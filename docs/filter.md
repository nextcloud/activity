# Adding a filter

In order to add a new filter to the sidebar and allow to restrict the activity stream, the `OCP\Activity\IFilter` interface needs to be implemented and registered in `appinfo/info.xml`. An example can be seen in the [comments app](https://github.com/nextcloud/server/blob/8105ba99297222b7db97b556a55f306c3f7cabc0/apps/comments/appinfo/info.xml#L22-L24)

```xml
<?xml version="1.0"?>
<info>
	...
	<activity>
		...
		<filters>
			<filter>OCA\Comments\Activity\Filter</filter>
		</filters>
	</activity>
</info>
```

The `filter` value is the fully namespaced class name of the `IFilter` implementation.

## Identifier

The identifier is used in the URL and as a HTML ID, therefor only lowercase a-z and underscores are allowed.

## Name

The name **must** already be translated and in best case only consist of 1-3 short words. It is also the label which is used by the link in the filter list of the web UI.

## Icon

The icon **must** be an absolute URL. The chosen icon should be 32*32 pixels and when possible a SVG.

## Priority

Priority should technically be a value between 0-100, where 0 means it's listed first and 100 last. 70 should be seen as a decent default value, while values lower then 10 should not be used. These are reserved for special filters like the "All activies", "By you", "By others", etc.

## Filter apps

With the `allowedApps()` method specify a list of apps. Only activities which belong to one of the given apps are then returned by the filter. E.g. the [comments app](https://github.com/nextcloud/server/blob/8105ba99297222b7db97b556a55f306c3f7cabc0/apps/comments/lib/Activity/Filter.php#L88-L88) only returns `return ['comments'];` here.

To return events of all apps, an empty array should be returned.

## Filter types

With the `filterTypes()` method the list of activities can be limited even more. This is used for example in the [dav app](https://github.com/nextcloud/server/blob/253a75e5aef409ca5ac412f3d8d3ccd06a9f4a86/apps/dav/lib/CalDAV/Activity/Filter/Todo.php#L82-L82) to have a filter where only the todo/task events are shown:

```php
return array_intersect(['calendar_todo'], $types);
```

Calendar/event related activities are also part of the dav app, but are handled in a second filter.
The types this method deals with are the identifiers of the `OCP\Activity\ISetting` implementations.

If no restriction is to be performed, the parameter should be returned directly:
```php
return $types;
```
