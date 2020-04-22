<?php namespace Todaymade\Daux\Tree;

use SplFileInfo;

abstract class Entry
{
    /** @var string */
    protected $title;

    /** @var string */
    protected $name;

    /** @var string */
    protected $uri;

    /** @var Directory */
    protected $parent;

    /** @var SplFileInfo */
    protected $info;

    /** @var string */
    protected $path;

    /**
     * @param string $uri
     * @param SplFileInfo $info
     */
    public function __construct(Directory $parent, $uri, SplFileInfo $info = null)
    {
        $this->setUri($uri);
        $this->setParent($parent);

        if ($info !== null) {
            $this->info = $info;
            $this->path = $info->getPathname();
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     */
    public function setUri($uri)
    {
        if ($this->parent) {
            $this->parent->removeChild($this);
        }

        $this->uri = $uri;

        if ($this->parent) {
            $this->parent->addChild($this);
        }
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return Directory
     */
    public function getParent(): ?Directory
    {
        return $this->parent;
    }

    /**
     * Return all parents starting with the root.
     *
     * @return Directory[]
     */
    public function getParents()
    {
        $parents = [];
        if ($this->parent && !$this->parent instanceof Root) {
            $parents = $this->parent->getParents();
            $parents[] = $this->parent;
        }

        return $parents;
    }

    protected function setParent(Directory $parent)
    {
        if ($this->parent) {
            $this->parent->removeChild($this);
        }

        $parent->addChild($this);
        $this->parent = $parent;
    }

    /**
     * @return string
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * Get the path to the file from the root of the documentation.
     */
    public function getRelativePath(): string
    {
        $root = $this;
        while ($root->getParent() != null) {
            $root = $root->getParent();
        }

        return substr($this->path, strlen($root->getPath()) + 1);
    }

    public function getFileinfo(): SplFileInfo
    {
        return $this->info;
    }

    public function getUrl(): string
    {
        $url = '';

        if ($this->getParent() && !$this->getParent() instanceof Root) {
            $url = $this->getParent()->getUrl() . '/' . $url;
        }

        $url .= $this->getUri();

        return $url;
    }

    public function dump()
    {
        return [
            'title' => $this->getTitle(),
            'type' => get_class($this),
            'name' => $this->getName(),
            'uri' => $this->getUri(),
            'url' => $this->getUrl(),
            'path' => $this->path,
        ];
    }

    public function isHotPath(Entry $node = null)
    {
        return $this->parent->isHotPath($node ?: $this);
    }
}
