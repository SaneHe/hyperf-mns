<?php

declare(strict_types=1);

namespace Sane;

use AliyunMNS\Client;
use Hyperf\Utils\Codec\Json;
use AliyunMNS\Config as AliyunConfig;
use Sane\Exception\RequestException;
use Sane\Exception\MethodNotExistException;

class AbstractProvider
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var mixed
     */
    protected $provider;

    /**
     * AbstractProvider constructor.
     *
     * @param Application $app
     * @param Config $config
     */
    public function __construct(Application $app, Config $config)
    {
        $this->app = $app;
        $this->config = $config;
        $this->client = $this->client();
    }

    /**
     * @return Client
     */
    protected function client(): Client
    {
        $config = new AliyunConfig();
        if ($guzzleConfig = (array)$this->config->get('guzzle_config')) {
            foreach ($guzzleConfig as $key => $value) {
                $key = 'set' . ucfirst($key);
                method_exists($config, $key) ? $config->$key($value) : '';
            }
        }

        return new Client(
            $this->config->get('end_point'),
            $this->config->get('access_key'),
            $this->config->get('secret_key'),
            null,
            $config
        );
    }

    /**
     * @param ResponseInterface $response
     *
     * @return bool
     */
    protected function checkResponseIsOk(ResponseInterface $response): bool
    {
        if ($response->getStatusCode() !== 200) {
            return false;
        }

        return (string)$response->getBody() === 'ok';
    }

    /**
     * @param ResponseInterface $response
     *
     * @return array
     */
    protected function handleResponse(ResponseInterface $response): array
    {
        $statusCode = $response->getStatusCode();
        $contents = (string)$response->getBody();

        if ($statusCode !== 200) {
            throw new RequestException($contents, $statusCode);
        }

        return Json::decode($contents);
    }

    /**
     * @param $method
     * @param $param
     *
     * @return false|mixed
     * @throws MethodNotExistException
     */
    public function __call($method, $param)
    {
        if ($this->provider && method_exists($this->provider, $method)) {
            return call_user_func_array([$this->provider, $method], $param);
        }

        throw new MethodNotExistException("{$method} not exist in " . get_class($this->provider));
    }
}
