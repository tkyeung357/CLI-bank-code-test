<?php
namespace Account\Commands;

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

class AccountCloseCommand extends Command 
{
    protected function configure() 
    {
        $this->setName('Account:Close')
                ->setDescription('Close Account command')
                ->addArgument('email', InputArgument::REQUIRED, 'email address');
    }
    protected function execute(InputInterface $input, OutputInterface $output) 
    {
        try {
            //get command parameter
            $email = $input->getArgument('email');

            //instance DB
            $db = new DB();
            $account = new Account($db, $email);

            //open account
            $info = $account->close();
                
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