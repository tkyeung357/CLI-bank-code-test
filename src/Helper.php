<?php
namespace Helper;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;

class Helper
{
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

    /**
    * validate DB object is instace of PDO
    */
    public function validateDBObj($db)
    {
       return $db instanceof \PDO;
    }
}