<?php

namespace desher\expertsender\methods;

use desher\expertsender\mappers;

class Lists extends AbstractMethod
{
    public function createList(mappers\SubscribersList $list)
    {
        $result = $this->connection->post('Lists', $this->getListXml($list));
        if ($result['code'] === 201) {
            return (int) $result['response']->Data;
        }
        return null;
    }

    protected function getListXml(mappers\SubscribersList $list)
    {
        $xml = $this->connection->getDefaultRequestXml();

        $dataXml = $xml->addChild('Data');
        $generalSettings = $dataXml->addChild('GeneralSettings');
        $generalSettings->addChild('Name', $list->name);
        $generalSettings->addChild('Language', $list->language);

        return $xml;
    }
}