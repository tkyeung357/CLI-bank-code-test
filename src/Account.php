<?php 
namespace Account;

use \Exception;
use \PDOException;
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

            //validate DB object
            if (!$this->validateDBObj($db)) {
                throw new Exception('Invalid DB obj.');
            }

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

            $SQL = "INSERT INTO account(`email`, `first_name`, `last_name`)
                        VALUES(:email, :first_name, :last_name);";
            $stmt = $db->prepare($SQL);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':first_name', $firstName);
            $stmt->bindParam(':last_name', $lastName);
            $stmt->execute();

            if (!$stmt->rowCount()) {
                throw new Exception('No data inserted, maybe already exists.');
            }
            return true;
        } catch (PDOException $e) {
            //rethrow exception
            throw $e;
        } catch (Exception $e) {
            //rethrow exception
            throw $e;
        }
    }

    /**
    * Close account, disable an enabled account
    */
    public function close($db, $email) 
    {
        try {
            //validate DB object
            if (!$this->validateDBObj($db)) {
                throw new Exception('Invalid DB obj.');
            }

            //validate email address format
            if (!$this->validateAccountEmail($email)) {
                throw new Exception('The Email Address is in an invalid format.');
            }
            
            //upate account status
            $SQL = "UPDATE account SET status = 0 WHERE status = 1 AND email = :email;";
            $stmt = $db->prepare($SQL);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if (!$stmt->rowCount()) {
                throw new Exception('The account status has not been updated');
            }
            return true;
        } catch (PDOException $e) {
            //rethrow exception
            throw $e;
        } catch (Exception $e) {
            //rethrow exception
            throw $e;
        }
    }

    /**
    * Reopen account, enable an disabled account
    */
    public function reopen($db, $email) 
    {
        try {
            //validate DB object
            if (!$this->validateDBObj($db)) {
                throw new Exception('Invalid DB obj.');
            }

            //validate email address format
            if (!$this->validateAccountEmail($email)) {
                throw new Exception('The Email Address is in an invalid format.');
            }
            
            //upate account status
            $SQL = "UPDATE account SET status = 1 WHERE status = 0 AND email = :email;";
            $stmt = $db->prepare($SQL);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if (!$stmt->rowCount()) {
                throw new Exception('The account status has not been updated');
            }
            return true;
        } catch (PDOException $e) {
            //rethrow exception
            throw $e;
        } catch (Exception $e) {
            //rethrow exception
            throw $e;
        }
    }
    /**
    * Delete account, delete account from system.
    */
    public function delete($db, $email) 
    {
        try {
            //validate DB object
            if (!$this->validateDBObj($db)) {
                throw new Exception('Invalid DB obj.');
            }

            //validate email address format
            if (!$this->validateAccountEmail($email)) {
                throw new Exception('The Email Address is in an invalid format.');
            }
            
            $SQL = "DELETE FROM account WHERE email = :email;";
            $stmt = $db->prepare($SQL);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if (!$stmt->rowCount()) {
                throw new Exception('No data deleted.');
            }
            return true;
        } catch (PDOException $e) {
            //rethrow exception
            throw $e;
        } catch (Exception $e) {
            //rethrow exception
            throw $e;
        }
    }

    /*
    * return account info
    */
    public function info($db, $email) 
    {
        try {
            //validate DB object
            if (!$this->validateDBObj($db)) {
                throw new Exception('Invalid DB obj.');
            }

            //validate email address format
            if (!$this->validateAccountEmail($email)) {
                throw new Exception('The Email Address is in an invalid format.');
            }
            
            $SQL = "SELECT id, email, first_name, last_name, status 
                    FROM account
                    WHERE
                        email = :email;";
            $stmt = $db->prepare($SQL);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if (!$stmt->rowCount()) {
                throw new Exception('No data found.');
            }
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            //rethrow exception
            throw $e;
        } catch (Exception $e) {
            //rethrow exception
            throw $e;
        }
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

    /**
    * validate DB object is instace of PDO
    */
    public function validateDBObj($db)
    {
       return $db instanceof \PDO;
    }
}