<?php

namespace Invoicetic\Fgoro\Operations;


use Invoicetic\Common\Gateway\Operations\Behaviours\HasHttpEndpointRequestTrait;
use Invoicetic\Fgoro\Behaviours\HasLoginParametersTrait;
use Invoicetic\Fgoro\Behaviours\HasPlatformUrlTrait;
use Psr\Http\Message\RequestInterface;
use UnexpectedValueException;

abstract class AbstractRequest extends \Invoicetic\Common\Gateway\Operations\AbstractRequest
{
    use HasHttpEndpointRequestTrait;
    use HasLoginParametersTrait;
    use HasPlatformUrlTrait;

    protected ?int $httpStatusCode = null;

    public function getHttpStatusCode(): ?int
    {
        return $this->httpStatusCode;
    }

    public function getHeaders(): array
    {
        $this->validateParameters('login_id', 'secret_key');

        return [
            'Authorization' => 'Bearer ' . $this->getSecretKey(),
            'Fgo-Cod-Unic' => (string) $this->getLoginId(),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json, application/problem+json',
        ];
    }

    protected function createRequest($method, $url, $headers = [], $body = [], $cookie = []): RequestInterface
    {
        $request = parent::createRequest($method, $url, [], $body, $cookie);
        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }

        return $request;
    }

    protected function createResponseDataFromHttpResponse($httpResponse)
    {
        $this->httpStatusCode = $httpResponse->getStatusCode();
        $data = json_decode((string) $httpResponse->getBody(), true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($data)) {
            throw new UnexpectedValueException('Expected a JSON object from FGO API v2.');
        }
        if ($this->httpStatusCode >= 200 && $this->httpStatusCode < 300 && !isset($data['data'])) {
            throw new UnexpectedValueException('Missing data envelope in successful FGO API v2 response.');
        }

        return $data;
    }
}
