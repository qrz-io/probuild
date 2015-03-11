<?php

namespace Probuild\Shell;

use Probuild\Shell;

class Composer extends Shell
{

    protected $composer = 'composer';

    /**
     * @param string $targetDir
     * @return Composer
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function run($targetDir, $update, $noDev)
    {
        if (!`which {$this->composer}`) {
            $this->write(sprintf('<error>\'%s\' is not installed.</error>', $this->composer));
            return $this;
        }

        $composerCommand = $this->composer;
        $composerCommand .= $update ? ' update' : ' install';
        $composerCommand .= $noDev ? ' --no-dev' : '';

        $this->exec("{$composerCommand} -d {$targetDir}");

        return $this;
    }

    /**
     * @param string $composer
     * @author Cristian Quiroz <cris@qcas.co>
     * @return Composer
     */
    public function setComposerCommand($composer)
    {
        $this->composer = $composer;

        return $this;
    }


}
