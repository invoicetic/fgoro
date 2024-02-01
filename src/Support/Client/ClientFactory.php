<?php

namespace Invoicetic\Fgoro\Support\Client;

use Invoicetic\Common\Dto\Party\Party;
use Invoicetic\Common\Dto\PostalAddress\PostalAddress;

class ClientFactory
{
    public static function fromParty(Party $party): Client
    {
        $client = new Client();

        self::parseName($party, $client);
        self::parseIdentification($party, $client);
        self::parseLegalEntity($party, $client);
        self::parseAddress($party, $client);
        self::parseContact($party, $client);

        return $client;
    }

    private static function parseName(Party $party, Client $client): void
    {
        $client->setDenumire($party->getName());
    }

    private static function parseIdentification(Party $party, Client $client): void
    {
        $identifier = $party->getPartyIdentification()->getValue();
        $int = intval($identifier);
        if ($int == $identifier) {
            $client->setCodUnic($int);
            return;
        }
    }

    private static function parseLegalEntity(Party $party, Client $client): void
    {
        $client->setTip($party->hasLegalEntity() ? 'PJ' : 'PF');
    }

    private static function parseAddress(Party $party, Client $client)
    {
        $address = $party->getPostalAddress();
        if (!($address instanceof PostalAddress)) {
            return;
        }
        $client->setJudet($address->getCountrySubentity());
        $client->setLocalitate($address->getCityName());
        $client->setAdresa($address->getStreetName() . ' ' . $address->getBuildingNumber());
//        $data['Tara'] = $customer->getPostalAddress()->getCountry()?->getIdentificationCode();
    }

    private static function parseContact(Party $party, Client $client): void
    {
        $contact = $party->getContact();
        $client->setEmail($contact->getEmail());
        $client->setTelefon($contact->getTelephone());
    }
}
