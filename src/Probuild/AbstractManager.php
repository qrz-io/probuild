<?php

namespace Probuild;

class AbstractManager
{

    /** @var Shell */
    protected $shell;

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
}