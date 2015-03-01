<?php

namespace Probuild;

use Symfony\Component\Console\Output\OutputInterface;

class Shell
{

    /** @var bool */
    protected $testMode = false;

    /**
     * @param $command
     * @return Shell
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function exec($command)
    {
        $commandOutput = "<info>{$command}</info>";

        if ($this->testMode) {
            $commandOutput = '<comment>#(test mode) </comment>' . $commandOutput;
        } else {
            $commandOutput .= shell_exec($command);
        }

       return $commandOutput;
    }

    /**
     * @return Shell
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function enableTestMode()
    {
        $this->testMode = true;

        return $this;
    }
}