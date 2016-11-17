<?php

use AliyunMNS\Client;

/**
 * class TestCase.
 */
class TestCase extends PHPUnit_Framework_TestCase
{
    protected $endPoint;
    protected $accessId;
    protected $accessKey;
    /** @var AliyunMNS\Client client */
    protected $client;

    public function setUp()
    {
        $ini_array = parse_ini_file(__DIR__ . '/aliyun-mns.ini');

        $this->endPoint  = $ini_array['endpoint'];
        $this->accessId  = $ini_array['accessid'];
        $this->accessKey = $ini_array['accesskey'];

        $this->client = new Client($this->endPoint, $this->accessId, $this->accessKey);
    }
}
