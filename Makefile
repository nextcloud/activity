# Makefile for building the project

app_name=activity

project_dir=$(CURDIR)/../$(app_name)
build_dir=$(CURDIR)/build/artifacts
appstore_dir=$(build_dir)/appstore
source_dir=$(build_dir)/source
sign_dir=$(build_dir)/sign
package_name=$(app_name)
cert_dir=$(HOME)/.nextcloud/certificates
version+=master

all: dev-setup

compile-handlebars-templates: dev-setup
	bash compile-handlebars-templates.sh

dev-setup:
	echo "Done"
