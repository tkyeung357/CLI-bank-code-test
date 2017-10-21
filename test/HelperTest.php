<?php
namespace Tests;

use Helper\Helper;
use Account\DB;
use PHPUnit\Framework\TestCase;

require_once './vendor/autoload.php';

class HelperTest extends TestCase {

    /*
    * Validator Test: DB object
    *   - success case test, pass PDO object
    *   - failed case test, pass null
    */
    public function testDBObj() 
    {
        $db = new DB();
        $helper = new Helper();
        //success case test, pass PDO object
        $this->assertTrue($helper->validateDBObj($db));
        //failed case test, pass null
        $this->assertFalse($helper->validateDBObj(null));
    }

    /*
    * Validator Test: email address
    *   - success case test
    *   - fail case test: invalid email format. remove @
    */
    public function testEmailValidator() 
    {
        $helper = new Helper();
        //success case test
        $this->assertTrue($helper->validateAccountEmail('timmy.timki@gmail.com'));
        //fail case test: invalid email format. remove @
        $this->assertFalse($helper->validateAccountEmail('timmy.timki-at-gmail.com'));
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
        $helper = new Helper();
        //success case test
        $this->assertTrue($helper->validateAccountName('Tim'));
        //success case test: max 30 character. 26(a-z)+4(aabb)
        $this->assertTrue($helper->validateAccountName('abcdefghijklmnopqrstuvwxyzaabb'));
        //fail case test: invalid name format
        $this->assertFalse($helper->validateAccountName('timmy.timki'));
        //fail case test: max character reached. 26(a-z)+6(aabbcc)
        $this->assertFalse($helper->validateAccountName('abcdefghijklmnopqrstuvwxyzaabbcc'));
    }
}