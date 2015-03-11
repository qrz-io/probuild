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
    const GRUNT_TASKS = 'grunt-tasks';

    /** @var array */
    protected $data;

    /**
     * @param string $file
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function __construct($file)
    {
        $this->load($file);
    }

    /**
     * @param string $file
     * @return array
     * @throws \Exception
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function load($file)
    {
        $data = $this->read($file);
        $this->validate($data);

        $this->data = $data;
    }

    /**
     * @return string
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function getTargetDirectory()
    {
        return $this->data[self::TARGET_DIR];
    }

    /**
     * @return bool
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function shouldCleanTargetDirectory()
    {
        return array_key_exists(self::CLEAN, $this->data) && $this->data[self::CLEAN] == true;
    }

    /**
     * @return array
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function getCleanExceptions()
    {
        return $this->getDataArrayFromConfig(self::CLEAN_EXCEPTIONS);
    }

    /**
     * @return array
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function getDirectoryPaths()
    {
        return $this->data[self::DIR_PATHS];
    }

    /**
     * @return bool
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function shouldRunComposer()
    {
        return array_key_exists(self::COMPOSER, $this->data) && $this->data[self::COMPOSER] == true;
    }

    /**
     * @return array
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function getPostComposerDirectoryPaths()
    {
        return $this->getDataArrayFromConfig(self::POST_COMPOSER_DIR_PATHS);
    }

    /**
     * @return bool
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function shouldRunGrunt()
    {
        return array_key_exists(self::GRUNT, $this->data) && $this->data[self::GRUNT] == true;
    }

    /**
     * @return array
     * @author Cristian Quiroz <cris@qrz.io>
     */
    public function getGruntTasks()
    {
        return $this->getDataArrayFromConfig(self::GRUNT_TASKS);
    }

    /**
     * @param string $key
     * @return array
     * @author Cristian Quiroz <cris@qrz.io>
     */
    protected function getDataArrayFromConfig($key)
    {
        if (!array_key_exists($key, $this->data) || !is_array($this->data[$key])) {
            return array();
        }

        return $this->data[$key];
    }

    /**
     * @param string $file
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
     * @param array $config
     * @throws Exception\InvalidConfig
     * @author Cristian Quiroz <cris@qcas.co>
     */
    protected function validate($config)
    {
        if (!array_key_exists(self::TARGET_DIR, $config)) {
            throw new Exception\InvalidConfig(sprintf('\'%s\' has not been specified in the config.', self::TARGET_DIR));
        }

        if (!array_key_exists(self::DIR_PATHS, $config) || count($config[self::DIR_PATHS]) == 0) {
            throw new Exception\InvalidConfig(sprintf('\'%s\' has not been specified in the config.', self::DIR_PATHS));
        }
    }
}
