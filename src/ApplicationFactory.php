<?php
declare(strict_types=1);

namespace Sane;

use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;

class ApplicationFactory
{

    public function __invoke(ContainerInterface $container): Application
    {
        $config = $container->get(ConfigInterface::class)->get('mns', []);

        return new Application(new Config($config));
    }
}
