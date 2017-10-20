<?php
namespace Tests;

use Account\Account;
use Account\DB;
use PHPUnit\Framework\TestCase;

require_once './vendor/autoload.php';

class AccountTest extends TestCase {

    /*
    * Validator Test: DB object
    *   - success case test, pass PDO object
    *   - failed case test, pass null
    */
    public function testDBObj() 
    {
        $account = new Account();
        $db = new DB();
        //success case test, pass PDO object
        $this->assertTrue($account->validateDBObj($db));
        //failed case test, pass null
        $this->assertFalse($account->validateDBObj(null));
    }

    /*
    * Validator Test: email address
    *   - success case test
    *   - fail case test: invalid email format. remove @
    */
    public function testEmailValidator() 
    {
        $account = new Account();
        //success case test
        $this->assertTrue($account->validateAccountEmail('timmy.timki@gmail.com'));
        //fail case test: invalid email format. remove @
        $this->assertFalse($account->validateAccountEmail('timmy.timki-at-gmail.com'));
    }

    /* 
    * Validator Test: user name
    *   - success case test
    *   - success case test: max 30 character. 26(a-z)+4(aabb)
    *   - fail case test: invalid name format
    *   - fail case test: max character reached. 26(a-z)+6(aabbcc)
    */
    public function testNameValidator() 
    {
        $account = new Account();
        //success case test
        $this->assertTrue($account->validateAccountName('Tim'));
        //success case test: max 30 character. 26(a-z)+4(aabb)
        $this->assertTrue($account->validateAccountName('abcdefghijklmnopqrstuvwxyzaabb'));
        //fail case test: invalid name format
        $this->assertFalse($account->validateAccountName('timmy.timki'));
        //fail case test: max character reached. 26(a-z)+6(aabbcc)
        $this->assertFalse($account->validateAccountName('abcdefghijklmnopqrstuvwxyzaabbcc'));
    }
}