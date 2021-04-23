<?php

namespace Fatum12\TransfonterCore\Processor;

use Fatum12\TransfonterCore\Context;
use Fatum12\TransfonterCore\Exception\ArgumentException;
use Fatum12\TransfonterCore\Font;
use Fatum12\TransfonterCore\Storage;
use Fatum12\TransfonterCore\Util\Path;
use Fatum12\TransfonterCore\Util\Template;

class DemoPageProcessor extends Processor
{
    /**
     * @var array
     */
    private $letters;

    /**
     * @var string
     */
    private $pangram;

    /**
     * @var array
     */
    private $demoTexts = [];

    public function __construct(array $letters, string $pangram)
    {
        $this->letters = $letters;
        $this->pangram = $pangram;
    }

    public function process(Font $font, Context $ctx, Storage $result): void
    {
        $useFontFamily = $ctx->options->get('fontFamily', false);

        $this->demoTexts[] = Template::render('demo_item', [
            'fontName' => $font->getFullName(),
            'letters' => $this->letters,
            'string' => $this->pangram,
            'fontFamily' => $useFontFamily ? $font->getFamilyName() : $font->getName(),
            'fontWeight' => $useFontFamily ? $font->getWeight() : 'normal',
            'fontStyle' => $useFontFamily ? $font->getStyle() : 'normal',
        ]);
    }

    public function finalize(Context $ctx): void
    {
        $options = $ctx->options;
        $demoPath = Path::join($ctx->targetDir, $options->get('demoName'));

        $ctx->logger->info('write demo html ' . $demoPath);

        $result = file_put_contents($demoPath, Template::render('demo', [
            'stylesheet' => $options->get('stylesheetName'),
            'text' => implode("\n", $this->demoTexts),
        ]));
        if ($result === false) {
            throw new ArgumentException("Can't write demo html: $demoPath");
        }
    }
}
