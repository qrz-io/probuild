<?php
require __DIR__ . '/../vendor/autoload.php';

use Probuild\Command;
use Probuild\Manager;
use Symfony\Component\Console\Application;


$application = new Application('Probuild', '@package_version@');

//Create Shell
$shell = new \Probuild\Shell();

//Create Managers (they should all share the same Shell object)
$directoryManager = new Manager\Directory();
$linkManager = new Manager\Link();
$composerManager = new Manager\Composer();
$gruntManager = new Manager\Grunt();
$directoryManager->setShell($shell);
$linkManager->setShell($shell);
$composerManager->setShell($shell);
$gruntManager->setShell($shell);

//Create and configure Make Command
$makeCommand = new Command\MakeCommand();
$makeCommand
    ->setShell($shell)
    ->setDirectoryManager($directoryManager)
    ->setLinkManager($linkManager)
    ->setComposerManager($composerManager)
    ->setGruntManager($gruntManager);

//Create Update Command
$updateCommand = new Command\UpdateCommand();

$application->add($makeCommand);
$application->add($updateCommand);

return $application;