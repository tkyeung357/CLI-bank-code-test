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
use Account\DB;
use Account\Account;
use Account\Transaction;

class AccountWithdrawalCommand extends Command 
{
    protected function configure() 
    {
        $this->setName('Account:Withdrawal')
                ->setDescription('Withdrawal Account command')
                ->addArgument('email', InputArgument::REQUIRED, 'email address')
                ->addArgument('amount', InputArgument::REQUIRED, 'transation amount');
    }
    protected function execute(InputInterface $input, OutputInterface $output) 
    {
        try {
            //get command parameter
            $email = $input->getArgument('email');
            $amount = $input->getArgument('amount');

            //instance DB
            $db = new DB();
            $account = new Account();

            //open account
            $info = $account->info($db, $email);
            $deposit = new Transaction($info);
            $deposit->withdrawal($db, $amount);   

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