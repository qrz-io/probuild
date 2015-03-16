<?php

namespace Probuild\Shell;

use Probuild\Shell;

class Copy extends Shell
{

    /**
     * @param array $paths
     * @param string $target
     * @return Copy
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function link($paths, $target)
    {
        foreach ($paths as $path) {
            $path = $this->prepareDirectoryPath($path);
            $this->exec("{$this->cp} -alf {$path}. $target");
        }

        return $this;
    }

    /**
     * @param array $paths
     * @param string $target
     * @return Copy
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function copy($paths, $target)
    {
        foreach ($paths as $path) {
            $path = $this->prepareDirectoryPath($path);
            $this->exec("{$this->cp} -af {$path}. $target");
        }

        return $this;
    }
}
