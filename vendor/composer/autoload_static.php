<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc0e41413c6a21cfbca395628dbfd296e
{
    public static $prefixLengthsPsr4 = array (
        'V' => 
        array (
            'Vishal\\SakaDateConverter\\' => 25,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Vishal\\SakaDateConverter\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc0e41413c6a21cfbca395628dbfd296e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc0e41413c6a21cfbca395628dbfd296e::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitc0e41413c6a21cfbca395628dbfd296e::$classMap;

        }, null, ClassLoader::class);
    }
}
