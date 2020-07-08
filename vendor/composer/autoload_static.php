<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5b5e257a38c3975347b6d4684547b97b
{
    public static $prefixLengthsPsr4 = array (
        'd' => 
        array (
            'data\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'data\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5b5e257a38c3975347b6d4684547b97b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5b5e257a38c3975347b6d4684547b97b::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
