<?php
namespace Tests;

use Account\Account;
use PHPUnit\Framework\TestCase;

require_once './vendor/autoload.php';

class AccountTest extends TestCase {
    //test success case
    public function testEmailValidator() {
        $account = new Account();
        //fail case test: invalid email format. remove @
        $this->assertFalse($account->validateAccountEmail('timmy.timki-at-gmail.com'));
        //success case test
        $this->assertTrue($account->validateAccountEmail('timmy.timki@gmail.com'));
    }

    public function testNameValidator() {
        $account = new Account();
        //fail case test: invalid name format
        $this->assertFalse($account->validateAccountName('timmy.timki'));
        //fail case test: max character reached. 26(a-z)+6(aabbcc)
        $this->assertFalse($account->validateAccountName('abcdefghijklmnopqrstuvwxyzaabbcc'));
        //success case test: max 30 character. 26(a-z)+4(aabb)
        $this->assertTrue($account->validateAccountName('abcdefghijklmnopqrstuvwxyzaabb'));
        //success case test
        $this->assertTrue($account->validateAccountName('Tim'));
    }
}