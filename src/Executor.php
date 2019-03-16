<?php

namespace DynamicComponents;

use Webmozart\Assert\Assert;

use function func_num_args;

class Executor extends \UI\Executor
{
    /** @var int */
    private $microseconds;

    /** @var int */
    private $seconds;

    /** @var callable|null */
    private $onExecute;

    /**
     * Just like \UI\Executor, if you only give 1 param, it will be treated as microseconds.
     */
    public function __construct(int $seconds = 0, ?int $microseconds = null, ?callable $onExecute = null)
    {
        $numArgs = func_num_args();

        if ($numArgs === 0) {
            $microseconds = 0;
        } elseif ($numArgs === 1) {
            $microseconds = $seconds;
            $seconds      = 0;
        }

        Assert::notNull($microseconds, 'Cannot set $microseconds to null. Set to 0 for no microseconds.');
        parent::__construct($seconds, $microseconds);

        $this->microseconds = $microseconds;
        $this->seconds      = $seconds;
        $this->onExecute    = $onExecute;
    }

    /**
     * @return int[] Tuple of [$seconds, $microseconds]
     */
    public function getInterval(): array
    {
        return [$this->seconds, $this->microseconds];
    }

    /**
     * Just like \UI\Executor, if you only give 1 param, it will be treated as microseconds.
     */
    public function setInterval(int $seconds, ?int $microseconds = null): void
    {
        if (func_num_args() === 1) {
            $microseconds = $seconds;
            $seconds      = 0;
        }

        Assert::notNull($microseconds, 'Cannot set $microseconds to null. Set to 0 for no microseconds.');
        parent::setInterval($seconds, $microseconds);

        $this->seconds      = $seconds;
        $this->microseconds = $microseconds;
    }

    public function setOnExecute(callable $onExecute): void
    {
        $this->onExecute = $onExecute;
    }

    protected function onExecute(): void
    {
        if ($onExecute = $this->onExecute) {
            $onExecute($this);
        }
    }
}
