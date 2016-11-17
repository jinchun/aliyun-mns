<?php

namespace AliyunMNS;

use AliyunMNS\Http\HttpClient;
use AliyunMNS\Model\QueueAttributes;
use AliyunMNS\Requests\BatchDeleteMessageRequest;
use AliyunMNS\Requests\BatchPeekMessageRequest;
use AliyunMNS\Requests\BatchReceiveMessageRequest;
use AliyunMNS\Requests\BatchSendMessageRequest;
use AliyunMNS\Requests\ChangeMessageVisibilityRequest;
use AliyunMNS\Requests\DeleteMessageRequest;
use AliyunMNS\Requests\GetQueueAttributeRequest;
use AliyunMNS\Requests\PeekMessageRequest;
use AliyunMNS\Requests\ReceiveMessageRequest;
use AliyunMNS\Requests\SendMessageRequest;
use AliyunMNS\Requests\SetQueueAttributeRequest;
use AliyunMNS\Responses\BatchDeleteMessageResponse;
use AliyunMNS\Responses\BatchPeekMessageResponse;
use AliyunMNS\Responses\BatchReceiveMessageResponse;
use AliyunMNS\Responses\BatchSendMessageResponse;
use AliyunMNS\Responses\ChangeMessageVisibilityResponse;
use AliyunMNS\Responses\DeleteMessageResponse;
use AliyunMNS\Responses\GetQueueAttributeResponse;
use AliyunMNS\Responses\PeekMessageResponse;
use AliyunMNS\Responses\ReceiveMessageResponse;
use AliyunMNS\Responses\SendMessageResponse;
use AliyunMNS\Responses\SetQueueAttributeResponse;

class Queue
{
    private $queueName;
    private $client;

    // boolean, whether the message body will be encoded in base64
    private $base64;

    public function __construct(HttpClient $client, $queueName, $base64 = true)
    {
        $this->queueName = $queueName;
        $this->client    = $client;
        $this->base64    = $base64;
    }

    public function setBase64($base64)
    {
        $this->base64 = $base64;
    }

    public function isBase64()
    {
        return $this->base64 == true;
    }

    public function getQueueName()
    {
        return $this->queueName;
    }

    /**
     * Set the QueueAttributes, detail API sepcs:
     * https://docs.aliyun.com/?spm=#/pub/mns/api_reference/api_spec&queue_operation
     *
     * @param QueueAttributes $attributes: the QueueAttributes to set
     *
     * @throws QueueNotExistException if queue does not exist
     * @throws InvalidArgumentException if any argument value is invalid
     * @throws MnsException if any other exception happends
     * @return SetQueueAttributeResponse: the response
     */
    public function setAttribute(QueueAttributes $attributes)
    {
        $request  = new SetQueueAttributeRequest($this->queueName, $attributes);
        $response = new SetQueueAttributeResponse();

        return $this->client->sendRequest($request, $response);
    }

    public function setAttributeAsync(QueueAttributes $attributes,
        AsyncCallback $callback = null)
    {
        $request  = new SetQueueAttributeRequest($this->queueName, $attributes);
        $response = new SetQueueAttributeResponse();

        return $this->client->sendRequestAsync($request, $response, $callback);
    }

    /**
     * Get the QueueAttributes, detail API sepcs:
     * https://docs.aliyun.com/?spm=#/pub/mns/api_reference/api_spec&queue_operation
     *
     * @throws QueueNotExistException if queue does not exist
     * @throws MnsException if any other exception happends
     * @return GetQueueAttributeResponse: containing the attributes
     */
    public function getAttribute()
    {
        $request  = new GetQueueAttributeRequest($this->queueName);
        $response = new GetQueueAttributeResponse();

        return $this->client->sendRequest($request, $response);
    }

    public function getAttributeAsync(AsyncCallback $callback = null)
    {
        $request  = new GetQueueAttributeRequest($this->queueName);
        $response = new GetQueueAttributeResponse();

        return $this->client->sendRequestAsync($request, $response, $callback);
    }

    /**
     * SendMessage, the messageBody will be automatically encoded in base64
     *     If you do not need the message body to be encoded in Base64,
     *     please specify the $base64 = FALSE in Queue
     *
     * detail API sepcs:
     * https://docs.aliyun.com/?spm=#/pub/mns/api_reference/api_spec&message_operation
     *
     * @param SendMessageRequest: containing the message body and properties
     *
     * @throws QueueNotExistException if queue does not exist
     * @throws InvalidArgumentException if any argument value is invalid
     * @throws MalformedXMLException if any error in xml
     * @throws MnsException if any other exception happends
     * @return SendMessageResponse: containing the messageId and bodyMD5
     */
    public function sendMessage(SendMessageRequest $request)
    {
        $request->setQueueName($this->queueName);
        $request->setBase64($this->base64);
        $response = new SendMessageResponse();

        return $this->client->sendRequest($request, $response);
    }

