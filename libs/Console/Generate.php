<?php namespace Todaymade\Daux\Console;

use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Todaymade\Daux\Daux;

class Generate extends DauxCommand
{
    protected function configure()
    {
        parent::configure();

        $description = 'Destination folder, relative to the working directory';

        $this
            ->setName('generate')
            ->setDescription('Generate documentation')

            ->addOption('format', 'f', InputOption::VALUE_REQUIRED, 'Output format, html or confluence', 'html')

            // Confluence format only
            ->addOption('delete', null, InputOption::VALUE_NONE, 'Delete pages not linked to a documentation page (confluence)')

            ->addOption('destination', 'd', InputOption::VALUE_REQUIRED, $description, 'static')
            ->addOption('search', null, InputOption::VALUE_NONE, 'Generate full text search');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // When used as a default command,
        // Symfony doesn't read the default parameters.
        // This will parse the parameters
        if ($input instanceof ArrayInput) {
            $argv = $_SERVER['argv'];
            $argv[0] = $this->getName();
            array_unshift($argv, 'binary_name');

            $input = new ArgvInput($argv, $this->getDefinition());
        }

        $daux = $this->prepareDaux($input);

        $width = $this->getApplication()->getTerminalDimensions()[0];

        // Instiantiate the processor if one is defined
        $this->prepareProcessor($daux, $input, $output, $width);

        // Generate the tree
        $daux->generateTree();

        // Generate the documentation
        $daux->getGenerator()->generateAll($input, $output, $width);
    }

    protected function prepareProcessor(Daux $daux, InputInterface $input, OutputInterface $output, $width)
    {
        if ($input->getOption('processor')) {
            $daux->getParams()['processor'] = $input->getOption('processor');
        }

        $class = $daux->getProcessorClass();
        if (!empty($class)) {
            $daux->setProcessor(new $class($daux, $output, $width));
        }
    }
}
