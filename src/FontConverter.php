<?php

namespace Fatum12\TransfonterCore;

use Fatum12\TransfonterCore\Processor\Processor;

class FontConverter
{
    /**
     * @var ProgressTrigger
     */
    private ProgressTrigger $progressTrigger;

    /**
     * @var Processor[]
     */
    private array $processors = [];

    public function __construct(ProgressTrigger $progressTrigger)
    {
        $this->progressTrigger = $progressTrigger;
    }

    /**
     * @param Processor $processor
     * @return $this
     */
    public function add(Processor $processor): self
    {
        $this->processors[] = $processor;

        return $this;
    }

    public function stepsCount(): int
    {
        return count($this->processors);
    }

    /**
     * @param Font $font Source font file
     * @param Context $ctx
     */
    public function convert(Font $font, Context $ctx): void
    {
        $logger = $ctx->logger;
        $logger->info('process font', [
            'name' => $font->getName(),
            'path' => $font->getPath(),
        ]);

        $result = new Storage();

        foreach ($this->processors as $processor) {
            $logger->info('start processor ' . get_class($processor));

            $processor->process($font, $ctx, $result);
            $this->progressTrigger->nextStep();

            $logger->info('end processor', [
                'result' => $result->getAll(),
            ]);
        }
    }

    public function finalize(Context $ctx): void
    {
        foreach ($this->processors as $processor) {
            $processor->finalize($ctx);
        }
    }
}
