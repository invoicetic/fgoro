<?php

namespace Invoicetic\Fgoro\Operations;

abstract class AbstractResponse extends \Invoicetic\Common\Gateway\Operations\AbstractResponse
{
    protected ?int $statusCode;

    public function __construct(AbstractRequest $request, $data)
    {
        parent::__construct($request, $data);
        $this->statusCode = $request->getHttpStatusCode();
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function isSuccessful(): bool
    {
        return $this->statusCode !== null && $this->statusCode >= 200 && $this->statusCode < 300;
    }

    public function getMessage(): string
    {
        return $this->data['detail'] ?? $this->data['title'] ?? '';
    }

    public function getCode(): ?string
    {
        return $this->data['type'] ?? null;
    }
}
