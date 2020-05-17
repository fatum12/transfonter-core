<?php

namespace Fatum12\TransfonterCore;

use Fatum12\TransfonterCore\Processor\Processor;
use Psr\Log\LoggerInterface;

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
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(ProgressTrigger $progressTrigger, LoggerInterface $logger)
    {
        $this->progressTrigger = $progressTrigger;
        $this->logger = $logger;
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
            $this->logger->info('start processor ' . get_class($processor));

            $processor->process($font, $dest, $options, $result);
            $this->progressTrigger->nextStep();

            $this->logger->info('end processor', [
                'result' => $result->getAll(),
            ]);
        }
    }
}
