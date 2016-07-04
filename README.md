# Nextcloud Activity App

The activity app for Nextcloud

Provides an activity feed showing your file changes and other interesting things
going on in your Nextcloud.

## QA metrics on stable9 branch:

[![Build Status](https://travis-ci.org/nextcloud/activity.svg?branch=stable9)](https://travis-ci.org/nextcloud/activity/branches)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nextcloud/activity/badges/quality-score.png?b=stable9)](https://scrutinizer-ci.com/g/nextcloud/activity/?branch=stable9)
[![Code Coverage](https://scrutinizer-ci.com/g/nextcloud/activity/badges/coverage.png?b=stable9)](https://scrutinizer-ci.com/g/nextcloud/activity/?branch=stable9)

# Add new activities / types for other apps

With the activity manager extensions can be registered which allow any app to extend the activity behavior.

In order to implement an extension create a class which implements the interface `\OCP\Activity\IExtension`.

The PHPDoc comments on each method should give enough information to the developer on how to implement them.
