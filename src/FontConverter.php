<?php
namespace Fatum12\TransfonterCore;

use Fatum12\TransfonterCore\Processor\Processor;

class FontConverter
{
    /**
     * @var ProgressTrigger
     */
    private $progressTrigger;
    /**
     * @var Processor[]
     */
    private $processors = [];

    public function __construct(ProgressTrigger $progressTrigger)
    {
        $this->progressTrigger = $progressTrigger;
    }

    /**
     * @param Processor $processor
     * @return $this
     */
    public function add(Processor $processor)
    {
        $this->processors[] = $processor;

        return $this;
    }

    public function stepsCount()
    {
        return count($this->processors);
    }

    /**
     * @param Font $font Source font file
     * @param string $dest Destination directory
     * @param Storage $options
     */
    public function convert(Font $font, $dest, Storage $options)
    {
        $result = new Storage();

        foreach ($this->processors as $processor) {
            $processor->process($font, $dest, $options, $result);
            $this->progressTrigger->nextStep();
        }
    }
}