    public function sendMessageAsync(SendMessageRequest $request,
        AsyncCallback $callback = null)
    {
        $request->setQueueName($this->queueName);
        $request->setBase64($this->base64);
        $response = new SendMessageResponse();

        return $this->client->sendRequestAsync($request, $response, $callback);
    }

    /**
     * PeekMessage, the messageBody will be automatically decoded as base64 if the $base64 in Queue is TRUE
     *
     * detail API sepcs:
     * https://docs.aliyun.com/?spm=#/pub/mns/api_reference/api_spec&message_operation
     *
     * @throws QueueNotExistException if queue does not exist
     * @throws MessageNotExistException if no message exists in the queue
     * @throws MnsException if any other exception happends
     * @return PeekMessageResponse: containing the messageBody and properties
     */
    public function peekMessage()
    {
        $request  = new PeekMessageRequest($this->queueName);
        $response = new PeekMessageResponse($this->base64);

        return $this->client->sendRequest($request, $response);
    }

    public function peekMessageAsync(AsyncCallback $callback = null)
    {
        $request  = new PeekMessageRequest($this->queueName);
        $response = new PeekMessageResponse($this->base64);

        return $this->client->sendRequestAsync($request, $response, $callback);
    }

    /**
     * ReceiveMessage, the messageBody will be automatically decoded as base64 if $base64 = TRUE in Queue
     * detail API sepcs:
     * https://docs.aliyun.com/?spm=#/pub/mns/api_reference/api_spec&message_operation
     *
     * @param waitSeconds: the long polling waitseconds
     *
     * @throws QueueNotExistException if queue does not exist
     * @throws MessageNotExistException if no message exists in the queue
     * @throws MnsException if any other exception happends
     * @return ReceiveMessageResponse: containing the messageBody and properties
     *          the response is same as PeekMessageResponse,
     *          except that the receiptHandle is also returned in receiveMessage
     */
    public function receiveMessage($waitSeconds = null)
    {
        $request  = new ReceiveMessageRequest($this->queueName, $waitSeconds);
        $response = new ReceiveMessageResponse($this->base64);

        return $this->client->sendRequest($request, $response);
    }

    public function receiveMessageAsync(AsyncCallback $callback = null)
    {
        $request  = new ReceiveMessageRequest($this->queueName);
        $response = new ReceiveMessageResponse($this->base64);

        return $this->client->sendRequestAsync($request, $response, $callback);
    }

    /**
     * DeleteMessage
     * detail API sepcs:
     * https://docs.aliyun.com/?spm=#/pub/mns/api_reference/api_spec&message_operation
     *
     * @param $receiptHandle: the receiptHandle returned from receiveMessage
     *
     * @throws QueueNotExistException if queue does not exist
     * @throws InvalidArgumentException if the argument is invalid
     * @throws ReceiptHandleErrorException if the $receiptHandle is invalid
     * @throws MnsException if any other exception happends
     * @return ReceiveMessageResponse
     */
    public function deleteMessage($receiptHandle)
    {
        $request  = new DeleteMessageRequest($this->queueName, $receiptHandle);
        $response = new DeleteMessageResponse();

        return $this->client->sendRequest($request, $response);
    }

    public function deleteMessageAsync($receiptHandle,
        AsyncCallback $callback = null)
    {
        $request  = new DeleteMessageRequest($this->queueName, $receiptHandle);
        $response = new DeleteMessageResponse();

        return $this->client->sendRequestAsync($request, $response, $callback);
    }

    /**
     * ChangeMessageVisibility, set the nextVisibleTime for the message
     * detail API sepcs:
     * https://docs.aliyun.com/?spm=#/pub/mns/api_reference/api_spec&message_operation
     *
     * @param $receiptHandle: the receiptHandle returned from receiveMessage
     *
     * @throws QueueNotExistException if queue does not exist
     * @throws MessageNotExistException if the message does not exist
     * @throws InvalidArgumentException if the argument is invalid
     * @throws ReceiptHandleErrorException if the $receiptHandle is invalid
     * @throws MnsException if any other exception happends
     * @return ChangeMessageVisibilityResponse
     */
    public function changeMessageVisibility($receiptHandle, $visibilityTimeout)
    {
        $request  = new ChangeMessageVisibilityRequest($this->queueName, $receiptHandle, $visibilityTimeout);
        $response = new ChangeMessageVisibilityResponse();

        return $this->client->sendRequest($request, $response);
    }

