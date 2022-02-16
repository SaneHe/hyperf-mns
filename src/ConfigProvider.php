<?php

declare(strict_types=1);

namespace Sane;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                Application::class => ApplicationFactory::class,
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'mns',
                    'description' => 'The config for aliyun mns.',
                    'source' => __DIR__ . '/../publish/mns.php',
                    'destination' => BASE_PATH . '/config/autoload/mns.php',
                ],
            ],
        ];
    }
}
