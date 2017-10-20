<?php
namespace Tests;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Account\AccountOpenCommand;
use Account\AccountCloseCommand;
use Account\Account;
use Account\DB;

require_once './vendor/autoload.php';

class AccountCommandTest extends \PHPUnit_Framework_TestCase {
    /*
    * test open account 
    * Test Case:
    * - Success case, open a new account
    * - Failed case, try to open an account with existing email
    * - Failed case, provide a invalid email.
    *
    * test close account 
    */
    public function testOpenAccount() {
        //delete the test email from system for testing
        $db = new DB();
        $account = new Account();
        $testEmail = 'timmy.timki@gmail.com';
        $account->delete($db, $testEmail);

        $app = new Application();
        $app->add(new AccountOpenCommand);
        $app->add(new AccountCloseCommand);

        $command = $app->find('Account:Open');
        $commandTester = new CommandTester($command);

        //success test case, create a new account
        $commandTester->execute(
            array(
                'command' => $command->getName(),
                'email' => $testEmail,
                'first_name' => 'Tim',
                'last_name' => 'Yeung',
            )
        );

        $this->assertRegExp('/Success/', $commandTester->getDisplay());

        //fail test case, email already exists
        $commandTester->execute(
            array(
                'command' => $command->getName(),
                'email' => $testEmail,
                'first_name' => 'Tim',
                'last_name' => 'Yeung',
            )
        );

        $this->assertRegExp('/Failed/', $commandTester->getDisplay());

        //fail test case, provide a invalid email
        $commandTester->execute(array(
            'command' => $command->getName(),
            'email' => 'Failed',
            'first_name' => 'Hello',
            'last_name' => 'World',
        ));

        $this->assertRegExp('/Failed/', $commandTester->getDisplay());

        //Use Case: Close Account
        $command = $app->find('Account:Close');
        $commandTester = new CommandTester($command);

        //success test case 
        $commandTester->execute(array(
            'command' => $command->getName(),
            'email' => $testEmail
        ));

        $this->assertRegExp('/Success/', $commandTester->getDisplay());

        //fail test case, close a disabled account
        $commandTester->execute(array(
            'command' => $command->getName(),
            'email' => $testEmail
        ));

        $this->assertRegExp('/Failed/', $commandTester->getDisplay());
    }

}