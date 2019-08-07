<?php namespace Todaymade\Daux\Server;

use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesserInterface as FileMimeTypeGuesserInterface;
use Symfony\Component\Mime\MimeTypeGuesserInterface;

/**
 * Guesses the mime type using the file's extension
 */
class ExtensionMimeTypeGuesser implements FileMimeTypeGuesserInterface, MimeTypeGuesserInterface
{
    /**
     * {@inheritdoc}
     */
    public function guess($path)
    {
        $extension = pathinfo($path,PATHINFO_EXTENSION);

        if ($extension == "css") {
            return "text/css";
        }

        if ($extension == "js") {
            return "application/javascript";
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isGuesserSupported(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function guessMimeType(string $path): ?string
    {
        return $this->guess($path);
    }
}
