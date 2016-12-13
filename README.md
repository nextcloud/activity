# Nextcloud Activity App

The activity app for Nextcloud

Provides an activity feed showing your file changes and other interesting things
going on in your Nextcloud.

## QA metrics on master branch:

[![Build Status](https://travis-ci.org/nextcloud/activity.svg?branch=master)](https://travis-ci.org/nextcloud/activity/branches)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nextcloud/activity/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nextcloud/activity/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/nextcloud/activity/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/nextcloud/activity/?branch=master)

## API for other apps to interact with activities

In Nextcloud 11 the old extension API was replaced by 3 new interfaces, to better split the functionality up. Documentation for each of those can be found in the `docs/` folder:

* [Create](docs/create.md) - an activity and store it in the app
* [Provider](docs/provider.md) - translate and render activities
* [Setting](docs/setting.md) - allow users to control what they want to see in their stream or mail
* [Filter](docs/filter.md) - allow to reduce the stream in the web UI by app or setting
