<?php

namespace Probuild\Shell;

use Probuild\Shell;

class Composer extends Shell
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

        $this->exec("composer update -d {$targetDir}");

        return $this;
    }
}
