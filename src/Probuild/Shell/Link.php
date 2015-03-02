<?php

namespace Probuild\Shell;

use Probuild\Shell;

class Link extends Shell
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
            $this->exec("{$this->cp} -alf {$path}. $target");
        }

        return $this;
    }
}