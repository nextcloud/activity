# Endpoint documentation

## Request URL

```
GET /index.php/apps/activity/api/v2/activity
```

With type filter

```
GET /index.php/apps/activity/api/v2/activity/:filter
```

## Parameters

Name | Type | Description
---- | ---- | -----------
`since` | int (Optional) | The integer ID of the last activity that you’ve seen.
`limit` | int (Optional) | How many activities should be returned (Default: `50`)
`object_type` | string (Optional) | Allows to filter the activities to a given object. May only appear together with `object_id`
`object_id` | string (Optional) | Allows to filter the activities to a given object. May only appear together with `object_type`
`sort` | string - `asc` or `desc` | Should activities be given ascending or descending (from the `since`) (Default: `desc`)

## HTTP Status

Status Code | Description
----------- | -----------
`200 OK` |  Activities
`204 No Content` |  The user has selected no activities to be listed in the stream
`304 Not Modified` | No new activities
`403 Forbidden` | The offset activity belongs to a different user
`403 Forbidden` | The user is not logged in
`404 Not Found` | The filter is unknown

## Headers

### Link for the next request

Already includes all parameters
```
Link: <http(s)://localhost/index.php/apps/activity/api/v2/activity/all?since=364>; rel="next"
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
`affecteduser` | string | User ID of the user that received this activity (always the same)
`subject` | string | Untranslated arbitrary subject (e.g. `'changed_self'`, `'changed_by'`)
`subjectparams` | array | Array with one array per parameter, each parameter array has the key `'type'` and `'value'`. Value is an array-of-parameters in case of type `collection` and a string otherwise
`subject_prepared` | string | Translated version of the subject with parameters included (See section `MarkUp`) (e.g. `You deleted <file link="/files/index.php?dir=%2F&scrollto=welcome.txt">welcome.txt</file>`)
`message` | string (Optional) | Untranslated arbitrary message (unused by core apps)
`messageparams` | array | See `subjectparams`
`message_prepared` | string | Translated version of the message with parameters included (See section `MarkUp`)
`link` | string (Optional) | A full URL pointing to a suitable location (e.g. `'http://localhost/ownCloud/master/core/index.php/apps/files/?dir=%2Ffolder'` in case the folder got created)
`object_type` | string (Optional) | Type of the object this activity is about (e.g. `'files'` is used for files and folders)
`object_id` | int (Optional) | ID of the object this activity is about (e.g. ID in the file cache is used for files and folders)
`object_name` | string (Optional) | Name of the object this activity is about (e.g. for files it's the relative path to the user's root: `'/folder/.travis.yml'`)
`activity_ids` | array of int (Optional) | Array of `activity_id`s that have been grouped into this activity

In case the endpoint returns more fields, they should be ignored and are deprecated (only for backwards compatibility usage) or internal.

### Example

```json
  {
    "activity_id": 1,
    "timestamp": 1446137950,
    "app": "files",
    "type": "file_deleted",
    "user": "test1",
    "affecteduser": "admin",
    "subject": "deleted_self",
    "subjectparams": [
      {
        "value": "\/welcome.txt",
        "type": "file"
      }
    ],
    "subject_prepared" : "You deleted <file link=\"\/files\/index.php?dir=%2F&scrollto=welcome.txt\" id=\"3\">welcome.txt<\/file>",
    "message": "",
    "messageparams": [
      
    ],
    "message_prepared" : "",
    "link": "",
    "object_type": "files",
    "object_id": 3,
    "object_name": "\/welcome.txt"
  }
```

## MarkUp

Element | Desciption
------- | ----------
`file` | File should be translated into a link on the file system/web UI
`user` | User should be presented with display name (with avatar)
`federated-cloud-id` | Federated Clout User should be presented with display name
`parameter` | Parameter with no given type, should be presented as is
`collection` | A collection of other parameters, should present the first X elements and then display " and `(#-X)` more"

### file

`<file link="/files/index.php?dir=%2F&scrollto=welcome.txt" id="3">welcome.txt</file>`

Attribute | Desciption
--------- | ----------
body | Full path to the file
`link` | Link where the item can currently be viewed
`id` | File id (if known, otherwise emtpy string)

### user

`<user display-name="Administrator">admin</user>`

Attribute | Desciption
--------- | ----------
body | User id
`display-name` | Display name of the user

### federated-cloud-id

`<federated-cloud-id display-name="Administrator of Example" user="admin" server="example.com">admin@example.com</federated-cloud-id>`

Attribute | Desciption
--------- | ----------
body | Federated cloud id
`display-name` | Display name of the user
`user` | User name
`server` | Server address

### parameter

`<parameter>string</parameter>`

Attribute | Desciption
--------- | ----------
body | String to use

### collection

`<collection><file link="/files/index.php?dir=%2F&scrollto=welcome.txt" id="3">welcome.txt</file><file link="/files/index.php?dir=%2F&scrollto=test.txt" id="4">test.txt</file></collection>`

Attribute | Desciption
--------- | ----------
body | Elements to display

