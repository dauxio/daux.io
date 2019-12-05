<?php namespace Todaymade\Daux\Format\Base;

use Todaymade\Daux\Config;
use Todaymade\Daux\Tree\Entry;

interface LiveGenerator extends Generator
{
    /**
     * @param Entry $node
     * @param Config $config
     * @return \Todaymade\Daux\Format\Base\Page
     */
    public function generateOne(Entry $node, Config $config);
}
