# Creating a new event

To create and publish an event to the activity app, a new `IEvent` should be fetched from the activity manager and afterwards be passed to the `publish()` method:

```php
$event = \OC::$server->getActivityManager()->createEvent();
...
\OC::$server->getActivityManager()->publish($event);
```

Tthe following values **must** be set, before publishing an event:

* `setApp()`
* `setType()`
* `setAffectedUser()`
* `setAuthor()`
* `setTimestamp()`
* `setSubject()`
* `setObject()`

Additionally these values **can** be set:
* `setMessage()`
* `setLink()` - should be done in `IProvider::parse()`
* `setIcon()` - should be done in `IProvider::parse()`

The following values **should not** be set on publishing (are not saved), instead they should be set in `IProvider::parse()`:

* `setParsedSubject()`
* `setRichSubject()`
* `setParsedMessage()`
* `setRichMessage()`
* `setChildEvent()`

