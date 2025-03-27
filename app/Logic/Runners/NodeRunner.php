<?php

namespace App\Logic\Runners;

use App\Facades\LogicRunner;
use App\Facades\SetupRunner;
use App\Logic\Contracts\NodeContract;
use App\Logic\Process;

class NodeRunner
{
    public function run(NodeContract $node, Process $process): void
    {
        $process->startTimer($node->getCode() .'::before', $id);
        SetupRunner::run($node->getBefore(), $process);
        $process->stopTimer($id);

        $process->startTimer($node->getCode() .'::run', $id);
        LogicRunner::run($node, $process);
        $process->stopTimer($id);

        $process->startTimer($node->getCode() .'::after', $id);
        SetupRunner::run($node->getAfter(), $process);
        $process->stopTimer($id);
    }
}
