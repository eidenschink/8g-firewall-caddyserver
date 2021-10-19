#!/bin/bash
#
# Transform the Nginx firewall rules from
# https://perishablepress.com/7g-firewall-nginx/#download
# to a map and variables suitable for Caddy server.
#
# Tested with version 1.4 of the original authors file.
# Do not use the result in production.
#

if [[ "${1}x" == "x" ]]; then
    cat <<USAGE

This script will replace variable names and directives
from the original 7g-firewall.conf file for nginx
to a configuration suitable for Caddy server

Print result:
$0 <path to 7g-firewall.conf file>

Redirect output to file:
$0 <path to 7g-firewall.conf file> > 7g-caddy.snippet

** Before including the generated output: **
Comment out line 2 and 9 of section bad_request_7g

USAGE
    exit
fi

echo -e "\n# to be used as a Caddy snippet via import\n# see https://caddyserver.com/docs/caddyfile/directives/import\n"

cat "$1" | sed -r '

    s/\$query_string/\{query\}/
    s/\$bad_querystring_7g/\{bad_querystring_7g\}/
    # fix of the "invalid repeat count" @todo correct approach?
    s/\[a-z0-9\]\{2000,\}/[a-z0-9]{1000,}/

    s/map(.*) \{/map \1 \{7g_fired\} {/

    s/\$request_uri/\{uri\}/
    s/\$bad_request_7g/\{bad_request_7g\}/

    s/\$http_user_agent/\{header.user-agent\}/
    s/\$bad_bot_7g/\{bad_bot_7g\}/

    s/\$http_referer/\{header.referer\}/
    s/\$bad_referer_7g/\{bad_referer_7g\}/

    # Request method matching could be written as an elegant
    # oneliner with a named matcher in Caddy, e.g. like
    # @request_method method CONNECT DEBUG TRACE MOVE TRACK
    # but to follow the spirit of the original script we keep it.
    s/\$request_method/\{method\}/
    s/\$not_allowed_method_7g/\{not_allowed_method_7g\}/
    s/\"~\*\^\(([a-zA-Z]+)\)\" ([1-9]);/~(?i)\1 \2 1/

    # transforms most of the regexp lines
    s/\"~\*(.*)\" ([0-9]+);/~(?i)\1 \2 1/

    s/default 0;/default 0 0/

'


