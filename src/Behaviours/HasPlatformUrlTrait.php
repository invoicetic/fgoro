<?php

namespace Invoicetic\Fgoro\Behaviours;

trait HasPlatformUrlTrait
{

    public function setPlatformaUrl(string $value): void
    {
        $this->setParameter('platforma_url', $value);
    }

    public function getPlatformaUrl()
    {
        return $this->getParameter('platforma_url');
    }

}

