ExpertSender API client
=================

PHP client for ExpertSender API

## Usage

```php
$expertSender = new ExpertSenderApi($apiKey);

# create new list
$list = new mappers\SubscribersList();
$list->name = 'Test list';
$listId = $expertSender->getLists()->createList($list);

# create new subscriber in list
$subscriber = new mappers\Subscriber('subscriber@email.com');
$subscriber
        ->setFirstname('Tester')
        ->addProperty(1, mappers\Property::TYPE_BOOLEAN, true);

$success = $expertSender->getSubscribers()->save($subscriber, $listId);

if ($success) {
    $subscriber = $expertSender->getSubscribers()->get('subscriber@email.com');

    # save additional data to table
    $expertSender->getTables()->addRow('orders', [
        'subscriber_id' => $subscriber->getId(),
        'product_id' => $productId,
    ]);
}

```
