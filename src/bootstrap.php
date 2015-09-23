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
$copyShell = new Shell\Copy();
$composerShell = new Shell\Composer();
$gruntShell = new Shell\Grunt();
$standardShell = new Shell\Standard();

//Create and configure Make Command
$makeCommand = new Command\MakeCommand();
$makeCommand
    ->setDirectoryShell($directoryShell)
    ->setCopyShell($copyShell)
    ->setComposerShell($composerShell)
    ->setGruntShell($gruntShell);

//Create and configure LinkCommand
$linkCommand = new Command\LinkCommand();
$linkCommand
    ->setDirectoryShell($directoryShell)
    ->setCopyShell($copyShell);

//Create and configure ComposerCommand
$composerCommand = new Command\ComposerCommand();
$composerCommand
    ->setDirectoryShell($directoryShell)
    ->setComposerShell($composerShell)
    ->setCopyShell($copyShell);

//Create and configure GruntCommand
$gruntCommand = new Command\GruntCommand();
$gruntCommand
    ->setDirectoryShell($directoryShell)
    ->setGruntShell($gruntShell);

$runCommand = new Command\RunCommand();
$runCommand
    ->setStandardShell($standardShell);

//Create Update Command
$updateCommand = new Command\UpdateCommand();

$application->add($makeCommand);
$application->add($updateCommand);
$application->add($linkCommand);
$application->add($composerCommand);
$application->add($gruntCommand);
$application->add($runCommand);

return $application;
