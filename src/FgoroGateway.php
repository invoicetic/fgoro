<?php

namespace Invoicetic\Fgoro;

use Invoicetic\Common\Gateway\HttpGateway;
use Invoicetic\Fgoro\Behaviours\HasLoginParametersTrait;
use LogicException;

class FgoroGateway extends HttpGateway
{
    use HasLoginParametersTrait;

    public const ENDPOINT_PRODUCTION = 'https://api.fgo.ro/v2';

    protected function getSandboxEndpoint()
    {
        throw new LogicException(
            'The v1 sandbox is not compatible with FGO API v2. Configure a confirmed v2 endpoint explicitly instead of enabling sandbox.'
        );
    }

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