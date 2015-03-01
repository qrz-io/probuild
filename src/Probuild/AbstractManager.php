<?php

namespace Probuild;

use Symfony\Component\Console\Output\OutputInterface;

class AbstractManager
{

    /** @var Shell */
    protected $shell;
    /** @var OutputInterface */
    protected $output;

    /**
     * @param $text
     * @return AbstractManager
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function write($text)
    {
        $this->output->writeln($text);
    }

    /**
     * @param Shell $shell
     * @return AbstractManager
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function setShell(Shell $shell)
    {
        $this->shell = $shell;

        return $this;
    }

    /**
     * @return Shell
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function getShell()
    {
        return $this->shell;
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
