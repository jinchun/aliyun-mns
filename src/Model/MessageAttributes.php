<?php

namespace AliyunMNS\Model;

use AliyunMNS\Constants;

/**
 * Please refer to
 * https://docs.aliyun.com/?spm=#/pub/mns/api_reference/intro&intro
 * for more details
 */
class MessageAttributes
{
    private $mailAttributes;

    public function __construct(
        $mailAttributes = null)
    {
        $this->mailAttributes = $mailAttributes;
    }

    public function setMailAttributes($mailAttributes)
    {
        $this->mailAttributes = $mailAttributes;
    }

    public function getMailAttributes()
    {
        return $this->mailAttributes;
    }

    public function writeXML(\XMLWriter $xmlWriter)
    {
        if ($this->mailAttributes != null) {
            $xmlWriter->startELement(Constants::MESSAGE_ATTRIBUTES);
            $this->mailAttributes->writeXML($xmlWriter);
            $xmlWriter->endElement();
        }
    }
}
