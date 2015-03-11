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
            $configFile = './.probuild.yaml';
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
     * @param Config $config
     * @param $output
     * @return null|string
     * @author Cristian Quiroz <cris@qrz.io>
     */
    public function prepareTargetDirectory(OutputInterface $output, Config $config)
    {
        $backupLocation = null;
        if ($config->shouldCleanTargetDirectory()) {
            $output->writeln("\n<comment>## Cleaning target directory ##</comment>");
            $backupLocation = $this->getDirectoryShell()->backup(
                $config->getTargetDirectory(), $config->getCleanExceptions()
            );
            $this->getDirectoryShell()->clean($config->getTargetDirectory());
        }

        return $backupLocation;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \Probuild\Config $config
     * @return void
     * @author Cristian Quiroz <cris@qrz.io>
     */
    public function createMainLinks(OutputInterface $output, Config $config)
    {
        $output->writeln("\n<comment>## Creating links to target directory ##</comment>");
        $this->getLinkShell()->createLinks($config->getDirectoryPaths(), $config->getTargetDirectory());
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \Probuild\Config $config
     * @param string $backupLocation
     * @return void
     * @author Cristian Quiroz <cris@qrz.io>
     */
    public function restoreBackup(OutputInterface $output, Config $config, $backupLocation)
    {
        if ($backupLocation !== null) {
            $output->writeln("\n<comment>## Restoring backups to target directory ##</comment>");
            $this->getDirectoryShell()->restore($config->getTargetDirectory(), $backupLocation);
        }
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \Probuild\Config $config
     * @return void
     * @author Cristian Quiroz <cris@qrz.io>
     */
    public function runComposer(OutputInterface $output, Config $config)
    {
        if ($config->shouldRunComposer()) {
            $output->writeln("\n<comment>## Running composer on target directory ##</comment>");
            $this->getComposerShell()->run($config->getTargetDirectory());
        }
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \Probuild\Config $config
     * @return void
     * @author Cristian Quiroz <cris@qrz.io>
     */
    public function createPostComposerLinks(OutputInterface $output, Config $config)
    {
        if (count($config->getPostComposerDirectoryPaths()) > 0) {
            $output->writeln("\n<comment>## Creating post composer links to target directory ##</comment>");
            $this->getLinkShell()->createLinks(
                $config->getPostComposerDirectoryPaths(),
                $config->getTargetDirectory()
            );
        }
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \Probuild\Config $config
     * @return void
     * @author Cristian Quiroz <cris@qrz.io>
     */
    public function runGrunt(OutputInterface $output, Config $config)
    {
        if ($config->shouldRunGrunt() && count($config->getGruntTasks()) > 0) {
            $output->writeln("\n<comment>## Running grunt on target directory ##</comment>");
            $this->getGruntShell()->run($config->getTargetDirectory(), $config->getGruntTasks());
        }
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \Probuild\Config $config
     * @return void
     * @author Cristian Quiroz <cris@qrz.io>
     */
    public function cleanup(OutputInterface $output, Config $config)
    {
        $output->writeln("\n<comment>## Cleaning up target directory ##</comment>");
        $this->getDirectoryShell()->cleanup($config->getTargetDirectory());
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
