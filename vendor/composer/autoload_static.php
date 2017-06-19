<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb481fe47b96a7cf90e68d14891d7ac6e
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Twig\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Twig\\' => 
        array (
            0 => __DIR__ . '/..' . '/twig/twig/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'T' => 
        array (
            'Twig_Extensions_' => 
            array (
                0 => __DIR__ . '/..' . '/slim/extras/Views/Extension',
            ),
            'Twig_' => 
            array (
                0 => __DIR__ . '/..' . '/twig/twig/lib',
            ),
        ),
        'S' => 
        array (
            'Slim\\Extras' => 
            array (
                0 => __DIR__ . '/..' . '/slim/extras',
            ),
            'Slim' => 
            array (
                0 => __DIR__ . '/..' . '/slim/slim',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb481fe47b96a7cf90e68d14891d7ac6e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb481fe47b96a7cf90e68d14891d7ac6e::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitb481fe47b96a7cf90e68d14891d7ac6e::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
