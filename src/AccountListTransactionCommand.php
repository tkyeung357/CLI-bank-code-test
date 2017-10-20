<?php
namespace Account;

use \Exception;
use \PDOException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\Table;
use Account\DB;
use Account\Account;
use Account\Transaction;

class AccountListTransactionCommand extends Command 
{
    protected function configure() 
    {
        $this->setName('Account:ListTransaction')
                ->setDescription('Deposit Account command')
                ->addArgument('email', InputArgument::REQUIRED, 'email address');
    }
    protected function execute(InputInterface $input, OutputInterface $output) 
    {
        try {
            //get command parameter
            $email = $input->getArgument('email');

            //instance DB
            $db = new DB();
            $account = new Account();

            //open account
            $info = $account->info($db, $email);
            $deposit = new Transaction($info);
            $transactionList = $deposit->listTransaction($db);   

            //display transaction list table
            $table = new Table($output); 
            $table->setHeaders(array('Transaction ID', 'Amount', 'Date', 'Type'))
                    ->setRows($transactionList);
            $table->render();
            
            $output->writeln("Success");
        } catch (PDOException $e) {
            $output->writeln('Failed - ' . $e->getMessage());
            return false;
        } catch (Exception $e) {
            $output->writeln('Failed - ' . $e->getMessage());
            return false;
        }
    }
}