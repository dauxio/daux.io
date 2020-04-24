<?php namespace Todaymade\Daux\Format\HTML;

use Todaymade\Daux\BaseConfig;

class Config extends BaseConfig
{
    private function prepareGithubUrl($url)
    {
        $url = str_replace('http://', 'https://', $url);

        return [
            'name' => 'GitHub',
            'basepath' => (strpos($url, 'https://github.com/') === 0 ? '' : 'https://github.com/') . trim($url, '/'),
        ];
    }

    public function getEditOn()
    {
        if ($this->hasValue('edit_on')) {
            $edit_on = $this->getValue('edit_on');
            if (is_string($edit_on)) {
                return $this->prepareGithubUrl($edit_on);
            }
            $edit_on['basepath'] = rtrim($edit_on['basepath'], '/');

            return $edit_on;
        }

        if ($this->hasValue('edit_on_github')) {
            return $this->prepareGithubUrl($this->getValue('edit_on_github'));
        }

        return null;
    }

    public function hasSearch()
    {
        return $this->hasValue('search') && $this->getValue('search');
    }

    public function showDateModified()
    {
        return $this->hasValue('date_modified') && $this->getValue('date_modified');
    }

    public function showPreviousNextLinks()
    {
        if ($this->hasValue('jump_buttons')) {
            return $this->getValue('jump_buttons');
        }

        return true;
    }

    public function showCodeToggle()
    {
        if ($this->hasValue('toggle_code')) {
            return $this->getValue('toggle_code');
        }

        return true;
    }

    public function hasAutomaticTableOfContents(): bool
    {
        return $this->hasValue('auto_toc') && $this->getValue('auto_toc');
    }

    public function hasGoogleAnalytics()
    {
        return $this->hasValue('google_analytics') && $this->getValue('google_analytics');
    }

    public function getGoogleAnalyticsId()
    {
        return $this->getValue('google_analytics');
    }

    public function hasPlausibleAnalyticsDomain()
    {
        return $this->hasValue('plausible_domain') && $this->getValue('plausible_domain');
    }

    public function getPlausibleAnalyticsDomain()
    {
        return $this->getValue('plausible_domain');
    }

    public function hasPiwikAnalytics()
    {
        return $this->getValue('piwik_analytics') && $this->hasValue('piwik_analytics_id');
    }

    public function getPiwikAnalyticsId()
    {
        return $this->getValue('piwik_analytics_id');
    }

    public function getPiwikAnalyticsUrl()
    {
        return $this->getValue('piwik_analytics');
    }

    public function hasPoweredBy()
    {
        return $this->hasValue('powered_by') && !empty($this->getValue('powered_by'));
    }

    public function getPoweredBy()
    {
        return $this->getValue('powered_by');
    }

    public function hasTwitterHandles()
    {
        return $this->hasValue('twitter') && !empty($this->getValue('twitter'));
    }

    public function getTwitterHandles()
    {
        return $this->getValue('twitter');
    }

    public function hasLinks()
    {
        return $this->hasValue('links') && !empty($this->getValue('links'));
    }

    public function getLinks()
    {
        return $this->getValue('links');
    }

    public function hasRepository()
    {
        return $this->hasValue('repo') && !empty($this->getValue('repo'));
    }

    public function getRepository()
    {
        return $this->getValue('repo');
    }

    public function hasButtons()
    {
        return $this->hasValue('buttons') && !empty($this->getValue('buttons'));
    }

    public function getButtons()
    {
        return $this->getValue('buttons');
    }

    public function hasLandingPage()
    {
        if ($this->hasValue('auto_landing')) {
            return $this->getValue('auto_landing');
        }

        return true;
    }

    public function hasBreadcrumbs()
    {
        if ($this->hasValue('breadcrumbs')) {
            return $this->getValue('breadcrumbs');
        }

        return true;
    }

    public function getBreadcrumbsSeparator()
    {
        return $this->getValue('breadcrumb_separator');
    }

    public function getTheme()
    {
        return $this->getValue('theme');
    }

    public function hasThemeVariant()
    {
        return $this->hasValue('theme-variant') && !empty($this->getValue('theme-variant'));
    }

    public function getThemeVariant()
    {
        return $this->getValue('theme-variant');
    }
}
