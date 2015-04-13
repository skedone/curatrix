<?php
namespace Curatrix;

use Curatrix\Storage\Storage;
use Curatrix\Supervisor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DescriptorHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class SupervisorCommand extends Command {

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('run')
            ->setDefinition(array(
                new InputOption('config', 'c', InputOption::VALUE_OPTIONAL, 'Configuration file'),
                new InputOption('debug', null, InputOption::VALUE_NONE, 'Run with debug flags active, overriding debug flags')
            ))
            ->setDescription('Run Curatrix in a nice dress.')
            ->setHelp(<<<EOF
The <info>run</info> command try to rule them all.
EOF
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configuration = [];

        $file = $input->getOption('config');

        try {
            $configuration = Yaml::parse(@file_get_contents(__DIR__ . '/../' . $file));
        } catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        if(empty($configuration)) {
            throw new \Exception('It seems that I can not read the configuration file.');
        }

        $debug = $input->getOption('debug');
        if($debug === true) {
            $configuration['debug'] = true;
        }

        $curatrix = Curatrix::run($configuration);

    }
}