<?php
declare(strict_types=1);

namespace Sane;

use Hyperf\AsyncQueue\Job;
use Sane\Exception\InvalidArgumentException;

abstract class MnsJob extends Job implements \JsonSerializable
{
    /**
     * job data
     *
     * @var array|string|mixed
     */
    protected $data;

    /**
     * @var string|null
     */
    protected $queue;

    /**
     * @var string|null
     */
    protected $topic;

    /**
     * MnsJob constructor.
     *
     * @param null $data
     * @param null $queue
     * @param null $topic
     *
     * @throws InvalidArgumentException
     */
    public function __construct($data = null, $queue = null, $topic = null)
    {
        if (($queue && $topic) || (!$queue && !$topic)) {
            throw new InvalidArgumentException("{$queue} and {$topic} forbidden same state: both values or not values");
        }

        if (!$data) {
            throw new InvalidArgumentException("cannot publish empty message: {$data}");
        }

        $this->data = $data;
        isset($queue) && $this->queue = (string)$queue;
        isset($topic) && $this->topic = (string)$topic;
    }

    /**
     * @param string $key
     *
     * @return null
     */
    public function get(string $key)
    {
        return property_exists($this, $key) ? $this->$key : null;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return array_merge(
            $this->formatData(),
            $this->formatQueueData(),
            $this->formatTopicData()
        );
    }

    /**
     * @return array
     */
    protected function formatData(): array
    {
        return [
            'job' => __CLASS__,
            'data' => $this->data,
        ];
    }

    /**\
     * @return array|null[]|string[]
     */
    protected function formatQueueData(): array
    {
        return $this->queue ? ['queue' => $this->queue] : [];
    }

    /**
     * @return array|null[]|string[]
     */
    protected function formatTopicData(): array
    {
        return $this->topic ? ['topic' => $this->topic] : [];
    }
}
