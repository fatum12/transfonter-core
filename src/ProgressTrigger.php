<?php
namespace Fatum12\TransfonterCore;

class ProgressTrigger
{
    /**
     * @var callable
     */
    private $callback;
    /**
     * @var int
     */
    private $totalSteps = 0;
    /**
     * @var int
     */
    private $currentStep;

    public function __construct()
    {
        $this->callback = function ($progress) {};
        $this->reset();
    }

    public function onProgress(callable $callback)
    {
        $this->callback = $callback;

        return $this;
    }

    public function reset()
    {
        $this->currentStep = 0;

        return $this;
    }

    public function nextStep()
    {
        $this->currentStep++;
        $progress = floor($this->currentStep * 100 / $this->totalSteps);
        if ($progress > 100) {
            $progress = 100;
        }
        call_user_func($this->callback, $progress);

        return $this;
    }

    public function setTotalSteps($total)
    {
        $this->totalSteps = $total;

        return $this;
    }
}