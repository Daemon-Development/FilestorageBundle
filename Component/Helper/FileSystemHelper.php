<?php

namespace Daemon\FilestorageBundle\Component\Helper;

use Symfony\Component\Filesystem\Filesystem;

class FileSystemHelper
{
    public static function createPathIfNotExists($path) {
        $pathArray = explode('/', $path);
        $currentPath = '';
        for ($i=0; $i < sizeof($pathArray); $i++) {
            $currentPath = FileSystemHelper::createFolderIfNotExists($currentPath, $pathArray[$i]);
        }
    }

    private static function createFolderIfNotExists($path, $folder) {
        $filesystem = new Filesystem();
        $newPath = $path . '/' . $folder;
        if (!$filesystem->exists($newPath)) {
            $filesystem->mkdir($newPath);
        }
        return $newPath;
    }
}
