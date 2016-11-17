<?php

namespace AliyunMNS\Model;

use AliyunMNS\Constants;

/**
 * Please refer to
 * https://docs.aliyun.com/?spm=#/pub/mns/api_reference/intro&intro
 * for more details
 */
class AccountAttributes
{
    private $loggingBucket;

    public function __construct(
        $loggingBucket = null)
    {
        $this->loggingBucket = $loggingBucket;
    }

    public function setLoggingBucket($loggingBucket)
    {
        $this->loggingBucket = $loggingBucket;
    }

    public function getLoggingBucket()
    {
        return $this->loggingBucket;
    }

    public function writeXML(\XMLWriter $xmlWriter)
    {
        if ($this->loggingBucket !== null) {
            $xmlWriter->writeElement(Constants::LOGGING_BUCKET, $this->loggingBucket);
        }
    }

    public static function fromXML(\XMLReader $xmlReader)
    {
        $loggingBucket = null;

        while ($xmlReader->read()) {
            if ($xmlReader->nodeType == \XMLReader::ELEMENT) {
                switch ($xmlReader->name) {
                case 'LoggingBucket':
                    $xmlReader->read();
                    if ($xmlReader->nodeType == \XMLReader::TEXT) {
                        $loggingBucket = $xmlReader->value;
                    }
                    break;
                }
            }
        }

        $attributes = new self($loggingBucket);

        return $attributes;
    }
}
