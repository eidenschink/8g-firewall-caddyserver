#!/bin/bash
#
# Simple script to test some patterns.
#
# If you use the provided Caddyfile with the included
# debug snippet you can see in the "resp_headers"
# output the exact rule of the firewall that matched.
#

HOST=$1

if [[ -z "$1" ]]; then
	# https on localhost is easy with Caddy.
	HOST=https://localhost
fi

echo -e "\nTesting GET requests\n"
while read -r line; do

	[[ "$line" == \#* ]] && continue
	[[ -z "$line" ]] && continue

    # Set the User-Agent to a non-default because "curl" is blocked by the firewall.
	HTTPCODE=$(curl -H "User-Agent: fwdebug" -s -w "%{http_code}" -o /dev/null "${HOST}${line}")
	case "$HTTPCODE" in
	"403") SETAF=1 ;;
	"200") SETAF=2 ;;
	*) SETAF=0 ;;
	esac
	echo -n "$(tput setaf $SETAF)${HTTPCODE}$(tput sgr0)"
	echo " $line"

done \
	<GET.test


echo -e "\nTesting URI requests\n"
while read -r line; do

	[[ "$line" == \#* ]] && continue
	[[ -z "$line" ]] && continue

    # Set the User-Agent to a non-default because "curl" is blocked by the firewall.
	HTTPCODE=$(curl -H "User-Agent: fwdebug" -s -w "%{http_code}" -o /dev/null "${HOST}${line}")
	case "$HTTPCODE" in
	"403") SETAF=1 ;;
	"200") SETAF=2 ;;
	*) SETAF=0 ;;
	esac
	echo -n "$(tput setaf $SETAF)${HTTPCODE}$(tput sgr0)"
	echo " $line"

done \
	<URI.test
    
echo -e "\nTesting COOKIE requests\n"
while read -r line; do

	[[ "$line" == \#* ]] && continue
	[[ -z "$line" ]] && continue

    # Set the User-Agent to a non-default because "curl" is blocked by the firewall.
	HTTPCODE=$(curl -H "User-Agent: fwdebug" -H "${line}" -s -w "%{http_code}" -o /dev/null "${HOST}")
	case "$HTTPCODE" in
	"403") SETAF=1 ;;
	"200") SETAF=2 ;;
	*) SETAF=0 ;;
	esac
	echo -n "$(tput setaf $SETAF)${HTTPCODE}$(tput sgr0)"
	echo " $line"

done \
	<COOKIE.test


echo -e "\nTesting Referer requests\n"
while read -r line; do

	[[ "$line" == \#* ]] && continue
	[[ -z "$line" ]] && continue

    # Set the User-Agent to a non-default because "curl" is blocked by the firewall.
	HTTPCODE=$(curl -H "User-Agent: fwdebug" -H "${line}" -s -w "%{http_code}" -o /dev/null "${HOST}")
	case "$HTTPCODE" in
	"403") SETAF=1 ;;
	"200") SETAF=2 ;;
	*) SETAF=0 ;;
	esac
	echo -n "$(tput setaf $SETAF)${HTTPCODE}$(tput sgr0)"
	echo " $line"

done \
	<REFERER.test
