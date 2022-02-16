<?php
declare(strict_types=1);

namespace Sane;

use Sane\Provider\QueueProvider;
use Sane\Provider\TopicProvider;
use Sane\Exception\InvalidArgumentException;

class Application
{
    /**
     * 服务提供者
     *
     * @var string[]
     */
    protected $alias = [
        'queue' => QueueProvider::class,
        'Topic' => TopicProvider::class,
    ];

    /**
     * 服务提供者实例
     *
     * @var array
     */
    protected $providers = [];

    /**
     * @var Config
     */
    protected $config;

    /**
     * Application constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $name
     *
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function __get(string $name)
    {
        if (!isset($name) || !isset($this->alias[$name])) {
            throw new InvalidArgumentException("{$name} is invalid.");
        }

        if (isset($this->providers[$name])) {
            return $this->providers[$name];
        }

        $class = $this->alias[$name];
        return $this->providers[$name] = new $class($this, $this->config);
    }
}
