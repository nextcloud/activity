# Nextcloud Activity App

[![REUSE status](https://api.reuse.software/badge/github.com/nextcloud/activity)](https://api.reuse.software/info/github.com/nextcloud/activity)

This app allows people to see actions related to their files and data in Nextcloud.
Each person can configure their individual activity settings in the personal settings,
to choose which activity should be pushed to mobile phones, send via email
or whether a daily summary is sent each morning.

## 🏗 Development setup
1. ☁ Clone this app into the `apps` folder of your Nextcloud: `git clone https://github.com/nextcloud/activity.git`
2. 🛠️ Run `make dev-setup` to install the development dependencies
3. ✅ Enable the app through the app management of your Nextcloud
4. 🏗️ Make your changes
5. 🤖 Compile the frontend with `make build-js-production`
6. 🚀 Send your pull request

## API for other apps to interact with activities

In Nextcloud 11 the old extension API was replaced by 3 new interfaces, to better split the functionality up. Documentation for each of those can be found in the `docs/` folder:

* [Create](docs/create.md) - an activity and store it in the app
* [Provider](docs/provider.md) - translate and render activities
* [Setting](docs/setting.md) - allow users to control what they want to see in their stream or mail
* [Filter](docs/filter.md) - allow to reduce the stream in the web UI by app or setting
