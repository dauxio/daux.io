<?php namespace Todaymade\Daux\Console;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Todaymade\Daux\Daux;

class DauxCommand extends SymfonyCommand
{
    protected function configure() 
    {
        $this
            ->addOption('configuration', 'c', InputOption::VALUE_REQUIRED, 'Configuration file')
            ->addOption('value', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Set different configuration values')
            ->addOption('source', 's', InputOption::VALUE_REQUIRED, 'Where to take the documentation from')
            ->addOption('processor', 'p', InputOption::VALUE_REQUIRED, 'Manipulations on the tree');

        // HTML Format only
        $this->addOption('themes', 't', InputOption::VALUE_REQUIRED, 'Set a different themes directory');
    }

    private function setValue(&$array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }
        $keys = explode('.', $key);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (! isset($array[$key]) || ! is_array($array[$key])) {
                $array[$key] = [];
            }
            $array = &$array[$key];
        }
        $array[array_shift($keys)] = $value;
        return $array;
    }

    private function applyConfiguration(array $options, Daux $daux)
    {
        $values = array_map(
            function ($value) {
                return array_map("trim", explode('=', $value));
            },
            $options
        );

        foreach ($values as $value) {
            $this->setValue($daux->options, $value[0], $value[1]);
        }
    }

    protected function prepareDaux(InputInterface $input)
    {
        $daux = new Daux(Daux::STATIC_MODE);

        // Set the format if requested
        if ($input->hasOption('format') && $input->getOption('format')) {
            $daux->getParams()->setFormat($input->getOption('format'));
        }

        // Set the source directory
        if ($input->getOption('source')) {
            $daux->getParams()->setDocumentationDirectory($input->getOption('source'));
        }

        if ($input->getOption('themes')) {
            $daux->getParams()->setThemesDirectory($input->getOption('themes'));
        }

        $daux->initializeConfiguration($input->getOption('configuration'));

        if ($input->hasOption('delete') && $input->getOption('delete')) {
            $daux->getParams()['confluence']['delete'] = true;
        }

        if ($input->hasOption('value')) {
            $this->applyConfiguration($input->getOption('value'), $daux);
        }

        return $daux;
    }
}
