<?php

namespace Invoicetic\Fgoro;

use Invoicetic\Common\Gateway\HttpGateway;
use Invoicetic\Fgoro\Behaviours\HasLoginParametersTrait;

class FgoroGateway extends HttpGateway
{
    use HasLoginParametersTrait;

    public const ENDPOINT_PRODUCTION = 'https://api.fgo.ro/v1';
    public const ENDPOINT_SANDBOX = 'http://testapp.fgo.ro/publicws';
    public function getName(): string
    {
        return 'fgoro';
    }

    public function getDefaultParameters(): array
    {
        $params = parent::getDefaultParameters();
        $params['login_id'] = $this->getLoginId();
        $params['secret_key'] = $this->getSecretKey();
        return $params;
    }
}