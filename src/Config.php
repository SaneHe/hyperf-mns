<?php
declare(strict_types=1);

namespace Sane;

class Config
{
    /**
     * @var string
     */
    protected $accessKey;

    /**
     * @var string
     */
    protected $secretKey;

    /**
     * @var string
     */
    protected $endPoint;

    /**
     * @var string
     */
    protected $queueEndPoint;

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var array
     */
    protected $guzzleConfig = [
        'headers' => [
            'charset' => 'UTF-8',
        ],
        'http_errors' => false,
    ];

    /**
     * Config constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        isset($config['prefix']) && $this->prefix = (string)$config['prefix'];
        isset($config['end_point']) && $this->endPoint = (string)$config['end_point'];
        isset($config['access_key']) && $this->accessKey = (string)$config['access_key'];
        isset($config['secret_key']) && $this->secretKey = (string)$config['secret_key'];
        isset($config['guzzle_config']) && $this->guzzleConfig = (array)$config['guzzle_config'];
        isset($config['queue_end_point']) && $this->queueEndPoint = (string)$config['queue_end_point'];
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function get(string $name): string
    {
        // 下划线转驼峰
        $property = preg_replace('/(.)(?=[A-Z])/u', '$1_', $name);

        if (property_exists($this, $property)) {
            return $this->$property;
        }

        return '';
    }
}
