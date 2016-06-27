<?php

namespace desher\expertsender\methods;

use desher\expertsender\mappers;

class Subscribers extends AbstractMethod
{
    const INFO_OPTION_FULL = 'Full';
    const INFO_OPTION_LONG = 'Long';
    const INFO_OPTION_SHORT = 'Short';
    const INFO_OPTION_EVENT_HISTORY = 'EventsHistory';

    /**
     * Subscriber info
     * @param $email
     * @param string $option
     * @return mappers\Subscriber|null
     */
    public function get($email, $option = self::INFO_OPTION_FULL)
    {
        $result = $this->connection->get('Subscribers', [
            'email' => $email,
            'option' => $option
        ]);

        if (isset($result['response']->ErrorMessage) && $result['response']->ErrorMessage->Code == "400") {
            return null;
        }

        return new mappers\Subscriber($email, isset($result['response']->Data) ? (array) $result['response']->Data : []);
    }

    /**
     * Save subscriber to list
     * @param mappers\Subscriber $subscriber
     * @param $listId
     * @return bool
     */
    public function save(mappers\Subscriber $subscriber, $listId)
    {
        $result = $this->connection->post('Subscribers', $this->getSubscriberXml($subscriber, $listId));
        return $result['code'] === 201;
    }

    /**
     * Return xml-object with subscriber data (for send to save method)
     * @param mappers\Subscriber $subscriber
     * @param $listId
     * @return \SimpleXMLElement
     */
    protected function getSubscriberXml(mappers\Subscriber $subscriber, $listId)
    {
        $xml = $this->connection->getDefaultRequestXml();

        // enable detailed response
        $xml->addChild('ReturnData', 'true');
        // add Data node with subscriber fields
        $dataXml = $xml->addChild('Data');
        $dataXml->addAttribute('xmlns:xsi:type', 'Subscriber');
        $dataXml->addChild('Email', $subscriber->email);
        $dataXml->addChild('Firstname', $subscriber->firstname);
        $dataXml->addChild('ListId', $listId);

        // add Properties list for subscriber
        $xmlProperties = $dataXml->addChild('Properties');
        foreach ($subscriber->properties as $property) {

            $xmlProperty = $xmlProperties->addChild('Property');
            $xmlProperty->addChild('Id', $property->id);
            $xmlProperty->addChild('Type', $property->type);
            $xmlPropertyValue = $xmlProperty->addChild('Value', $property->value);
            $xmlPropertyValue->addAttribute('xmlns:xsi:type', 'xs:' . $property->type);
        }

        return $xml;
    }
}