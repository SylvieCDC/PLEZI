<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit91857cd71900e43b6395f4e940059043
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Stripe\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Stripe\\' => 
        array (
            0 => __DIR__ . '/..' . '/stripe/stripe-php/lib',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'PHPExif' => 
            array (
                0 => __DIR__ . '/..' . '/miljar/php-exif/lib',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit91857cd71900e43b6395f4e940059043::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit91857cd71900e43b6395f4e940059043::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit91857cd71900e43b6395f4e940059043::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit91857cd71900e43b6395f4e940059043::$classMap;

        }, null, ClassLoader::class);
    }
}
