<?php

namespace Probuild\Command;

use Probuild\Config;
use Probuild\Manager;
use Probuild\Shell;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeCommand extends Command
{

    /** @var Shell */
    protected $shell;
    /** @var  Manager\Directory */
    protected $directoryManager;
    /** @var Manager\Link */
    protected $linkManager;
    /** @var Manager\Composer */
    protected $composerManager;
    /** @var Manager\Grunt */
    protected $gruntManager;

    /**
     * @author Cristian Quiroz <cris@qcas.co>
     */
    protected function configure()
    {
        $this->setName('make')
            ->setDescription('Makes the build with the specified configuration.')
            ->addArgument('config', InputArgument::REQUIRED, 'Yaml config file with build settings.')
            ->addOption('test', 't', InputOption::VALUE_NONE, 'If set, no commands will be executed.');
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
            $this->getShell()->enableTestMode();
        }

        //Prepare Managers
        $this->getDirectoryManager()->setOutput($output);
        $this->getLinkManager()->setOutput($output);
        $this->getComposerManager()->setOutput($output);
        $this->getGruntManager()->setOutput($output);

        //Prepare target Directory
        $backupLocation = null;
        if ($config->shouldCleanTargetDirectory()) {
            $backupLocation = $this->getDirectoryManager()->backup(
                $config->getTargetDirectory(), $config->getCleanExceptions()
            );
            $this->getDirectoryManager()->clean($config->getTargetDirectory());
        }

        //Create main links
        $this->getLinkManager()->createLinks($config->getDirectoryPaths(), $config->getTargetDirectory());

        //Restore exceptions, if any
        if ($backupLocation !== null) {
            $this->getDirectoryManager()->restore($config->getTargetDirectory(), $backupLocation);
        }

        //Run composer
        if ($config->shouldRunComposer()) {
            $this->getComposerManager()->run($config->getTargetDirectory());
        }

        //Create post composer links
        $this->getLinkManager()->createLinks($config->getPostComposerDirectoryPaths(), $config->getTargetDirectory());

        //Run Grunt
        if ($config->shouldRunGrunt()) {
            $this->getGruntManager()->run($config->getTargetDirectory());
        }
    }

    /**
     * @return Shell
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function getShell()
    {
        return $this->shell;
    }

    /**
     * @param mixed $shell
     * @author Cristian Quiroz <cris@qcas.co>
     * @return MakeCommand
     */
    public function setShell($shell)
    {
        $this->shell = $shell;

        return $this;
    }

    /**
     * @return Manager\Directory
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function getDirectoryManager()
    {
        return $this->directoryManager;
    }

    /**
     * @param Manager\Directory $directoryManager
     * @author Cristian Quiroz <cris@qcas.co>
     * @return MakeCommand
     */
    public function setDirectoryManager($directoryManager)
    {
        $this->directoryManager = $directoryManager;

        return $this;
    }

    /**
     * @return Manager\Link
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function getLinkManager()
    {
        return $this->linkManager;
    }

    /**
     * @param Manager\Link $linkManager
     * @author Cristian Quiroz <cris@qcas.co>
     * @return MakeCommand
     */
    public function setLinkManager($linkManager)
    {
        $this->linkManager = $linkManager;

        return $this;
    }

    /**
     * @return Manager\Composer
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function getComposerManager()
    {
        return $this->composerManager;
    }

    /**
     * @param Manager\Composer $composerManager
     * @author Cristian Quiroz <cris@qcas.co>
     * @return MakeCommand
     */
    public function setComposerManager($composerManager)
    {
        $this->composerManager = $composerManager;

        return $this;
    }

    /**
     * @return Manager\Grunt
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function getGruntManager()
    {
        return $this->gruntManager;
    }

    /**
     * @param Manager\Grunt $gruntManager
     * @author Cristian Quiroz <cris@qcas.co>
     * @return MakeCommand
     */
    public function setGruntManager($gruntManager)
    {
        $this->gruntManager = $gruntManager;

        return $this;
    }
}