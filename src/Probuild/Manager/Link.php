<?php

namespace Probuild\Manager;

use Probuild\AbstractManager;

class Link extends AbstractManager
{

    /**
     * @param array $paths
     * @param string $target
     * @return Link
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function createLinks($paths, $target)
    {
        foreach ($paths as $path) {
            $path = $this->prepareDirectoryPath($path);
            $this->getShell()->exec("ln -s {$path}* $target");
        }

        return $this;
    }
}