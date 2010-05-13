#!/bin/bash

cat /etc/shorewall/rules /home/trash/dotrash.rules > /tmp/shorewall.rules.new
mv /etc/shorewall/rules /etc/shorewall/rules.old
mv /tmp/shorewall.rules.new /etc/shorewall/rules

echo -n "modified" > /home/trash/trash.state
shorewall restart
