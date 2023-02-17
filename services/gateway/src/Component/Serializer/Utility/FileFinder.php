<?php

declare(strict_types=1);

namespace Gateway\Component\Serializer\Utility;

final class FileFinder
{
    public static function findInFolder($fileName, $folderName = './'): bool|array
    {
        $found = [];
        $folderName = rtrim($folderName, '/');

        $dir = opendir($folderName);

        while(($file = readdir($dir)) !== false){
            $filePath = "$folderName/$file";

            if($file == '.' || $file == '..') {
                continue;
            }

            if(is_file($filePath) && strcmp($file, $fileName) == 0) {
                $found[] = $filePath;
            } elseif (is_dir($filePath)) {
                $result = self::findInFolder($fileName, $filePath);
                $found = array_merge($found, $result);
            }
        }

        closedir($dir);

        return $found;
    }
}
