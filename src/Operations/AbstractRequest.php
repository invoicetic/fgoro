<?php

namespace Invoicetic\Fgoro\Operations;


use Invoicetic\Common\Gateway\Operations\Behaviours\HasHttpEndpointRequestTrait;
use Invoicetic\Fgoro\Behaviours\HasLoginParametersTrait;

abstract class AbstractRequest extends \Invoicetic\Common\Gateway\Operations\AbstractRequest
{
    use HasHttpEndpointRequestTrait;
    use HasLoginParametersTrait;

}

