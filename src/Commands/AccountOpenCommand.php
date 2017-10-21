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

class AccountOpenCommand extends Command 
{
    protected function configure() 
    {
        $this->setName('Account:Open')
                ->setDescription('Open Account command')
                ->addArgument('email', InputArgument::REQUIRED, 'email address')
                ->addArgument('first_name', InputArgument::REQUIRED, 'account first name')
                ->addArgument('last_name', InputArgument::REQUIRED, 'account last name');
    }
    protected function execute(InputInterface $input, OutputInterface $output) 
    {
        try {
            //get command parameter
            $email = $input->getArgument('email');
            $firstName = $input->getArgument('first_name');
            $lastName = $input->getArgument('last_name');

            //instance DB
            $db = new DB();
            $account = new Account($db, $email);

            //open account
            $stmt = $account->open($firstName, $lastName);

            $output->writeln("Success");
            return true;
        } catch (PDOException $e) {
            $output->writeln('Failed - ' . $e->getMessage());
            return false;
        } catch (Exception $e) {
            $output->writeln('Failed - ' . $e->getMessage());
            return false;
        }
    }
}