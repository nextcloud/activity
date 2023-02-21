# Adding a provider

Providers are used to parse, translate and beautify activities. They need to implement the `OCP\Activity\IProvider` interface and are registered in `appinfo/info.xml`. An example can be seen in the [files app](https://github.com/nextcloud/server/blob/8baf986d3bf6efb4d81fb18eac07592932467b8e/apps/files/appinfo/info.xml#L35-L38)

```xml
<?xml version="1.0"?>
<info>
	...
	<activity>
		...
		<providers>
			...
			<provider>OCA\Files\Activity\FavoriteProvider</provider>
		</providers>
	</activity>
</info>
```

The `provider` value is the fully namespaced class name of the `IProvider` implementation.

## Parse

The provider interface only contains one single `parse()` method. But it makes sense to split the logic up into multiple methods and the [favorite provider](https://github.com/nextcloud/server/blob/8baf986d3bf6efb4d81fb18eac07592932467b8e/apps/files/lib/Activity/FavoriteProvider.php) can serve as a good example for that. So the methods of this class will be explained here as an example.

As a quick summary, when the given event is known by the provider, the following method **must** be called:

* `setParsedSubject()`: Translated simple subject without markup, ready for use (e.g. `'You added hello.jpg to your favorites'`)

Additionally the following methods *should* be called, in order to beautify the activity:

* `setIcon()`: A full URL to an icon for the activity, e.g. `$this->url->getAbsoluteURL($this->url->imagePath('core', 'actions/starred.svg'))`
* `setRichSubject()`:  String subject including placeholders and the array of placeholders. See [this issue](https://github.com/nextcloud/server/issues/1706) for more information. Available object types are bound to the server version and can be found in the [`OCP\RichObjectStrings\Definitions`](https://github.com/nextcloud/server/blob/01f4c7550538a30311597d4eb9b889fbb04c4d67/lib/public/RichObjectStrings/Definitions.php) class. Note that starting with Nextcloud 26, you do not need to call `setParsedSubject` if you call `setRichSubject`, a parsed version will be computed automatically.

### Check responsibility

The first thing the provider should do, is to check whether the `IEvent` is one it cares about. A simple check for the app and maybe additionally the type, if the app has more then one provider, should be enough:

```php
		if ($event->getApp() !== 'files' || $event->getType() !== 'favorite') {
			throw new \InvalidArgumentException();
		}
```

Whenever a provider throws an `\InvalidArgumentException` the activity app will continue and pass the event to the next provider, so this should always be thrown when the event is unknown.

### Short translation

All file related activities are available with a short version e.g. "Added to favorites" (shown in the sidebar of the filelist) and a long version e.g. "You added hello.jpg to your favorites" (shown in the stream and mails). Whether or not it is applicable to use a short version can be checked via [`OCP\Activity\Manager::isFormattingFilteredObject()`](https://github.com/nextcloud/server/blob/8baf986d3bf6efb4d81fb18eac07592932467b8e/apps/files/lib/Activity/FavoriteProvider.php#L80-L80).

This allows to skip the file name in the sidebar, since it would just be repeated all the time while adding no value to the stream.

The favorite provider makes use of this and the activities don't have additional placeholders, so only the parsed subject is set.

### Long translations with "rich object" string

In the long version for the normal activity stream contains the filename. Objects like users, files and more can be highlighted in the "rich subject", which allows the app to show an avatar next to the name, link the file name to the file list and many more things.

The `IEvent::setRichSubject()` method has two arguments:

1. The already translated string with placeholders
2. The list of placeholders, in the case of the favorite provider it's the file that was marked as a favorite

```php
$event->setRichSubject(
	$this->l->t('You added {file} to your favorites')
	['file' => [
		'type' => 'file',
		'id' => $event->getObjectId(),
		'name' => basename($event->getObjectName()),
		'path' => $event->getObjectName(),
	]]
);
```

The list of required keys for each object can be found in the [`OCP\RichObjectStrings\Definitions`](https://github.com/nextcloud/server/blob/01f4c7550538a30311597d4eb9b889fbb04c4d67/lib/public/RichObjectStrings/Definitions.php) class.

### Merging activities

It is also possible to merge activities. E.g. when a user favorites two files, those can be combined to the following:

```php
$event->setRichSubject(
	$this->l->t('You added {file1} and {file2} to your favorites')
	[
		'file1' => [
			'type' => 'file',
			'id' => 23,
			'name' => 'file1.txt',
			'path' => 'path1/to1/file1.txt',
		],
		'file1' => [
			'type' => 'file',
			'id' => 42,
			'name' => 'file2.txt',
			'path' => 'path2/to2/file2.txt',
		],
	]
);
```

Merging two events is fairly easy. Afterwards the child needs to be set, to avoid the previous event from showing up in the stream additionally `$event->setChildEvent($previousEvent)`.

But this should not be done manually! Instead dependency injection should be used to get `OCP\Activity\IEventMerger`. This helper automatically merges events, when the following requirements are met:

* Both events need to have the same `getApp()`
* Both events must not have a message `getMessage()`
* Both events need to have the same subject `getSubject()`
* Both events need to have the same object type `getObjectType()`
* The time difference between both events must not be bigger then 3 hours
* Only up to 5 events can be merged.
* All parameters apart from such starting with `$mergeParameter` must be the same for both events.

Activities should also **not be merged manually** if those requirements are not met.
