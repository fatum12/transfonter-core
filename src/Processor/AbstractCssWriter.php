<?php
namespace Fatum12\TransfonterCore\Processor;

use Fatum12\TransfonterCore\Font;
use Fatum12\TransfonterCore\Storage;
use Fatum12\TransfonterCore\Util\Template;

abstract class AbstractCssWriter implements Processor
{
    /**
     * @var resource
     */
    protected $file;
    
    public function __construct($file)
    {
        $this->file = $file;
    }

    public function process(Font $font, $dest, Storage $options, Storage $result)
    {
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
            $data[$format] = $this->getRule($result, $format, $file);
        }

        if ($result->has(Font::TYPE_SVG)) {
            $data['svgId'] = $this->getSvgId($font);
        }

        fwrite($this->file, "\n");
        fwrite($this->file, Template::render($this->getTemplateName(), $data));
    }

    /**
     * @param Storage $result
     * @param $format
     * @param $file
     * @return string
     */
    abstract protected function getRule(Storage $result, $format, $file);

    /**
     * @return string
     */
    abstract protected function getTemplateName();

    protected function getSvgId(Font $font)
    {
        return $font->getName();
    }
}