<?php
require __DIR__ . '/../vendor/autoload.php';

use Probuild\Command;
use \Probuild\Shell;
use Symfony\Component\Console\Application;

$application = new Application('Probuild', '@package_version@');

//Create Shell
$shell = new \Probuild\Shell();

//Create Shells (they should all share the same Shell object)
$directoryShell = new Shell\Directory();
$linkShell = new Shell\Link();
$composerShell = new Shell\Composer();
$gruntShell = new Shell\Grunt();

//Create and configure Make Command
$makeCommand = new Command\MakeCommand();
$makeCommand
    ->setDirectoryShell($directoryShell)
    ->setLinkShell($linkShell)
    ->setComposerShell($composerShell)
    ->setGruntShell($gruntShell);

//Create Update Command
$updateCommand = new Command\UpdateCommand();

$application->add($makeCommand);
$application->add($updateCommand);

return $application;