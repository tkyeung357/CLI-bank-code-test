<?php
namespace Tests;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Account\AccountOpenCommand;
use Account\AccountCloseCommand;
use Account\AccountDepositCommand;
use Account\AccountWithdrawalCommand;
use Account\AccountListTransactionCommand;
use Account\AccountBalanceCommand;
use Account\Account;
use Account\DB;

require_once './vendor/autoload.php';

class AccountCommandTest extends \PHPUnit_Framework_TestCase {
    /*
    * Test Preparation: Delete test account if exists
    *   Test Account: timmy.timki@gmail.com
    *
    * Use Case: open account 
    *   Test Case:
    *   - Success case, open a test account 
    *   - Failed case, try to open an account with existing email
    *   - Failed case, provide a invalid email.
    *
    * Use Case: close account 
    *   Test Case:
    *   - Success case, update account status to 0 (disabled)
    *   - Failed case, try to update a disabled account. no record udpated
    *
    * Test Preparation: Reopen closed account - call account reopen function to update account status to 1 (enabled)
    *
    * Use Case: Deposit Account
    *   Test Case:
    *   - Success case, deposit 100 to test account
    *   - Failed case, deposit "test amount" to test account
    *
    * Use Case: Withdrawal Account
    *   Test Case:
    *   - Success case, withdraw 100 to test account
    *   - Failed case, deposit "test amount" to test account
    *
    * Use Case: List Transaction
    *   Test Case:
    *   - Success case
    *
    * Use Case: Balance 
    *   Test Case:
    *   - Success case
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
        $app->add(new AccountDepositCommand);
        $app->add(new AccountWithdrawalCommand);
        $app->add(new AccountListTransactionCommand);
        $app->add(new AccountBalanceCommand);

        //Use Case: Open account
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

        //reopen closed account
        $account->reopen($db, $testEmail);


        //Use Case: Deposit Account
        $command = $app->find('Account:Deposit');
        $commandTester = new CommandTester($command);

        //success test case 
        $commandTester->execute(array(
            'command' => $command->getName(),
            'email' => $testEmail,
            'amount' => 100
        ));

        $this->assertRegExp('/Success/', $commandTester->getDisplay());

        //failed test case 
        $commandTester->execute(array(
            'command' => $command->getName(),
            'email' => $testEmail,
            'amount' => 'test amount'
        ));

        $this->assertRegExp('/Failed/', $commandTester->getDisplay());


        //Use Case: Withdrawal Account
        $command = $app->find('Account:Withdrawal');
        $commandTester = new CommandTester($command);

        //success test case 
        $commandTester->execute(array(
            'command' => $command->getName(),
            'email' => $testEmail,
            'amount' => 100
        ));

        $this->assertRegExp('/Success/', $commandTester->getDisplay());

        //failed test case 
        $commandTester->execute(array(
            'command' => $command->getName(),
            'email' => $testEmail,
            'amount' => 'test amount'
        ));

        $this->assertRegExp('/Failed/', $commandTester->getDisplay());

        //Use Case: List Transaction 
        $command = $app->find('Account:ListTransaction');
        $commandTester = new CommandTester($command);

        //success test case 
        $commandTester->execute(array(
            'command' => $command->getName(),
            'email' => $testEmail
        ));

        $this->assertRegExp('/Success/', $commandTester->getDisplay());

        //Use Case: Balance 
        $command = $app->find('Account:Balance');
        $commandTester = new CommandTester($command);

        //success test case 
        $commandTester->execute(array(
            'command' => $command->getName(),
            'email' => $testEmail
        ));

        $this->assertRegExp('/Success/', $commandTester->getDisplay());
    }

}