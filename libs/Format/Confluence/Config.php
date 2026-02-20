<?php namespace Todaymade\Daux\Format\Confluence;

use Todaymade\Daux\BaseConfig;

class Config extends BaseConfig
{
    public function shouldAutoDeleteOrphanedPages()
    {
        return $this->getValue('delete', false);
    }

    public function shouldPrintDiff()
    {
        return $this->getValue('print_diff', false);
    }

    public function getUpdateThreshold()
    {
        return $this->getValue('update_threshold', 2);
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

    public function setSpaceId($value)
    {
        $this->setValue('space_id', $value);
    }

    public function hasAncestorId()
    {
        return $this->hasValue('ancestor_id');
    }

    public function getAncestorId()
    {
        return $this->getValue('ancestor_id');
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

    public function createRootIfMissing()
    {
        return $this->getValue('create_root_if_missing', false);
    }

    public function getPreRenderMermaid(): bool
    {
        return $this->getValue('pre_render_mermaid', false);
    }

    public function getMermaidCliPath(): string
    {
        return $this->getValue('mermaid_cli_path', 'mmdc');
    }

    public function getMermaidImageFormat(): string
    {
        $format = $this->getValue('mermaid_image_format', 'svg');

        return in_array($format, ['svg', 'png'], true) ? $format : 'svg';
    }

    public function getMermaidKrokiUrl(): ?string
    {
        return $this->getValue('mermaid_kroki_url');
    }

    public function getMermaidImageWidth(): ?int
    {
        $width = $this->getValue('mermaid_image_width');

        return $width !== null ? (int) $width : null;
    }

    public function getMermaidImageHeight(): ?int
    {
        $height = $this->getValue('mermaid_image_height');

        return $height !== null ? (int) $height : null;
    }
}
