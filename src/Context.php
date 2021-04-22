<?php

namespace Fatum12\TransfonterCore;

use Psr\Log\LoggerInterface;

class Context
{
    /**
     * @var string
     */
    public $targetDir;

    /**
     * @var string
     */
    public $fontsTargetDir;

    /**
     * @var Storage
     */
    public $options;

    /**
     * @var LoggerInterface
     */
    public $logger;
}
