<?php

namespace Invoicetic\Fgoro\Tests\Support\Client;

use Invoicetic\Common\Dto\Party\Party;
use Invoicetic\Fgoro\Support\Client\ClientFactory;
use PHPUnit\Framework\TestCase;

class ClientFactoryTest extends TestCase
{
    public function test_codunic_only_numeric()
    {
        $party = new Party();
        $party->setPartyIdentification('123456789');
        $client = ClientFactory::fromParty($party);
        self::assertSame(123456789, $client->getCodUnic());

        $party->setPartyIdentification('a4h5');
        $client = ClientFactory::fromParty($party);
        self::assertNull($client->getCodUnic());
    }

    public function test_codunic_with_ro()
    {
        $party = new Party();
        $party->setPartyIdentification('RO123456789');
        $client = ClientFactory::fromParty($party);
        self::assertSame(123456789, $client->getCodUnic());
    }
}
