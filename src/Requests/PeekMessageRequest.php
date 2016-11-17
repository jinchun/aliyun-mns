<?php

namespace AliyunMNS\Requests;

class PeekMessageRequest extends BaseRequest
{
    private $queueName;

    public function __construct($queueName)
    {
        parent::__construct('get', 'queues/' . $queueName . '/messages?peekonly=true');

        $this->queueName = $queueName;
    }

    public function getQueueName()
    {
        return $this->queueName;
    }

    public function generateBody()
    {
        return null;
    }

    public function generateQueryString()
    {
        return null;
    }
}
