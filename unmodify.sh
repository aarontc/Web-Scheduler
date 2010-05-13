#!/bin/bash

sed -n '
# if the first line copy the pattern to the hold buffer
1h
# if not the first line then append the pattern to the hold buffer
1!H
# if the last line then ...
$ {
        # copy from the hold to the pattern buffer
        g
        # do the search and replace
        s/\#TRASHBEGIN.*\#TRASHEND//g
        # print
        p
}
' /etc/shorewall/rules > /tmp/shorewall.rules.new

mv /etc/shorewall/rules /etc/shorewall/rules.old
mv /tmp/shorewall.rules.new /etc/shorewall/rules
echo -n "unmodified" > /home/trash/trash.state

shorewall restart
