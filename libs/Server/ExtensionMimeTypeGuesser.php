<?php namespace Todaymade\Daux\Server;

use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesserInterface;

/**
 * Guesses the mime type using the file's extension
 */
class ExtensionMimeTypeGuesser implements MimeTypeGuesserInterface
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
}
