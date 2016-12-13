# Adding a setting

*Note:* settings are what types used to be in the old `OCP\Activity\IExtension` interface.

In order to add a new setting to the personal page and make it possible to store events with this type, the `OCP\Activity\ISetting` interface needs to be implemented and registered in `appinfo/info.xml`. An example can be seen in the [comments app](https://github.com/nextcloud/server/blob/8105ba99297222b7db97b556a55f306c3f7cabc0/apps/comments/appinfo/info.xml#L18-L20)

```xml
<?xml version="1.0"?>
<info>
	...
	<activity>
		...
		<settings>
			<setting>OCA\Comments\Activity\Setting</setting>
		</settings>
	</activity>
</info>
```

The `setting` value is the fully namespaced class name of the `ISetting` implementation.

## Identifier

The identifier is also used as a HTML ID, therefor only lowercase a-z and underscores are allowed.

**Note:** This must also match the value that is used in the `IEvent::setType()` of the event, otherwise the event will not be visible.

## Name

The name **must** already be translated and should be a short and descriptive sentence. One or two important words can also be highlighted using the `<strong>` HTML tag, to allow easier recognition of the setting.

## Icon

The icon **must** be an absolute URL. The chosen icon should be 32*32 pixels and when possible a SVG.

## Priority

Priority should technically be a value between 0-100, where 0 means it's listed first and 100 last. 70 should be seen as a decent default value, while values lower then 10 should not be used. These are reserved for special settings of the activity app itself.

## Is default enabled stream / mail

The two "is default enabled x" booleans specify whether the setting is enabled or disabled by default for the stream or mail. Once a user changed their setting, the default is not used for them anymore. Changing the default is therefore not "retro active".

## Can change stream / mail

When the "can change x" boolean is set to `false`, users can not change this setting on the personal page. When both "can change x" booleans are false, the setting is not listed on the personal page at all.
