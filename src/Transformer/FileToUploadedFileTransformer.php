<?php

namespace App\Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileToUploadedFileTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        // transforme l'objet UploadedFile en une chaîne de caractères
        if ($value instanceof UploadedFile) {
            return $value->getPathname();
        }

        return $value;
    }

    public function reverseTransform($value)
    {
        // transforme la chaîne de caractères en un objet File
        if ($value) {
            return new File($value);
        }

        return null;
    }
}
