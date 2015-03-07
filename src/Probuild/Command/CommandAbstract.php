<?php

namespace Probuild\Command;

use Probuild\Config;
use Probuild\Shell;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CommandAbstract extends Command
{

    /** @var  Shell\Directory */
    protected $directoryShell;
    /** @var Shell\Link */
    protected $linkShell;
    /** @var Shell\Composer */
    protected $composerShell;
    /** @var Shell\Grunt */
    protected $gruntShell;

    /**
     * @param string $configFile
     * @return \Probuild\Config
     * @author Cristian Quiroz <cris@qrz.io>
     */
    public function getConfig($configFile)
    {
        if (!$configFile) {
            $configFile = './config.yaml';
        }
        $config = new Config($configFile);

        return $config;
    }

    /**
     * @return CommandAbstract
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function enableTestMode()
    {
        if ($this->getDirectoryShell()) {
            $this->getDirectoryShell()->enableTestMode();
        }

        if ($this->getLinkShell()) {
            $this->getLinkShell()->enableTestMode();
        }

        if ($this->getComposerShell()) {
            $this->getComposerShell()->enableTestMode();
        }

        if ($this->getGruntShell()) {
            $this->getGruntShell()->enableTestMode();
        }

        return $this;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return CommandAbstract
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function setShellOutput(OutputInterface $output)
    {
        if ($this->getDirectoryShell()) {
            $this->getDirectoryShell()->setOutput($output);
        }

        if ($this->getLinkShell()) {
            $this->getLinkShell()->setOutput($output);
        }

        if ($this->getComposerShell()) {
            $this->getComposerShell()->setOutput($output);
        }

        if ($this->getGruntShell()) {
            $this->getGruntShell()->setOutput($output);
        }

        return $this;
    }

    /**
     * @return Shell\Directory
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function getDirectoryShell()
    {
        return $this->directoryShell;
    }

    /**
     * @param Shell\Directory $directoryShell
     * @author Cristian Quiroz <cris@qcas.co>
     * @return MakeCommand
     */
    public function setDirectoryShell($directoryShell)
    {
        $this->directoryShell = $directoryShell;

        return $this;
    }

    /**
     * @return Shell\Link
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function getLinkShell()
    {
        return $this->linkShell;
    }

    /**
     * @param Shell\Link $linkShell
     * @author Cristian Quiroz <cris@qcas.co>
     * @return MakeCommand
     */
    public function setLinkShell($linkShell)
    {
        $this->linkShell = $linkShell;

        return $this;
    }

    /**
     * @return Shell\Composer
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function getComposerShell()
    {
        return $this->composerShell;
    }

    /**
     * @param Shell\Composer $composerShell
     * @author Cristian Quiroz <cris@qcas.co>
     * @return MakeCommand
     */
    public function setComposerShell($composerShell)
    {
        $this->composerShell = $composerShell;

        return $this;
    }

    /**
     * @return Shell\Grunt
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function getGruntShell()
    {
        return $this->gruntShell;
    }

    /**
     * @param Shell\Grunt $gruntShell
     * @author Cristian Quiroz <cris@qcas.co>
     * @return MakeCommand
     */
    public function setGruntShell($gruntShell)
    {
        $this->gruntShell = $gruntShell;

        return $this;
    }
}
