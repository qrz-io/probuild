<?php

namespace Probuild;

use \Symfony\Component\Yaml\Yaml;

class Config
{

    const TARGET_DIR = 'target-dir';
    const CLEAN = 'clean';
    const CLEAN_EXCEPTIONS = 'clean-exceptions';
    const DIR_PATHS = 'dir-paths';
    const COMPOSER = 'composer';
    const POST_COMPOSER_DIR_PATHS = 'post-composer-dir-paths';
    const GRUNT = 'grunt';

    /**
     * @param string $file
     * @return array
     * @throws \Exception
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function load($file)
    {
        $configData = $this->read($file);
        $this->validate($configData);

        return $configData;
    }

    /**
     * @param $file
     * @return array
     * @throws Exception\FileNotExists
     * @author Cristian Quiroz <cris@qcas.co>
     */
    protected function read($file)
    {
        if (!is_file($file)) {
            throw new Exception\FileNotExists('File doesn\'t exists');
        }

        return Yaml::parse($file);
    }

    /**
     * @param $config
     * @throws Exception\InvalidConfig
     * @author Cristian Quiroz <cris@qcas.co>
     */
    protected function validate($config)
    {
        if (!array_key_exists(self::TARGET_DIR, $config)) {
            throw new Exception\InvalidConfig(sprintf('\'%s\' has not been specified in the config.', self::TARGET_DIR));
        }

        if (!array_key_exists(self::DIR_PATHS, $config)) {
            throw new Exception\InvalidConfig(sprintf('\'%s\' has not been specified in the config.', self::DIR_PATHS));
        }
    }
}
