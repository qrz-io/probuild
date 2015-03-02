<?php
require __DIR__ . '/../vendor/autoload.php';

use Probuild\Command;
use \Probuild\Shell;
use Symfony\Component\Console\Application;

$application = new Application('Probuild', '@package_version@');

//Create Shell
$shell = new \Probuild\Shell();

//Create Managers (they should all share the same Shell object)
$directoryManager = new Shell\Directory();
$linkManager = new Shell\Link();
$composerManager = new Shell\Composer();
$gruntManager = new Shell\Grunt();

//Create and configure Make Command
$makeCommand = new Command\MakeCommand();
$makeCommand
    ->setDirectoryManager($directoryManager)
    ->setLinkManager($linkManager)
    ->setComposerManager($composerManager)
    ->setGruntManager($gruntManager);

//Create Update Command
$updateCommand = new Command\UpdateCommand();

$application->add($makeCommand);
$application->add($updateCommand);

return $application;