#!/bin/bash
#
# ownCloud
#
# @author Thomas Müller
# @copyright 2014 Thomas Müller thomas.mueller@tmit.eu
#
mysql -e 'create database oc_autotest;'
mysql -u root -e "CREATE USER 'oc_autotest'@'localhost' IDENTIFIED BY 'owncloud'";
mysql -u root -e "grant all on oc_autotest.* to 'oc_autotest'@'localhost'";

