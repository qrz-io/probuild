<?php

namespace Probuild\Shell;

use Probuild\Shell;

class Grunt extends Shell
{

    /**
     * @param string $targetDir
     * @return Grunt
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function run($targetDir)
    {
        if (!`which npm`) {
            $this->write('<error>\'npm\' is not installed. </error>');
            return $this;
        }

        if (!`which grunt`) {
            $this->write('<error>\'grunt\' is not installed. </error>');
            return $this;
        }

        $gruntfilePath = $targetDir . '/Gruntfile.js';
        if (!is_file($gruntfilePath)) {
            $this->write(
                sprintf('<error>Cannot find gruntfile in \'%s\'</error>', $gruntfilePath)
            );
            return $this;
        }

        $this->exec("npm install --prefix {$targetDir} {$targetDir}");
        $this->exec("grunt --gruntfile {$targetDir}/Gruntfile.js devbuild-force");

        return $this;
    }
}