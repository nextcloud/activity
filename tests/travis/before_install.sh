#!/bin/bash
#
# ownCloud
#
# @author Thomas Müller
# @copyright 2014 Thomas Müller thomas.mueller@tmit.eu
#

WORKDIR=$PWD
echo "Work directory: $WORKDIR"
cd ..
git clone --depth 1 https://github.com/owncloud/core
cd core
git submodule update --init

cd apps
cp -R ../../activity/ .
cd $WORKDIR
