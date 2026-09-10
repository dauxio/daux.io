<?php
namespace Todaymade\Daux\Format\HTML;

use League\Plates\Exception\TemplateNotFound;
use League\Plates\Template\Name;
use League\Plates\Template\ResolveTemplatePath;

/**
 * Resolves a template by walking a list of directories in order, from the most
 * specific one (the theme) down to the templates shipped with Daux.
 *
 * This is what allows a theme to override only the templates it cares about,
 * the missing ones are taken from the directory below in the list.
 */
class TemplateResolver implements ResolveTemplatePath
{
    /**
     * @param string[] $directories ordered list of existing directories to search
     */
    public function __construct(private readonly array $directories) {}

    public function __invoke(Name $name): string
    {
        $searched = [];

        foreach ($this->directories as $directory) {
            $path = $directory . DIRECTORY_SEPARATOR . $name->getFile();

            if (is_file($path)) {
                return $path;
            }

            $searched[] = $path;
        }

        throw new TemplateNotFound(
            $name->getName(),
            $searched,
            'The template "' . $name->getName() . '" could not be found in any of these locations: ' . implode(', ', $searched)
        );
    }
}
