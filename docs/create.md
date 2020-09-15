# Creating a new event

To create and publish an event to the activity app, a new `IEvent` should be fetched from the activity manager and afterwards be passed to the `publish()` method:

```php
// The activity manager should be automatically injected
// by the type hint OCP\Activity\IManager, when inside a class.
// When you have a plain file without a class, you can use
// \OC::$server->getActivityManager() instead.

$event = $this->activityManager->generateEvent();
...
$this->activityManager->publish($event);
```

The following values **must** be set before publishing an event:

* `setApp()`
* `setType()` - this must match an `\OCP\Activity\ISetting::getIdentifier()`
* `setAffectedUser()`
* `setSubject()`
* `setObject()`

Additionally these values **can** be set:
* `setAuthor()` - if no author is set, the current user will be used
* `setTimestamp()` - if no time is set, the current time will be used
* `setMessage()`
* `setLink()` - should be done in `IProvider::parse()`
* `setIcon()` - should be done in `IProvider::parse()`

The following values **should not** be set on publishing (are not saved), instead they should be set in `IProvider::parse()`:

* `setParsedSubject()`
* `setRichSubject()`
* `setParsedMessage()`
* `setRichMessage()`
* `setChildEvent()`

