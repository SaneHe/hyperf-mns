<?php
declare(strict_types=1);

namespace Sane\Provider;

use AliyunMNS\Queue;
use Sane\AbstractProvider;
use AliyunMNS\AsyncCallback;
use AliyunMNS\Responses\MnsPromise;
use AliyunMNS\Requests\CreateQueueRequest;
use AliyunMNS\Requests\SendMessageRequest;
use AliyunMNS\Responses\CreateQueueResponse;
use AliyunMNS\Responses\DeleteQueueResponse;
use AliyunMNS\Responses\SendMessageResponse;
use AliyunMNS\Responses\ReceiveMessageResponse;
use AliyunMNS\Responses\ChangeMessageVisibilityResponse;

class QueueProvider extends AbstractProvider
{

    /**
     * @var Queue
     */
    protected $provider;

    /**
     * 创建队列
     *
     * @param string $queueName
     *
     * @return CreateQueueResponse
     */
    public function create(string $queueName): CreateQueueResponse
    {
        return $this->client->createQueue(new CreateQueueRequest($queueName));
    }

    /**
     * @param string $queueName
     *
     * @return DeleteQueueResponse
     */
    public function delete(string $queueName): DeleteQueueResponse
    {
        return $this->client->deleteQueue($queueName);
    }

    /**
     *
     * @param string $queueName
     *
     * @return Queue
     */
    public function getProvider(string $queueName): Queue
    {
        return $this->provider = $this->client->getQueueRef($queueName);
    }

    /**
     * 同步投递消息
     *
     * 异步投递消息 支持回调
     *
     * @param string $queueName  队列名
     * @param mixed $messageBody 消息内容
     * @param null $delaySeconds 延迟时间 秒数
     * @param null $priority     权重
     * @param bool $isBase64
     * @param AsyncCallback|null $callback
     *
     * @return MnsPromise|SendMessageResponse
     */
    public function publishMessage(string $queueName, $messageBody, $delaySeconds = null, $priority = null, bool $isBase64 = true, AsyncCallback $callback = null)
    {
        return $callback ?
            $this->getProvider($queueName)->sendMessageAsync(
                new SendMessageRequest($messageBody, $delaySeconds, $priority, $isBase64), $callback
            ) :
            $this->getProvider($queueName)->sendMessage(
                new SendMessageRequest($messageBody, $delaySeconds, $priority, $isBase64)
            );
    }

    /**
     * 接收消息
     *
     * @param string $queueName
     * @param int $timeOut 秒数
     *
     * @return ReceiveMessageResponse
     */
    public function receiveMessage(string $queueName, int $timeOut = 30): ReceiveMessageResponse
    {
        return $this->getProvider($queueName)->receiveMessage($timeOut);
    }

    /**
     * 删除消息
     *
     * @param string $queueName
     * @param null $receiptHandle
     * @param AsyncCallback|null $callback
     *
     * @return MnsPromise|ReceiveMessageResponse
     */
    public function deleteMessage(string $queueName, $receiptHandle = null, AsyncCallback $callback = null)
    {
        return $callback ?
            $this->getProvider($queueName)->deleteMessageAsync($receiptHandle, $callback) :
            $this->getProvider($queueName)->deleteMessage($receiptHandle);
    }

    /**
     * 修改消息的可见性
     *
     * @param string $queueName
     * @param null $receiptHandle
     * @param int $visibilityTimeout
     *
     * @return ChangeMessageVisibilityResponse
     */
    public function changeMessageVisibility(string $queueName, $receiptHandle = null, int $visibilityTimeout = 30): ChangeMessageVisibilityResponse
    {
        return $this->getProvider($queueName)->changeMessageVisibility($receiptHandle, $visibilityTimeout);
    }
}
