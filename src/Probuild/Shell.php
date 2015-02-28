<?php

namespace Probuild;

use Symfony\Component\Console\Output\OutputInterface;

class Shell
{

    /** @var bool */
    protected $isDryRun = false;

    /**
     * @param $command
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return Shell
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function exec($command, OutputInterface $output)
    {
        $output->writeln("<info>{$command}</info>");

        if (!$this->isDryRun) {
            $result = shell_exec($command);
            $output->writeln($result);
        }

        return $this;
    }

    /**
     * @return Shell
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function enableDryRun()
    {
        $this->isDryRun = true;

        return $this;
    }
}