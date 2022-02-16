<?php
declare(strict_types=1);

namespace Sane\Provider;

use AliyunMNS\Topic;
use Sane\AbstractProvider;
use AliyunMNS\Responses\BaseResponse;
use AliyunMNS\Requests\CreateTopicRequest;
use AliyunMNS\Responses\CreateTopicResponse;
use AliyunMNS\Responses\DeleteTopicResponse;
use AliyunMNS\Requests\PublishMessageRequest;

class TopicProvider extends AbstractProvider
{

    /**
     * @var Topic
     */
    protected $provider;

    /**
     * topic 创建
     *
     * @param string $topicName
     *
     * @return CreateTopicResponse
     */
    public function create(string $topicName): CreateTopicResponse
    {
        return $this->client->createTopic(new CreateTopicRequest($topicName));
    }

    /**
     * topic 删除
     *
     * @param string $topicName
     *
     * @return DeleteTopicResponse
     */
    public function delete(string $topicName): DeleteTopicResponse
    {
        return $this->client->deleteTopic($topicName);
    }

    /**
     *
     * @param string $topicName
     *
     * @return Topic
     */
    public function getProvider(string $topicName): Topic
    {
        return $this->provider = $this->client->getTopicRef($topicName);
    }

    /**
     * @param string $topicName
     * @param $messageBody
     * @param null $messageTag
     * @param null $messageAttributes
     *
     * @return BaseResponse
     */
    public function publishMessage(string $topicName, $messageBody, $messageTag = null, $messageAttributes = null): BaseResponse
    {
        return $this->getProvider($topicName)->publishMessage(new PublishMessageRequest($messageBody, $messageTag, $messageAttributes));
    }

    /**
     * 接收消息
     *
     * @param string $topicName
     * @param int $timeOut 秒数
     *
     * @return \AliyunMNS\Responses\ReceiveMessageResponse
     */
    public function receiveMessage(string $topicName, int $timeOut = 30): \AliyunMNS\Responses\ReceiveMessageResponse
    {
        return $this->getProvider($topicName)->receiveMessage($timeOut);
    }
}
