<?php 
namespace Account;

use \Exception;
use \PDOException;
use \PDO;
use Account\DB;
use Helper\Helper;

class Account 
{
    private $db = null;
    private $email = null;

    function __construct($db, $email)
    {
        $helper = new Helper();
        //validate DB object
        if (!$helper->validateDBObj($db)) {
            throw new Exception('Invalid DB obj.');
        }

        //validate email address format
        if (!$helper->validateAccountEmail($email)) {
            throw new Exception('The Email Address is in an invalid format.');
        }

        $this->db = $db;
        $this->email = $email;
    }
    /**
    * Open account
    */
    public function open($firstName, $lastName) 
    {
        try {
            $helper = new Helper();

            //validate first name
            if (!$helper->validateAccountName($firstName)) {
                throw new Exception('First name is invalid or max length of character reached(30).');
            }

            //validate last name
            if (!$helper->validateAccountName($lastName)) {
                throw new Exception('Last name is invalid or max length of character reached(30).');
            }

            $SQL = "INSERT INTO account(`email`, `first_name`, `last_name`)
                        VALUES(:email, :first_name, :last_name);";
            $stmt = $this->db->prepare($SQL);
            $stmt->bindParam(':email', $this->email);
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
    public function close() 
    {
        try {
            
            //upate account status
            $SQL = "UPDATE account SET status = 0 WHERE status = 1 AND email = :email;";
            $stmt = $this->db->prepare($SQL);
            $stmt->bindParam(':email', $this->email);
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
    public function reopen() 
    {
        try {
            //upate account status
            $SQL = "UPDATE account SET status = 1 WHERE status = 0 AND email = :email;";
            $stmt = $this->db->prepare($SQL);
            $stmt->bindParam(':email', $this->email);
            $stmt->execute();

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
    public function delete() 
    {
        try {
            $SQL = "DELETE FROM account WHERE email = :email;";
            $stmt = $this->db->prepare($SQL);
            $stmt->bindParam(':email', $this->email);
            $stmt->execute();

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
    public function info() 
    {
        try {
            $SQL = "SELECT `id`, `email`, `first_name`, `last_name`, `status` 
                    FROM account
                    WHERE
                        email = :email;";
            $stmt = $this->db->prepare($SQL);
            $stmt->bindParam(':email', $this->email);
            $stmt->execute();

            if (!$stmt->rowCount()) {
                throw new Exception('No data found.');
            }
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            //rethrow exception
            throw $e;
        } catch (Exception $e) {
            //rethrow exception
            throw $e;
        }
    }

    // return account ID,
    // return null if account not found
    public function getAccountID()
    {

        $account = $this->info();
        $accountID = isset($account['id']) && !empty($account['id']) ? $account['id'] : null;
        return $accountID;
    }

    /*
    * return account balance
    */
    public function balance() 
    {
        try {
            $SQL = "SELECT
                        account.email,
                        SUM( acct.transaction_amount ) AS balance
                    FROM
                        account
                    JOIN account_transaction AS acct ON
                        acct.account_id = account.id
                    WHERE
                        account.email = :email;";
            $stmt = $this->db->prepare($SQL);
            $stmt->bindParam(':email', $this->email);
            $stmt->execute();

            if (!$stmt->rowCount()) {
                throw new Exception('No data found.');
            }
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            //rethrow exception
            throw $e;
        } catch (Exception $e) {
            //rethrow exception
            throw $e;
        }
    }
}