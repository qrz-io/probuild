<?php

namespace Probuild\Command;

use Probuild\Config;
use Probuild\Shell;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeCommand extends Command
{

    /** @var  Shell\Directory */
    protected $directoryManager;
    /** @var Shell\Link */
    protected $linkManager;
    /** @var Shell\Composer */
    protected $composerManager;
    /** @var Shell\Grunt */
    protected $gruntManager;

    /**
     * @author Cristian Quiroz <cris@qcas.co>
     */
    protected function configure()
    {
        $this->setName('make')
            ->setDescription('Makes the build with the specified configuration.')
            ->addArgument('config', InputArgument::REQUIRED, 'Yaml config file with build settings.')
            ->addOption('test', 't', InputOption::VALUE_NONE, 'If set, no commands will be executed.')
            ->addOption('command-cp', 'cp', InputOption::VALUE_OPTIONAL, 'If set, overrides `cp` command.')
            ->addOption('command-composer', 'composer', InputOption::VALUE_OPTIONAL, 'If set, overrides `composer` command.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     * @author Cristian Quiroz <cris@qcas.co>
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = new Config($input->getArgument('config'));

        //Prepare shell
        if ($input->getOption('test')) {
            $this->getDirectoryManager()->enableTestMode();
            $this->getLinkManager()->enableTestMode();
            $this->getComposerManager()->enableTestMode();
            $this->getGruntManager()->enableTestMode();
        }

        //Prepare Managers
        $this->getDirectoryManager()->setOutput($output);
        $this->getLinkManager()->setOutput($output);
        $this->getComposerManager()->setOutput($output);
        $this->getGruntManager()->setOutput($output);

        //Prepare target Directory
        $backupLocation = null;
        if ($config->shouldCleanTargetDirectory()) {
            $output->writeln("\n<comment>## Cleaning target directory ##</comment>");
            $backupLocation = $this->getDirectoryManager()->backup(
                $config->getTargetDirectory(), $config->getCleanExceptions()
            );
            $this->getDirectoryManager()->clean($config->getTargetDirectory());
        }

        //Create main links
        $output->writeln("\n<comment>## Creating links to target directory ##</comment>");
        $this->getLinkManager()->createLinks($config->getDirectoryPaths(), $config->getTargetDirectory());

        //Restore exceptions, if any
        if ($backupLocation !== null) {
            $output->writeln("\n<comment>## Restoring backups to target directory ##</comment>");
            $this->getDirectoryManager()->restore($config->getTargetDirectory(), $backupLocation);
        }

        //Run composer
        if ($config->shouldRunComposer()) {
            $output->writeln("\n<comment>## Running composer on target directory ##</comment>");
            $this->getComposerManager()->run($config->getTargetDirectory());
        }

        //Create post composer links
        if (count($config->getPostComposerDirectoryPaths()) > 0) {
            $output->writeln("\n<comment>## Creating post composer links to target directory ##</comment>");
            $this->getLinkManager()->createLinks(
                $config->getPostComposerDirectoryPaths(),
                $config->getTargetDirectory()
            );
        }

        //Run Grunt
        if ($config->shouldRunGrunt()) {
            $output->writeln("\n<comment>## Running grunt on target directory ##</comment>");
            $this->getGruntManager()->run($config->getTargetDirectory());
        }

        //Clean up target directory
        $output->writeln("\n<comment>## Cleaning up target directory ##</comment>");
        $this->getDirectoryManager()->cleanup($config->getTargetDirectory());
    }

    /**
     * @return Shell\Directory
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function getDirectoryManager()
    {
        return $this->directoryManager;
    }

    /**
     * @param Shell\Directory $directoryManager
     * @author Cristian Quiroz <cris@qcas.co>
     * @return MakeCommand
     */
    public function setDirectoryManager($directoryManager)
    {
        $this->directoryManager = $directoryManager;

        return $this;
    }

    /**
     * @return Shell\Link
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function getLinkManager()
    {
        return $this->linkManager;
    }

    /**
     * @param Shell\Link $linkManager
     * @author Cristian Quiroz <cris@qcas.co>
     * @return MakeCommand
     */
    public function setLinkManager($linkManager)
    {
        $this->linkManager = $linkManager;

        return $this;
    }

    /**
     * @return Shell\Composer
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function getComposerManager()
    {
        return $this->composerManager;
    }

    /**
     * @param Shell\Composer $composerManager
     * @author Cristian Quiroz <cris@qcas.co>
     * @return MakeCommand
     */
    public function setComposerManager($composerManager)
    {
        $this->composerManager = $composerManager;

        return $this;
    }

    /**
     * @return Shell\Grunt
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function getGruntManager()
    {
        return $this->gruntManager;
    }

    /**
     * @param Shell\Grunt $gruntManager
     * @author Cristian Quiroz <cris@qcas.co>
     * @return MakeCommand
     */
    public function setGruntManager($gruntManager)
    {
        $this->gruntManager = $gruntManager;

        return $this;
    }
}