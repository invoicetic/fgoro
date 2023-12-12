<?php

namespace Invoicetic\Fgoro\Operations;


use Invoicetic\Common\Gateway\Operations\Behaviours\HasHttpEndpointRequestTrait;
use Invoicetic\Fgoro\Behaviours\HasLoginParametersTrait;
use Invoicetic\Fgoro\Behaviours\HasPlatformUrlTrait;

abstract class AbstractRequest extends \Invoicetic\Common\Gateway\Operations\AbstractRequest
{
    use HasHttpEndpointRequestTrait;
    use HasLoginParametersTrait;
    use HasPlatformUrlTrait;

    protected function generateBaseData(): array
    {
        $data = [];
        $data['CodUnic'] = $this->getLoginId();
        $data['Hash'] = '';
        $data['PlatformaUrl'] = $this->getPlatformaUrl();
        return $data;
    }
}

