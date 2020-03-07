#!/bin/bash
#
# Transform domain data into blocklist format for Pi-hole
# https://pi-hole.net/
#
# - Read all domain files
# - Pipe through pi-hole.php
# - Save as Pi-hole compatible blocklist
#

# set -x

# Check for predefine PHP binary
: ${PHP:=$(which php)}

pwd=$(dirname $(readlink -f $0))

files=$(mktemp)
trap 'rm $files' 0

echo -n 'Analyse domains ... '

ls -1 $pwd/../domains/*.json > $files

echo $(wc -l < $files) files

$PHP $pwd/pi-hole.php $files | sort | uniq > $pwd/pi-hole.txt

echo Got $(wc -l < $pwd/pi-hole.txt) unique domain entries

cat $pwd/pi-hole.regex.list $pwd/pi-hole.txt | sudo tee /etc/pihole/regex.list >/dev/null

pihole -g
