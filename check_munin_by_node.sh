#!/bin/bash
if [[ "$1" == "" ]]
then
	echo 'no arguments passed'
	exit 3
fi
if [[ `grep -c -F "$1" /tmp/problems-critical.html` -ne 0 ]]
then
	echo 'CRITICAL'
	exit 2
fi
if [[ `grep -c -F "$1" /tmp/problems-warning.html` -ne 0 ]]
then
        echo 'WARNING'
        exit 1
fi
if [[ `grep -c -F "$1" /tmp/problems-unknown.html` -ne 0 ]]
then
        echo 'UNKNOWN'
        exit 3
fi
echo 'OK'
exit 0
