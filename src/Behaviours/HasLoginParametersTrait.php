<?php

namespace Invoicetic\Fgoro\Behaviours;

trait HasLoginParametersTrait
{

    public function setLoginId(string $loginId): void
    {
        $this->setParameter('login_id', $loginId);
    }

    public function getLoginId()
    {
        return $this->getParameter('login_id');
    }

    public function setSecretKey(string $secretKey)
    {
        $this->setParameter('secret_key', $secretKey);
    }

    public function getSecretKey()
    {
        return $this->getParameter('secret_key');
    }
}