    /**
     * BatchSendMessage, message body will be automatically encoded in base64
     *     If you do not need the message body to be encoded in Base64,
     *     please specify the $base64 = FALSE in Queue
     *
     * detail API sepcs:
     * https://docs.aliyun.com/?spm=#/pub/mns/api_reference/api_spec&message_operation
     *
     * @param BatchSendMessageRequest:
     *            the requests containing an array of SendMessageRequestItems
     *
     * @throws QueueNotExistException if queue does not exist
     * @throws MalformedXMLException if any error in the xml
     * @throws InvalidArgumentException if the argument is invalid
     * @throws BatchSendFailException if some messages are not sent
     * @throws MnsException if any other exception happends
     * @return BatchSendMessageResponse
     */
    public function batchSendMessage(BatchSendMessageRequest $request)
    {
        $request->setQueueName($this->queueName);
        $request->setBase64($this->base64);
        $response = new BatchSendMessageResponse();

        return $this->client->sendRequest($request, $response);
    }

    public function batchSendMessageAsync(BatchSendMessageRequest $request,
        AsyncCallback $callback = null)
    {
        $request->setQueueName($this->queueName);
        $request->setBase64($this->base64);
        $response = new BatchSendMessageResponse();

        return $this->client->sendRequestAsync($request, $response, $callback);
    }

    /**
     * BatchReceiveMessage, message body will be automatically decoded as base64 if $base64 = TRUE in Queue
     *
     * detail API sepcs:
     * https://docs.aliyun.com/?spm=#/pub/mns/api_reference/api_spec&message_operation
     *
     * @param BatchReceiveMessageRequest:
     *            containing numOfMessages and waitSeconds
     *
     * @throws QueueNotExistException if queue does not exist
     * @throws MessageNotExistException if no message exists
     * @throws MnsException if any other exception happends
     * @return BatchReceiveMessageResponse:
     *            the received messages
     */
    public function batchReceiveMessage(BatchReceiveMessageRequest $request)
    {
        $request->setQueueName($this->queueName);
        $response = new BatchReceiveMessageResponse($this->base64);

        return $this->client->sendRequest($request, $response);
    }

    public function batchReceiveMessageAsync(BatchReceiveMessageRequest $request, AsyncCallback $callback = null)
    {
        $request->setQueueName($this->queueName);
        $response = new BatchReceiveMessageResponse($this->base64);

        return $this->client->sendRequestAsync($request, $response, $callback);
    }

    /**
     * BatchPeekMessage, message body will be automatically decoded as base64 is $base64 = TRUE in Queue
     *
     * detail API sepcs:
     * https://docs.aliyun.com/?spm=#/pub/mns/api_reference/api_spec&message_operation
     *
     * @param BatchPeekMessageRequest:
     *            containing numOfMessages and waitSeconds
     *
     * @throws QueueNotExistException if queue does not exist
     * @throws MessageNotExistException if no message exists
     * @throws MnsException if any other exception happends
     * @return BatchPeekMessageResponse:
     *            the received messages
     */
    public function batchPeekMessage($numOfMessages)
    {
        $request  = new BatchPeekMessageRequest($this->queueName, $numOfMessages);
        $response = new BatchPeekMessageResponse($this->base64);

        return $this->client->sendRequest($request, $response);
    }

    public function batchPeekMessageAsync($numOfMessages, AsyncCallback $callback = null)
    {
        $request  = new BatchPeekMessageRequest($this->queueName, $numOfMessages);
        $response = new BatchPeekMessageResponse($this->base64);

        return $this->client->sendRequestAsync($request, $response, $callback);
    }

    /**
     * BatchDeleteMessage
     * detail API sepcs:
     * https://docs.aliyun.com/?spm=#/pub/mns/api_reference/api_spec&message_operation
     *
     * @param $receiptHandles:
     *            array of $receiptHandle, which is got from receiveMessage
     *
     * @throws QueueNotExistException if queue does not exist
     * @throws ReceiptHandleErrorException if the receiptHandle is invalid
     * @throws InvalidArgumentException if the argument is invalid
     * @throws BatchDeleteFailException if any message not deleted
     * @throws MnsException if any other exception happends
     * @return BatchDeleteMessageResponse
     */
    public function batchDeleteMessage($receiptHandles)
    {
        $request  = new BatchDeleteMessageRequest($this->queueName, $receiptHandles);
        $response = new BatchDeleteMessageResponse();

        return $this->client->sendRequest($request, $response);
    }

    public function batchDeleteMessageAsync($receiptHandles, AsyncCallback $callback = null)
    {
        $request  = new BatchDeleteMessageRequest($this->queueName, $receiptHandles);
        $response = new BatchDeleteMessageResponse();

        return $this->client->sendRequestAsync($request, $response, $callback);
    }
}
