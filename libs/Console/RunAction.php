<?php namespace Todaymade\Daux\Console;

use Symfony\Component\Console\Output\OutputInterface;

trait RunAction
{
    protected function getLength($content) {
        return function_exists('mb_strlen') ? mb_strlen($content) : strlen($content);
    }

    protected function runAction($title, OutputInterface $output, $width, \Closure $closure)
    {
        $output->write($title);

        // 8 is the length of the label + 2 let it breathe
        $padding = $width - $this->getLength($title) - 10;
        try {
            $response = $closure(function ($content) use ($output, &$padding) {
                $padding -= $this->getLength($content);
                $output->write($content);
            });
        } catch (\Exception $e) {
            $output->writeln(str_pad(' ', $padding) . '[ <fg=red>FAIL</fg=red> ]');
            throw $e;
        }
        $output->writeln(str_pad(' ', $padding) . '[  <fg=green>OK</fg=green>  ]');

        return $response;
    }
}
