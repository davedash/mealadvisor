#!/bin/bash

cd $(dirname $0)/..
mkdir static/images/menuitems
rsync -av davedash@wallace.mealadvisor.us:/a/static.mealadvisor.us/menuitems/ static/images/menuitems