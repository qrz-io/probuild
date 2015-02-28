<?php

namespace Probuild\Command;

use Herrera\Phar\Update\Manager;
use Herrera\Phar\Update\Manifest;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCommand extends Command
{

    const MANIFEST_FILE = 'http://cristian-quiroz.github.io/probuild/manifest.json';

    /**
     * @return void
     * @author Cristian Quiroz <cris@qcas.co>
     */
    protected function configure()
    {
        $this->setName('update')
            ->setDescription('Updates probuild.phar to the latest version.')
        ;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     * @author Cristian Quiroz <cris@qcas.co>
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = new Manager(Manifest::loadFile(self::MANIFEST_FILE));
        $manager->update($this->getApplication()->getVersion(), true);
    }
}
