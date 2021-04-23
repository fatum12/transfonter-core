<?php

namespace Fatum12\TransfonterCore\Processor;

use Fatum12\TransfonterCore\Context;
use Fatum12\TransfonterCore\Exception\ArgumentException;
use Fatum12\TransfonterCore\Font;
use Fatum12\TransfonterCore\FontDisplay;
use Fatum12\TransfonterCore\Storage;
use Fatum12\TransfonterCore\Util\Template;

abstract class AbstractCssWriter extends Processor
{
    /**
     * @var string
     */
    private $filePath;

    /**
     * @var resource
     */
    private $file;
    
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function process(Font $font, Context $ctx, Storage $result): void
    {
        if (!$this->file) {
            $this->file = fopen($this->filePath, 'wb');
            if ($this->file === false) {
                throw new ArgumentException("Can't open file for writing: {$this->filePath}");
            }
        }

        $options = $ctx->options;
        $useFamily = $options->get('fontFamily');
        $formats = $options->get('formats', []);
        $addLocalRule = $options->get('local', false);

        if ($font->getType() == Font::TYPE_SVG) {
            $addLocalRule = false;
        }

        $data = [
            'name' => $useFamily ? $font->getFamilyName() : $font->getName(),
            'weight' => $useFamily ? $font->getWeight() : 'normal',
            'style' => $useFamily ? $font->getStyle() : 'normal',
            'local' => $addLocalRule,
            'localName' => $font->getFullName(),
            'localPostScriptName' => $font->getName(),
            'eotOnly' => count($formats) == 1 && in_array(Font::TYPE_EOT, $formats)
        ];

        foreach ($result->getAll() as $format => $file) {
            if ($format == Font::TYPE_TTF && !in_array(Font::TYPE_TTF, $formats)) {
                continue;
            }
            $data[$format] = $this->getRule($options, $result, $format, $file);
        }

        if ($result->has(Font::TYPE_SVG)) {
            $data['svgId'] = $this->getSvgId($font);
        }

        $fontDisplay = $options->get('fontDisplay');
        if ($fontDisplay && $fontDisplay != FontDisplay::AUTO) {
            $data['display'] = $fontDisplay;
        }

        fwrite($this->file, Template::render($this->getTemplateName(), $data));
        fwrite($this->file, "\n");
    }

    public function finalize(Context $ctx): void
    {
        if ($this->file) {
            fclose($this->file);
        }
    }

    /**
     * @param Storage $options
     * @param Storage $result
     * @param string $format
     * @param string $file
     * @return string
     */
    abstract protected function getRule(Storage $options, Storage $result, string $format, string $file): string;

    /**
     * @return string
     */
    abstract protected function getTemplateName(): string;

    private function getSvgId(Font $font): string
    {
        return $font->getName();
    }
}
