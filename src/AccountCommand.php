<?php
namespace Account;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class AccountCommand extends Command {
    protected function configure() {
        $this->setName('Account:Open')
                ->setDescription('Open Account command')
                ->addArgument('TestInput', InputArgument::REQUIRED, 'Pls enter input.')
                ->addArgument('TestInput2', InputArgument::REQUIRED, 'Pls enter input2.');
    }
    protected function execute(InputInterface $input, OutputInterface $output) {
        $input1 = $input->getArgument('TestInput');
        $input2 = $input->getArgument('TestInput2');
        $output->writeln("input: {$input1}");
        $output->writeln("input2: {$input2}");
    }
}