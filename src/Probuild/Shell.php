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

    /**
     * @param $text
     * @return AbstractManager
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function write($text)
    {
        $this->output->writeln($text);

        return $this;
    }

    /**
     * @param string $path
     * @return string
     * @author Cristian Quiroz <cris@qcas.co>
     */
    protected function prepareDirectoryPath($path)
    {
        $path = rtrim($path, '/') . '/';

        return $path;
    }

    /**
     * @return OutputInterface
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param OutputInterface $output
     * @author Cristian Quiroz <cris@qcas.co>
     * @return AbstractManager
     */
    public function setOutput($output)
    {
        $this->output = $output;

        return $this;
    }
}