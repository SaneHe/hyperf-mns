<?php
declare(strict_types=1);

namespace Sane;

use Hyperf\AsyncQueue\JobInterface;
use Hyperf\AsyncQueue\Driver\Driver;
use Psr\Container\ContainerInterface;
use Hyperf\AsyncQueue\MessageInterface;
use Sane\Exception\InvalidArgumentException;

class MnsDriver extends Driver
{
    /**
     * @var Application|mixed
     */
    protected $mnsApp;

    /**
     * MnsDriver constructor.
     *
     * @param ContainerInterface $container
     * @param $config
     *
     * @throws InvalidArgumentException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container, $config)
    {
        parent::__construct($container, $config);

        if (!$this->packer instanceof MnsMessagePacker) {
            throw new InvalidArgumentException("invalid parameter packer: " . $config['packer'] ?? '');
        }

        $this->mnsApp = $container->get(Application::class);
    }

    protected function retry(MessageInterface $message): bool
    {
        // TODO: Implement retry() method.
    }

    protected function remove($data): bool
    {
        // TODO: Implement remove() method.
    }

    public function push(JobInterface $job, int $delay = 0): bool
    {
        // TODO: Implement push() method.
    }

    public function delete(JobInterface $job): bool
    {
        // TODO: Implement delete() method.
    }

    public function pop(): array
    {
        // TODO: Implement pop() method.
    }

    public function ack($data): bool
    {
        // TODO: Implement ack() method.
    }

    public function fail($data): bool
    {
        // TODO: Implement fail() method.
    }

    public function reload(string $queue = null): int
    {
        // TODO: Implement reload() method.
    }

    public function flush(string $queue = null): bool
    {
        // TODO: Implement flush() method.
    }

    public function info(): array
    {
        // TODO: Implement info() method.
    }
}
