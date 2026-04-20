<?php

namespace Fatum12\TransfonterCore;

use Fatum12\TransfonterCore\Exception\ArgumentException;
use Fatum12\TransfonterCore\Processor\AutohintProcessor;
use Fatum12\TransfonterCore\Processor\Base64CssWriter;
use Fatum12\TransfonterCore\Processor\CssWriter;
use Fatum12\TransfonterCore\Processor\DehintProcessor;
use Fatum12\TransfonterCore\Processor\DemoPageProcessor;
use Fatum12\TransfonterCore\Processor\DropTtfProcessor;
use Fatum12\TransfonterCore\Processor\EotProcessor;
use Fatum12\TransfonterCore\Processor\FixVerticalMetricsProcessor;
use Fatum12\TransfonterCore\Processor\SubsetsProcessor;
use Fatum12\TransfonterCore\Processor\SvgProcessor;
use Fatum12\TransfonterCore\Processor\TtfProcessor;
use Fatum12\TransfonterCore\Processor\Woff2Processor;
use Fatum12\TransfonterCore\Processor\WoffProcessor;
use Fatum12\TransfonterCore\Tools\Woff2;
use Fatum12\TransfonterCore\Util\Path;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

class FontManager
{
    use LoggerAwareTrait;

    /**
     * @var Storage
     */
    private $options;

    /**
     * @var array Source files
     */
    private $files = [];

    /**
     * @var array
     */
    private $strings;

    /**
     * @var ProgressTrigger
     */
    private $progressTrigger;

    public function __construct(array $options = [], callable $onProgress = null)
    {
        $this->options = new Storage(array_replace([
            'stylesheetName' => 'stylesheet.css',
            'demoName' => 'demo.html',
            'demoLanguage' => Language::LANG_EN,
            'formats' => [Font::TYPE_WOFF, Font::TYPE_WOFF2],
            'subsets' => [],
            'hinting' => Hinting::KEEP_EXISTING,
            // add local rule
            'local' => false,
            // embed font in CSS
            'base64' => false,
            // family support in CSS
            'fontFamily' => false,
            'fixVerticalMetrics' => false,
            'fontDisplay' => FontDisplay::AUTO,
            'fontsDirectory' => '',
        ], $options));

        $subdir = (string)$this->options->get('fontsDirectory');
        $subdir = Path::normalize($subdir);
        $this->options->set('fontsDirectory', $subdir);

        $this->strings = json_decode(file_get_contents(__DIR__ . '/strings.json'), true);

        $this->progressTrigger = new ProgressTrigger();
        if (!is_null($onProgress)) {
            $this->progressTrigger->onProgress($onProgress);
        }

        $this->logger = new NullLogger();
    }

    public function add(string $path): void
    {
        $this->files[] = $path;
    }

    public function loadFromDir(string $dir): void
    {
        if (!is_dir($dir)) {
            throw new ArgumentException("Directory not found: $dir");
        }

        foreach (glob(Path::join($dir, '*.{' . implode(',', Font::sourceTypes()) . '}'), \GLOB_BRACE | \GLOB_NOSORT) as $file) {
            $this->add($file);
        }
    }

    public function getOption(string $key, $default = null)
    {
        return $this->options->get($key, $default);
    }

    public function process(string $targetDir): void
    {
        if (empty($this->files)) {
            throw new ArgumentException('Empty files list');
        }

        sort($this->files);

        $this->logger->info('start processing', [
            'options' => $this->options->getAll(),
            'files' => $this->files,
            'destination' => $targetDir,
        ]);

        $subdir = (string)$this->options->get('fontsDirectory');
        $fontsTargetDir = Path::join($targetDir, $subdir);

        Path::mkdir($fontsTargetDir);

        $converter = new FontConverter($this->progressTrigger);

        $converter
            ->add(new TtfProcessor())
            ->add(new SubsetsProcessor())
        ;

        $hinting = $this->options->get('hinting');
        if ($hinting === Hinting::TTFAUTOHINT) {
            $converter->add(new AutohintProcessor());
        } elseif ($hinting === Hinting::DEHINT) {
            $converter->add(new DehintProcessor());
        }

        if ($this->options->get('fixVerticalMetrics')) {
            $converter->add(new FixVerticalMetricsProcessor());
        }

        $formats = $this->options->get('formats', []);
        if (in_array(Font::TYPE_EOT, $formats)) {
            $converter->add(new EotProcessor());
        }
        if (in_array(Font::TYPE_WOFF, $formats)) {
            $converter->add(new WoffProcessor());
        }
        if (in_array(Font::TYPE_WOFF2, $formats)) {
            $converter->add(new Woff2Processor());
        }
        if (in_array(Font::TYPE_SVG, $formats)) {
            $converter->add(new SvgProcessor());
        }
        if (!in_array(Font::TYPE_TTF, $formats)) {
            $converter->add(new DropTtfProcessor());
        }

        $cssPath = Path::join($targetDir, $this->options->get('stylesheetName'));
        if ($this->options->get('base64')) {
            $converter->add(new Base64CssWriter($cssPath));
        } else {
            $converter->add(new CssWriter($cssPath));
        }

        $lang = $this->options->get('demoLanguage');
        if ($lang) {
            if (!Language::isValidLang($lang)) {
                throw new ArgumentException("Unsupported language: $lang");
            }

            $demoLetters = $this->strings[$lang]['letters'];
            $demoPangram = $this->strings[$lang]['pangram'];
            $converter->add(new DemoPageProcessor($demoLetters, $demoPangram));
        }

        $files = [];
        $unpackDir = false;

        try {
            foreach ($this->files as $file) {
                $font = new Font($file);
                if ($font->isCollection()) {
                    if (!$unpackDir) {
                        $unpackDir = Path::createTempDirectory("ttc_");
                    }
                    $unpackedFiles = $font->unpack($unpackDir, false);
                    $files = array_merge($files, $unpackedFiles);
                } else {
                    $files[] = $file;
                }
            }

            $this->progressTrigger
                ->reset()
                ->setTotalSteps(count($files) * $converter->stepsCount());

            $ctx = new Context();
            $ctx->targetDir = $targetDir;
            $ctx->fontsTargetDir = $fontsTargetDir;
            $ctx->options = $this->options;
            $ctx->logger = $this->logger;

            foreach ($files as $file) {
                $font = new Font($file);

                try {
                    $converter->convert($font, $ctx);
                } catch (\Exception $e) {
                    $converter->finalize($ctx);
                    throw $e;
                }
            }

            $converter->finalize($ctx);
        } finally {
            if ($unpackDir) {
                Path::removeDirectory($unpackDir);
            }
        }

        $this->logger->info('end processing');
    }
}
