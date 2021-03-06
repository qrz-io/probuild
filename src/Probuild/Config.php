<?php

namespace Probuild;

use \Symfony\Component\Yaml\Yaml;

class Config
{

    const TARGET_DIR = 'target-dir';
    const CLEAN = 'clean';
    const CLEAN_EXCEPTIONS = 'clean-exceptions';
    const LINK_DIR_PATHS = 'link-dir-paths';
    const COPY_DIR_PATHS = 'copy-dir-paths';
    const COMPOSER = 'composer';
    const COMPOSER_UPDATE = 'update';
    const COMPOSER_NO_DEV = 'no-dev';
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
        $this->data = $this->read($file);
        $this->validate();
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
    public function getLinkDirectoryPaths()
    {
        return $this->getDataArrayFromConfig(self::LINK_DIR_PATHS);
    }

    /**
     * @return array
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function getCopyDirectoryPaths()
    {
        return $this->getDataArrayFromConfig(self::COPY_DIR_PATHS);
    }

    /**
     * @return bool
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function shouldRunComposer()
    {
        return $this->isOptionTrueInConfig(self::COMPOSER);
    }

    /**
     * @return bool
     * @author Cristian Quiroz <cris@qrz.io>
     */
    public function shouldRunComposerUpdate()
    {
        return $this->isOptionTrueInConfig(self::COMPOSER_UPDATE);
    }

    /**
     * @return bool
     * @author Cristian Quiroz <cris@qrz.io>
     */
    public function shouldRunComposerNoDev()
    {
        return $this->isOptionTrueInConfig(self::COMPOSER_NO_DEV);
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
        return $this->isOptionTrueInConfig(self::GRUNT);
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
     * @param string $key
     * @return bool
     * @author Cristian Quiroz <cris@qrz.io>
     */
    protected function isOptionTrueInConfig($key)
    {
        return array_key_exists($key, $this->data) && $this->data[$key] == true;
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
     * @throws Exception\InvalidConfig
     * @author Cristian Quiroz <cris@qcas.co>
     */
    protected function validate()
    {
        if (!is_array($this->data)) {
            throw new Exception\InvalidConfig('Config could not be parsed correctly.');
        }

        if (!array_key_exists(self::TARGET_DIR, $this->data)) {
            throw new Exception\InvalidConfig(sprintf('\'%s\' has not been specified in the config.', self::TARGET_DIR));
        }

        if (count($this->getDataArrayFromConfig(self::LINK_DIR_PATHS)) == 0 && count($this->getDataArrayFromConfig(self::COPY_DIR_PATHS)) == 0) {
            throw new Exception\InvalidConfig(sprintf('No copy paths have been specified in the config.'));
        }
    }
}
