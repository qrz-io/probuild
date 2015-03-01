<?php

namespace Probuild\Manager;

use Probuild\AbstractManager;

class Composer extends AbstractManager
{

    /**
     * @param string $targetDir
     * @return Composer
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function run($targetDir)
    {
        if (!`which composer`) {
            $this->write('<error>\'composer\' is not installed.</error>');
            return $this;
        }
        $this->write(
            $this->getShell()->exec("composer update -d {$targetDir}")
        );

        return $this;
    }
}
