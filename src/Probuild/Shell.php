<?php

namespace Probuild;

use Symfony\Component\Console\Output\OutputInterface;

class Shell
{

    /** @var bool */
    protected $testMode = false;
    /** @var OutputInterface */
    protected $output;

    /**
     * @param $command
     * @return Shell
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function exec($command)
    {
        $commandOutput = "<info>{$command}</info>\n";

        if ($this->testMode) {
            $commandOutput = '<comment>(test mode) </comment>' . $commandOutput;
        } else {
            $commandOutput .= shell_exec($command);
        }

        if ($this->output instanceof OutputInterface) {
            $this->output->writeln($commandOutput);
        }

        return $this;
    }

    /**
     * @param OutputInterface $output
     * @return Shell
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;

        return $this;
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