<?php

namespace Probuild\Command;

use Probuild\Config;
use Probuild\Shell;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LinkCommand extends CommandAbstract
{

    /**
     * @author Cristian Quiroz <cris@qcas.co>
     */
    protected function configure()
    {
        $this->setName('link')
            ->setDescription('Links target paths and post composer paths to target dir.')
            ->addArgument('config', InputArgument::OPTIONAL, 'Yaml config file with build settings. If not defined, config.yaml will be tried.')
            ->addOption('test', 't', InputOption::VALUE_NONE, 'If set, no commands will be executed.')
            ->addOption('cp-command', 'c', InputOption::VALUE_REQUIRED, 'If set, overrides `cp` command.');
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

        //Prepare Shells
        $this->setShellOutput($output);

        $this->createMainLinks($output, $config);
        $this->createPostComposerLinks($output, $config);
        $this->cleanup($output, $config);
    }
}
