<?php
namespace Account;

use \Exception;
use \PDOException;
use \PDO;
use Helper\Helper;

class Transaction 
{
    private $account = null;
    private $db = null;
    
    function __construct($db, $account)
    {
        $helper = new Helper();
        //validate DB object
        if (!$helper->validateDBObj($db)) {
            throw new Exception('Invalid DB obj.');
        }
        $this->account = $account;
        $this->db = $db;
    }

    public function listTransaction()
    {
        try {
            $accountID = $this->account->getAccountID();

            if (!$accountID) {
                throw new Exception('accountID missed.');
            }
            $SQL = "
                SELECT
                    acct.transaction_id,
                    acct.transaction_amount,
                    acct.transaction_date,
                    tt.name AS transaction_type
                FROM
                    account_transaction AS acct 
                JOIN transaction_type AS tt ON
                    tt.id = acct.transaction_type
                WHERE
                    acct.account_id = :accountID;
            ";
                    
            $stmt = $this->db->prepare($SQL);
            $stmt->bindParam(':accountID', $accountID);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            //rethrow exception
            throw $e;
        } catch (Exception $e) {
            //rethrow exception
            throw $e;
        }
    }
    public function deposit($amount)
    {
        try {
            $account = $this->account->info();
            $accountID = isset($account['id']) && !empty($account['id']) ? $account['id'] : null;
            $status = isset($account['status']) && !empty($account['status']) ? true : false;

            if (!$status) {
                //account closed
                throw new Exception ('Account Closed.');
            }

            if (!$accountID) {
                throw new Exception('accountID missed.');
            }

            $SQL = "
                INSERT INTO account_transaction(`account_id`, `transaction_amount`, `transaction_date`, `transaction_type`)
                    VALUES(:accountID, :amount, NOW(), 1);
            ";
                    
            $stmt = $this->db->prepare($SQL);
            $stmt->bindParam(':accountID', $accountID);
            $stmt->bindParam(':amount', $amount);
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

    public function withdrawal($amount)
    {
        try {
            $account = $this->account->info();
            $accountID = isset($account['id']) && !empty($account['id']) ? $account['id'] : null;
            $status = isset($account['status']) && !empty($account['status']) ? true : false;

            if (!$status) {
                //account closed
                throw new Exception ('Account Closed.');
            }

            if (!$accountID) {
                throw new Exception('accountID missed.');
            }

            if (!is_numeric($amount)) {
                throw new Exception('invalid amount.');
            }

            $currentBalance = $this->account->balance();
            if ($currentBalance['balance'] < $amount) {
                //no enough money and throw exception
                throw new Exception ('Not enough money');
            }

            $SQL = "
                INSERT INTO account_transaction(`account_id`, `transaction_amount`, `transaction_date`, `transaction_type`)
                    VALUES(:accountID, :amount, NOW(), 2);
            ";
                    
            $amount = abs($amount) * -1;
            $stmt = $this->db->prepare($SQL);
            $stmt->bindParam(':accountID', $accountID);
            $stmt->bindParam(':amount', $amount);
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
}
