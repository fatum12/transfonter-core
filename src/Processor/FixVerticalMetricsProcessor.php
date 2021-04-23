<?php

namespace Fatum12\TransfonterCore\Processor;

use Fatum12\TransfonterCore\Context;
use Fatum12\TransfonterCore\Font;
use Fatum12\TransfonterCore\Storage;
use Fatum12\TransfonterCore\Exception\CommandError;
use Fatum12\TransfonterCore\Tools\FontTools;

class FixVerticalMetricsProcessor extends Processor
{
    public function process(Font $font, Context $ctx, Storage $result): void
    {
        try {
            FontTools::fixVerticalMetrics($result->get(Font::TYPE_TTF));
        } catch (CommandError $e) {
            // ignore errors
            $ctx->logger->warning($e->getMessage());
        }
    }
}
