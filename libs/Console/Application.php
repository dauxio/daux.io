<?php namespace Todaymade\Daux\Console;

use Symfony\Component\Console\Application as SymfonyApplication;

class Application extends SymfonyApplication
{
    public function __construct($name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        parent::__construct($name, $version);

        $this->add(new Generate());
        $this->add(new Serve());
        $this->add(new ClearCache());

        $app_name = 'daux/daux.io';

        $up = '..' . DIRECTORY_SEPARATOR;
        $composer = __DIR__ . DIRECTORY_SEPARATOR . $up . $up . $up . $up . $up . 'composer.lock';
        $version = 'unknown';

        if (file_exists($composer)) {
            $app = json_decode(file_get_contents($composer));
            $packages = $app->packages;

            foreach ($packages as $package) {
                if ($package->name == $app_name) {
                    $version = $package->version;
                }
            }
        }

        $this->setVersion($version);
        $this->setName($app_name);
    }
}
