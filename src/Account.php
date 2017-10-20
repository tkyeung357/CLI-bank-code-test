<?php 
namespace Account;

use \Exception;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Account\DB;

class Account 
{

    /**
    * Open account
    */
    public function open($db, $email, $firstName, $lastName) 
    {

        try {
            //validate email address format
            if (!$this->validateAccountEmail($email)) {
                throw new Exception('The Email Address is in an invalid format.');
            }
            
            //validate first name
            if (!$this->validateAccountName($firstName)) {
                throw new Exception('First name is invalid or max length of character reached(30).');
            }

            //validate last name
            if (!$this->validateAccountName($lastName)) {
                throw new Exception('Last name is invalid or max length of character reached(30).');
            }
            return true;
        } catch (Exception $e) {
            //rethrow exception
            throw $e;
        }
    }

    /**
    * Close account
    */
    public function close() 
    {

    }

    /*
    * return account info
    */
    public function info($db, $email) 
    {

    }

    /*
    * validate email address format
    */
    public function validateAccountEmail($email)
    {
        //intace email validator
        $emailConstraint = new EmailConstraint();
        $validator = Validation::createValidator();
        $emailValidateResult = $validator->validate(
            $email,
            $emailConstraint
        );

        //return result
        return count($emailValidateResult)? false : true;
    }

    /** 
     * validate account name by regex
     * - support AZ character 
     * - support max 30 character
    */
    public function validateAccountName($name)
    {
        $regexValidator = "/^[a-zA-Z'-]{1,30}$/";
        return preg_match($regexValidator, $name)? true : false;
    }
}