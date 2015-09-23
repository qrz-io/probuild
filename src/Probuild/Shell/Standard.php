<?php

namespace Probuild\Shell;

use Probuild\Shell;

class Standard extends Shell
{

    /**
     * @param $targetDir
     * @param $command
     * @return $this
     * @author Cristian Quiroz <cristian.quiroz@ampersandcommerce.com>
     */
    public function run($targetDir, $command)
    {
        $command = "cd {$targetDir}; " . $command;
        $this->exec($command);

        return $this;
    }
}
