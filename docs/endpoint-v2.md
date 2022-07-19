# Endpoint documentation

## Capabilities

```xml
GET /ocs/v2.php/cloud/capabilities

...
   <activity>
    <apiv2>
     <element>filters</element>
     <element>previews</element>
     <element>rich-strings</element>
    </apiv2>
   </activity>
...
```
## Request URL

```
GET /ocs/v2.php/apps/activity/api/v2/activity
```

With type filter

```
GET /ocs/v2.php/apps/activity/api/v2/activity/{filter}
```

Supported type filters can be obtained from:

```
GET /ocs/v2.php/apps/activity/api/v2/activity/filters
```

Additionally, filtering based on `object_type` and `object_id` (see below) is only valid when supplying `filter` as the type filter.  Filtering results based on `object_type` and `object_id` parameters is not supported when using any other type filter.

## Parameters

Name | Type | Description
---- | ---- | -----------
`since` | int (Optional) | The integer ID of the last activity that you’ve seen.
`limit` | int (Optional) | How many activities should be returned (Default: `50`)
`object_type` | string (Optional) | Allows to filter the activities to a given object. May only appear together with `object_id` and the `filter` type filter
`object_id` | string (Optional) | Allows to filter the activities to a given object. May only appear together with `object_type` and the `filter` type filter
`sort` | string - `asc` or `desc` | Should activities be given ascending or descending (from the `since`) (Default: `desc`)


## HTTP Status

Status Code | Description
----------- | -----------
`200 OK` |  Activities
`204 No Content` |  The user has selected no activities to be listed in the stream
`304 Not Modified` | ETag/If-None-Match are the same or the end of the activity list was reached
`403 Forbidden` | The offset activity belongs to a different user
`403 Forbidden` | The user is not logged in
`404 Not Found` | The filter is unknown

## Headers

### Link for the next request

Already includes all parameters
```
Link: <http(s)://localhost/ocs/v2.php/apps/activity/api/v2/activity/all?since=364>; rel="next"
```

### First known activity

In case the `since` parameter was not known, the header gives the first known activity ID
```
X-Activity-First-Known: 370
```

### Last given activity

Id that should be used as `since` parameter for the next request
```
X-Activity-Last-Given: 370
```


## Activity element

Field name | Type | Value description
---------- | ---- | -----------------
`activity_id` | int | Autoincrement value from the database
`datetime` | string | ISO 8601 date of the activity (e.g. `2015-11-20T12:49:31+00:00`)
`app` | string | App that created the activity (e.g. `'files'`, `'files_sharing'`, etc.)
`type` | string | For most files related activities this is the action that was performed on the file/folder (e.g. `'file_changed'`, `'file_created'` (same is used for both, file and folder)), other apps use other strings (e.g. `'announcementcenter'`)
`user` | string (Optional) | User ID of the user that triggered/created this activity (can also be empty in case of public link/remote share action)
`subject` | string | Translated simple subject without markup, ready for use (e.g. `'You created hello.jpg'`)
`subject_rich` | array (Optional) | `0` is the string subject including placeholders, `1` is an array with the placeholders. See [this issue](https://github.com/nextcloud/server/issues/1706) for more information
`message` | string (Optional) | Translated simple message without markup, ready for use (longer text, unused by core apps)
`message_rich` | array (Optional) | See `subject_rich`
`icon` | string (Optional) | A full URL to an icon for the activity
`link` | string (Optional) | A full URL pointing to a suitable location (e.g. `'http://localhost/cloud/master/core/index.php/apps/files/?dir=%2Ffolder'` in case the folder got created)
`object_type` | string (Optional) | Type of the object this activity is about (e.g. `'files'` is used for files and folders)
`object_id` | int (Optional) | ID of the object this activity is about (e.g. ID in the file cache is used for files and folders)
`object_name` | string (Optional) | Name of the object this activity is about (e.g. for files it's the relative path to the user's root: `'/folder/.travis.yml'`)
`objects` | object (Optional) | In activities that reference multiple objects (e.g. an activity detailing several files edited in the same folder), this field holds the objects referenced in this activity. They are stored in `objects` as key-value pairs of the `object_id` and the `object_name`: `{ object_id: object_name}`
`previews` | array (Optional) | A list of potential previews (currently only available for file activities). See `Preview element` for more information

### Preview element

Field name | Type | Value description
---------- | ---- | -----------------
`source` | string | Full URL of the image to be displayed
`link` | string | Full URL the preview should be wrapped in
`mimeType` | string | The mime type of the file (not the preview)
`fileId` | int | The if of the actual file
`view` | string | The view where the file can be found (either `files` or `trashbin`)
`isMimeTypeIcon` | bool | True if `source` points to a mime type icon instead of a real preview
`filename` | string | The filename of the file the preview is for

In case the endpoint returns more fields, they should be ignored and are deprecated (only for backwards compatibility usage) or internal.

### Example

```json
  {
    "activity_id": 1,
    "timestamp": 1446137950,
    "app": "files",
    "type": "file_created",
    "user": "test1",
    "affecteduser": "admin",
    "subject": "test1 created hello.txt",
    "subject_rich": {
      "0": "test1 created {file1}",
      "1": {
        "file1": {
          "type": "file",
          "id": 23,
          "name": "hello.txt",
          "path": "\/test\/hello.txt"
        }
      }
    },
    "message": null,
    "message_rich": {
      "0": "",
      "1": []
    },
    "icon": "https:\/\/localhost\/apps\/files\/img\/add-color.svg",
    "link": "",
    "object_type": "files",
    "object_id": 23,
    "object_name": "\/test\/hello.txt",
    "previews": [
      {
        "link": "https:\/\/localhost\/index.php\/apps\/files\/?dir=\/test&scrollto=hello.txt",
        "source": "https:\/\/localhost\/index.php\/core\/preview.png?file=\/hello.txt&x=150&y=150",
         "mimeType": "text/plain",
         "view": "files",
         "fileId": 23,
         "isMimeTypeIcon": false,
         "filename": "hello.txt"
      }
    ]
  }
```
