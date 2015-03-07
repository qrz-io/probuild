<?php

namespace Probuild\Command;

use Probuild\Config;
use Probuild\Shell;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeCommand extends CommandAbstract
{

    /**
     * @author Cristian Quiroz <cris@qcas.co>
     */
    protected function configure()
    {
        $this->setName('make')
            ->setDescription('Makes the build with the specified configuration.')
            ->addArgument('config', InputArgument::OPTIONAL, 'Yaml config file with build settings. If not defined, config.yaml will be tried.')
            ->addOption('test', 't', InputOption::VALUE_NONE, 'If set, no commands will be executed.')
            ->addOption('cp-command', 'c', InputOption::VALUE_REQUIRED, 'If set, overrides `cp` command.')
            ->addOption('composer-command', 'p', InputOption::VALUE_REQUIRED, 'If set, overrides `composer` command.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     * @author Cristian Quiroz <cris@qcas.co>
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configFile = $input->getArgument('config');
        $config = $this->getConfig($configFile);

        //Prepare shell
        if ($input->getOption('test')) {
            $this->enableTestMode();
        }

        //Use cp and composer if set
        if ($cpCommand = $input->getOption('cp-command')) {
            $this->getLinkShell()->setCpCommand($cpCommand);
            $this->getDirectoryShell()->setCpCommand($cpCommand);
        }

        if ($composerCommand = $input->getOption('composer-command')) {
            $this->getComposerShell()->setComposerCommand($composerCommand);
        }

        //Prepare Shells
        $this->setShellOutput($output);

        //Prepare target Directory
        $backupLocation = null;
        if ($config->shouldCleanTargetDirectory()) {
            $output->writeln("\n<comment>## Cleaning target directory ##</comment>");
            $backupLocation = $this->getDirectoryShell()->backup(
                $config->getTargetDirectory(), $config->getCleanExceptions()
            );
            $this->getDirectoryShell()->clean($config->getTargetDirectory());
        }

        //Create main links
        $output->writeln("\n<comment>## Creating links to target directory ##</comment>");
        $this->getLinkShell()->createLinks($config->getDirectoryPaths(), $config->getTargetDirectory());

        //Restore exceptions, if any
        if ($backupLocation !== null) {
            $output->writeln("\n<comment>## Restoring backups to target directory ##</comment>");
            $this->getDirectoryShell()->restore($config->getTargetDirectory(), $backupLocation);
        }

        //Run composer
        if ($config->shouldRunComposer()) {
            $output->writeln("\n<comment>## Running composer on target directory ##</comment>");
            $this->getComposerShell()->run($config->getTargetDirectory());
        }

        //Create post composer links
        if (count($config->getPostComposerDirectoryPaths()) > 0) {
            $output->writeln("\n<comment>## Creating post composer links to target directory ##</comment>");
            $this->getLinkShell()->createLinks(
                $config->getPostComposerDirectoryPaths(),
                $config->getTargetDirectory()
            );
        }

        //Run Grunt
        if ($config->shouldRunGrunt()) {
            $output->writeln("\n<comment>## Running grunt on target directory ##</comment>");
            $this->getGruntShell()->run($config->getTargetDirectory(), $config->getGruntTasks());
        }

        //Clean up target directory
        $output->writeln("\n<comment>## Cleaning up target directory ##</comment>");
        $this->getDirectoryShell()->cleanup($config->getTargetDirectory());
    }
}
