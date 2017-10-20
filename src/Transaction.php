<?php
namespace Account;

use \Exception;
use \PDOException;
use \PDO;

class Transaction 
{
    private $account = null;
    
    function __construct($account)
    {
        $this->account = $account;
    }

    public function listTransaction($db)
    {
        try {
            $account = $this->account;
            $accountID = isset($account['id']) && !empty($account['id']) ? $account['id'] : null;

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
                    
            $stmt = $db->prepare($SQL);
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
    public function deposit($db, $amount)
    {
        try {
            $account = $this->account;
            $accountID = isset($account['id']) && !empty($account['id']) ? $account['id'] : null;

            if (!$accountID) {
                throw new Exception('accountID missed.');
            }
            $SQL = "
                INSERT INTO account_transaction(`account_id`, `transaction_amount`, `transaction_date`, `transaction_type`)
                    VALUES(:accountID, :amount, NOW(), 1);
            ";
                    
            $stmt = $db->prepare($SQL);
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

    public function withdrawal($db, $amount)
    {
        try {
            $account = $this->account;
            $accountID = isset($account['id']) && !empty($account['id']) ? $account['id'] : null;

            if (!$accountID) {
                throw new Exception('accountID missed.');
            }
            if (!is_numeric($amount)) {
                throw new Exception('invalid amount.');
            }
            $SQL = "
                INSERT INTO account_transaction(`account_id`, `transaction_amount`, `transaction_date`, `transaction_type`)
                    VALUES(:accountID, :amount, NOW(), 2);
            ";
                    
            $amount = abs($amount) * -1;
            $stmt = $db->prepare($SQL);
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
