<?php namespace Todaymade\Daux\Format\Confluence;

use Todaymade\Daux\BaseConfig;

class Config extends BaseConfig
{
    public function shouldAutoDeleteOrphanedPages()
    {
        if ($this->hasValue('delete')) {
            return $this->getValue('delete');
        }

        return false;
    }

    public function getUpdateThreshold()
    {
        return $this->hasValue('update_threshold') ? $this->getValue('update_threshold') : 2;
    }

    public function getPrefix()
    {
        return $this->getValue('prefix');
    }

    public function getBaseUrl()
    {
        return $this->getValue('base_url');
    }

    public function getUser()
    {
        return $this->getValue('user');
    }

    public function getPassword()
    {
        return $this->getValue('pass');
    }

    public function getSpaceId()
    {
        return $this->getValue('space_id');
    }

    public function hasAncestorId()
    {
        return $this->hasValue('ancestor_id');
    }

    public function getAncestorId()
    {
        return $this->getValue('ancestor_id');
    }

    public function setAncestorId($value)
    {
        $this->setValue('ancestor_id', $value);
    }

    public function hasRootId()
    {
        return $this->hasValue('root_id');
    }

    public function getRootId()
    {
        return $this->getValue('root_id');
    }

    public function hasHeader()
    {
        return $this->hasValue('header');
    }

    public function getHeader()
    {
        return $this->getValue('header');
    }
}
