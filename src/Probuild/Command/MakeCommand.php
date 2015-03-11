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

        //Execute
        $backupLocation = $this->prepareTargetDirectory($output, $config);
        $this->createMainLinks($output, $config);
        $this->restoreBackup($output, $config, $backupLocation);
        $this->runComposer($output, $config);
        $this->createPostComposerLinks($output, $config);
        $this->runGrunt($output, $config);
        $this->cleanup($output, $config);
    }
}
