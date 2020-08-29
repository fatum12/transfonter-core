<?php

namespace Fatum12\TransfonterCore;

use Fatum12\TransfonterCore\Exception\ArgumentException;
use Fatum12\TransfonterCore\Processor\AutohintProcessor;
use Fatum12\TransfonterCore\Processor\Base64CssWriter;
use Fatum12\TransfonterCore\Processor\CssWriter;
use Fatum12\TransfonterCore\Processor\DehintProcessor;
use Fatum12\TransfonterCore\Processor\DropTtfProcessor;
use Fatum12\TransfonterCore\Processor\EotProcessor;
use Fatum12\TransfonterCore\Processor\FixVerticalMetricsProcessor;
use Fatum12\TransfonterCore\Processor\SubsetsProcessor;
use Fatum12\TransfonterCore\Processor\SvgProcessor;
use Fatum12\TransfonterCore\Processor\TtfProcessor;
use Fatum12\TransfonterCore\Processor\Woff2Processor;
use Fatum12\TransfonterCore\Processor\WoffProcessor;
use Fatum12\TransfonterCore\Util\Template;
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
    protected $options;
    /**
     * @var array Source files
     */
    protected $files = [];
    /**
     * @var array
     */
    protected $strings;
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
            // keep for backward compatibility
            'autohint' => false,
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

    public function add($path)
    {
        $this->files[] = $path;
    }

    public function loadFromDir($dir)
    {
        if (!is_dir($dir)) {
            throw new ArgumentException("Directory not found: {$dir}");
        }

        foreach (glob(Path::join($dir, '*.{ttf,otf,svg,woff,woff2}'), \GLOB_BRACE | \GLOB_NOSORT) as $file) {
            $this->add($file);
        }
    }

    public function process($dest)
    {
        sort($this->files);

        $this->logger->info('start processing', [
            'options' => $this->options->getAll(),
            'files' => $this->files,
            'destination' => $dest,
        ]);

        Path::mkdir($dest);

        $subdir = $this->options->get('fontsDirectory');
        if ($subdir !== '') {
            Path::mkdir(Path::join($dest, $subdir));
        }

        $converter = new FontConverter($this->progressTrigger, $this->logger);

        $converter
            ->add(new TtfProcessor())
            ->add(new SubsetsProcessor())
        ;

        $hinting = $this->options->get('hinting');
        if ($this->options->get('autohint') || $hinting === Hinting::TTFAUTOHINT) {
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

        $cssPath = Path::join($dest, $this->options->get('stylesheetName'));
        $cssFile = fopen($cssPath, 'wb');
        if ($cssFile === false) {
            throw new ArgumentException("Can't open file for writing: $cssPath");
        }
        if ($this->options->get('base64')) {
            $converter->add(new Base64CssWriter($cssFile));
        } else {
            $converter->add(new CssWriter($cssFile));
        }

        $this->progressTrigger
            ->reset()
            ->setTotalSteps(count($this->files) * $converter->stepsCount())
        ;

        $lang = $this->options->get('demoLanguage');
        if (!Language::isValidLang($lang)) {
            $lang = Language::LANG_EN;
        }

        $demoLetters = $this->strings[$lang]['letters'];
        $demoString = $this->strings[$lang]['pangram'];

        $demoTexts = [];

        $useFontFamily = $this->options->get('fontFamily', false);

        foreach ($this->files as $file) {
            $font = new Font($file);

            $isWoff2 = $font->getType() == Font::TYPE_WOFF2;
            if ($isWoff2) {
                // fontforge can't work with woff2, so we need to decompress it first
                $ttfFromWoff2 = Path::join(dirname($font->getPath()), Path::filename($font->getPath()) . '.ttf');
                $this->logger->info('decompress woff2', [
                    'source' => $font->getPath(),
                    'target' => $ttfFromWoff2,
                ]);
                Woff2::decompress($font->getPath());
                $font->setPath($ttfFromWoff2);
            }

            try {
                $this->logger->info('process font', [
                    'name' => $font->getName(),
                    'path' => $font->getPath(),
                ]);

                $converter->convert($font, Path::join($dest, $subdir), $this->options);

                $demoTexts[] = Template::render('demo_item', [
                    'fontName' => $font->getFullName(),
                    'letters' => $demoLetters,
                    'string' => $demoString,
                    'fontFamily' => $useFontFamily ? $font->getFamilyName() : $font->getName(),
                    'fontWeight' => $useFontFamily ? $font->getWeight() : 'normal',
                    'fontStyle' => $useFontFamily ? $font->getStyle() : 'normal',
                ]);
            } finally {
                if ($isWoff2) {
                    // remove decompressed woff2
                    $this->logger->info('unlink source woff2');
                    @unlink($font->getPath());
                }
            }
        }

        fclose($cssFile);

        // write demo file
        $demoPath = Path::join($dest, $this->options->get('demoName'));
        $this->logger->info('write demo html ' . $demoPath);
        file_put_contents($demoPath, Template::render('demo', [
            'stylesheet' => $this->options->get('stylesheetName'),
            'text' => implode("\n", $demoTexts),
        ]));

        $this->logger->info('end processing');
    }
}
