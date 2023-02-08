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

            if(is_file($filePath) && str_contains($file, $fileName)) {
                $found[] = $filePath;
            } elseif (is_dir($filePath)) {
                $result = self::findInFolder($filePath, $fileName);
                $found = array_merge($found, $result);
            }
        }

        closedir($dir);

        return $found;
    }
}
