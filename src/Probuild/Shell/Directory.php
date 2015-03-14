<?php

namespace Probuild\Shell;

use Probuild\Shell;

class Directory extends Shell
{

    const TEMP_FOLDER = '/tmp/probuild/';

    /**
     * @param string $targetDirectory
     * @param array $cleanExceptions
     * @return string
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function backup($targetDirectory, $cleanExceptions)
    {
        $tempFolder = self::TEMP_FOLDER . time() . '/';
        $targetDirectory = $this->prepareDirectoryPath($targetDirectory);

        foreach ($cleanExceptions as $cleanException) {

            // full temporary path for clean exception
            $fullTempPath = $tempFolder . $cleanException;
            // full original path for clean exception
            $fullOrigPath = $targetDirectory . $cleanException;

            if (file_exists($fullOrigPath)) {
                $dirTempPath = pathinfo($fullTempPath, PATHINFO_DIRNAME);
                $this->exec("mkdir -p -m 0777 {$dirTempPath}");
                $this->exec("cp -r {$fullOrigPath} {$dirTempPath}");
            } else {
                $this->write(
                    sprintf('<error>\'%s\' is not a valid file. Ignoring.</error>', $fullOrigPath)
                );
            }
        }

        return $tempFolder;
    }

    /**
     * @param string $target
     * @return Directory
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function clean($target)
    {
        // checks for irrational fears
        if ($target == "/" || strlen($target) <= 1) {
            throw new InvalidConfig('<error>\'%s\' is not a valid target directory. Exiting.</error>', $target);

        }

        // remove target directory if it exists
        if (file_exists($target)) {
            $this->exec("rm -rf {$target}");
        }

        // create target directory
        $this->exec("mkdir -p -m 0777 {$target}");

        return $this;
    }

    /**
     * @param string $targetDirectory
     * @param string $backupDirectory
     * @return Directory
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function restore($targetDirectory, $backupDirectory)
    {
        $this->exec("cp -r {$backupDirectory}. {$targetDirectory}");

        return $this;
    }

    /**
     * @param $targetDirectory
     * @return Directory
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function cleanup($targetDirectory)
    {
        $this->exec("chmod -R 777 {$targetDirectory}");
        $this->exec("rm -rf `find {$targetDirectory} -type d -name .svn`");
        $this->exec("rm -rf `find {$targetDirectory} -type d -name .git`");
    }
}
