# Using the activity sidebar tab

Sometimes events are strongly connected with activities and thus belong to the activity stream but require custom rendering or special actions should be provided. In this case the Activity sidebar tab can be used.

**Note: This should be used sparely for UX reasons**

One example of this is the comments app that reuses the Activity sidebar tab for posting new comments on files.

## Checking if Activity sidebar API is available

To check if the sidebar API is available you can use `@nextcloud/initial-state` and lookup the key `sidebar-api-version` of the `activity` app.
E.g.:
```ts
if (loadState<number>('activity', 'sidebar-api-version', 0) >= 1) {
	// available
}
```

## Registration

Every plugin needs to be registered using `OC.Plugins.register('OCA.Activity.SidebarPlugin', yourPlugin)`.
Ideally this is done in an init script, see `\OCP\Util::addInitScript()`.


```js
const yourPlugin = {
	// Name of your plugin
	name: 'Your plugin name',
	// Called to when the plugin should register its functions
	attach(ActivityAPI) {
		// could be used to register a sidebar action
		ActivityAPI.registerSidebarAction(action)
		// could be used to register a filter to hide some activities from sidebar
		ActivityAPI.registerSidebarFilter(filter)
		// could be used to show entries in the sidebar that are not activites
		ActivityAPI.registerSidebarEntries(factory)
	},
	// Called for optional cleanup
	detach() {
		//...
	}
}
```

## Sidebar API
### Sidebar Actions
To allow other apps to register inputs on the Activity sidebar (see the Comments app input), apps can register an action using `registerSidebarAction`.

```ts
interface IActivitySidebarAction {
	/**
	 * The action is called with the HTML element where is should be mounted
	 */
	mount: (element: HTMLElement, options: MountOptions) => void

	/**
	 * Called just before the sidebar is destroyed to allow plugins to cleanup
	 */
	unmount: () => void
}
```

### Activity filters
Sometimes it is useful to filter activites from the activity stream on the sidebar, this is e.g. used by the comments app to remove comments activity.
Those filters can be registered using `registerSidebarFilter`

```ts
type IActivityFilter = (activity: ActivityModel) => boolean
```

### Adding entries to the Activity stream
To add custom entries, not available in the activity stream, apps can use `registerSidebarEntries` to register a factory.

```ts
type IActivityFactory = (options: { fileInfo: any, limit?: number, offset?: number }) => Promise<IActivitySidebarEntry[]>

interface IActivitySidebarEntry {
	/**
	 * Timestamp for sorting in ms
	 */
	timestamp: number

	/**
	 * The action is called with the HTML element where is should be mounted
	 */
	mount: (element: HTMLElement, options: Omit<MountOptions, 'fileInfo'>) => void

	/**
	 * Called just before the sidebar is destroyed to allow plugins to cleanup
	 */
	unmount: () => void
}
```
