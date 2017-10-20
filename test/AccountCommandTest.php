<?php
namespace Tests;

use Account\AccountOpenCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

require_once './vendor/autoload.php';

class AccountCommandTest extends \PHPUnit_Framework_TestCase {
    //test success case
    public function testOpenAccountIsSuccess() {
        $app = new Application();
        $app->add(new AccountOpenCommand);

        $command = $app->find('Account:Open');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'email' => 'timmy.timki@gmail.com',
            'first_name' => 'Hello',
            'last_name' => 'World',
        ));

        $this->assertRegExp('/Success/', $commandTester->getDisplay());
    }

    //test failed case
    public function testOpenAccountIsFailed() {
        $app = new Application();
        $app->add(new AccountOpenCommand);

        $command = $app->find('Account:Open');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'email' => 'Failed',
            'first_name' => 'Hello',
            'last_name' => 'World',
        ));

        $this->assertRegExp('/Failed/', $commandTester->getDisplay());
    }
}