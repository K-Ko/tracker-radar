#!/bin/bash
#
# Transform domain data into blocklist format for Pi-hole
# https://pi-hole.net/
# 
# - Read all domain files
# - Pipe through pi-hole.php
# - Save as Pi-hole compatible blocklist
#

# Check for predefine PHP binary
: ${PHP:=$(which php}

pwd=$(dirname $(readlink -f $0))

ls -1 $pwd/../domains \
while read file; do
    $PHP $pwd/pi-hole.php $file
done > $pwd/pihile.txt
