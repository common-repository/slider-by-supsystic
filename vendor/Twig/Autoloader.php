<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Autoloads Twig classes.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @deprecated since 1.21 and will be removed in 2.0. Use Composer instead. 2.0.
 */
class Twig_supTwgSsl_Autoloader
{
    /**
     * Registers Twig_supTwgSsl_Autoloader as an SPL autoloader.
     *
     * @param bool $prepend whether to prepend the autoloader or not
     */
    public static function register($prepend = false)
    {

        if (PHP_VERSION_ID < 50300) {
            spl_autoload_register(array(__CLASS__, 'autoload'));
        } else {
            spl_autoload_register(array(__CLASS__, 'autoload'), true, $prepend);
        }
    }

    /**
     * Handles autoloading of classes.
     *
     * @param string $class a class name
     */
    public static function autoload($class)
    {
        if (0 !== strpos($class, 'Twig_supTwgSsl')) {
            return;
        }

        if (is_file($file = dirname(__FILE__).'/../'.str_replace(array('_', "\0"), array('/', ''), $class).'.php')) {
            require $file;
        }
    }
}
