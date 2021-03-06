<?php
namespace Skeleton\System;

/**
 * AutoLoader
 *
 * @author Bas Matthee <basmatthee@gmail.com>
 * @copyright Copyright (c) 2015 Bas Matthee <http://www.bas-matthee.nl>
 * @package Skeleton\System
 */
final class AutoLoader
{
    static public function loader($className)
    {
        $filename = str_replace('\'', DIRECTORY_SEPARATOR, $className) . ".php";
        $filename = str_replace('\\Skeleton\\', '', $filename);
        $filename = str_replace('Skeleton\\', '', $filename);

        if (false !== file_exists($filename)) {
            include($filename);

            if (false !== class_exists($className)) {
                return true;
            }
        }

        return false;
    }
}