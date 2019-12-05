<?php namespace Todaymade\Daux\Format\Confluence;

use Todaymade\Daux\BaseConfig;

class Config extends BaseConfig
{
    public function shouldAutoDeleteOrphanedPages() {
        if (array_key_exists('delete', $this)) {
            return $this['delete'];
        }

        return false;
    }

    public function getUpdateThreshold() {
        return array_key_exists('update_threshold', $this) ? $this['update_threshold'] : 2;
    }

    public function getPrefix() {
        return $this['prefix'];
    }

    public function getBaseUrl() {
        return $this['base_url'];
    }

    public function getUser() {
        return $this['user'];
    }

    public function getPassword() {
        return $this['pass'];
    }

    public function getSpaceId() {
        return $this['space_id'];
    }

    public function hasAncestorId() {
        return array_key_exists('ancestor_id', $this);
    }

    public function getAncestorId() {
        return $this['ancestor_id'];
    }

    public function setAncestorId($value) {
        $this['ancestor_id'] = $value;
    }

    public function hasRootId() {
        return array_key_exists('root_id', $this);
    }

    public function getRootId() {
        return $this['root_id'];
    }

    public function hasHeader() {
        return array_key_exists('header', $this);
    }

    public function getHeader() {
        return $this['header'];
    }
}
