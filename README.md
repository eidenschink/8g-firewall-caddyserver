# 7g-firewall-caddyserver
An attempt to port the great 7G Firewall from Jeff Star to Caddyserver. The attempt is made on the NGINX version, please see also the [thank you](https://perishablepress.com/7g-firewall-nginx/#thank-you) section on Jeffs page.

**This is currently work in progress and should not be used in production. There is no matcher that would actually abort any request.**

The NGINX version uses [a map directive](http://nginx.org/en/docs/http/ngx_http_map_module.html). There is also [a map directive available in Caddyserver](https://caddyserver.com/docs/caddyfile/directives/map) so the idea was to try to convert the rules file and work with the result.

At the time being the current work is based on version 1.4 of the firewall file.

## Prerequisite

For the map directive to work [you need to build the latest Caddyserver](https://caddyserver.com/docs/build). (Background see [Fix regex mappings](https://github.com/caddyserver/caddy/commit/95c035060f66577f52158312c75ca379d7ddc21e) and [this post](https://caddy.community/t/map-directive-and-regular-expressions/13866)).

## How to use

The script `transform-to-caddy.sh` expects the 7G-Firewall.conf NGINX release and simply applies sed-transformations on the content. The result is a Caddyserver snippet that can be imported where needed. See the example _Caddyfile_.

1. Go to the [7G Firewall for NGINX](https://perishablepress.com/7g-firewall-nginx/#download) page and download und unzip the ZIP file.
2. Run the `transform-to-caddy.sh` script and point it to the 7g-firewall.conf file of the ZIP archive, redirect output to a file (see below)
3. Edit the file and comment out line 2 and 9 of section bad_request_7g

```
chmod +x transform-to-caddy.sh
# ./transform-to-caddy.sh <location of 7g-firewall.conf>
# redirect the output to a new file like:
./transform-to-caddy.sh 7G-Firewall-Nginx-v1.4/7g-firewall.conf > 7g-caddy.snippet
```

## Todo and bugs

- [ ] work on rule 2 and 9 of section/map bas_request (currently a regex compile error)
- [ ] write tests for every section to make sure the patterns match what they are supposed to match (AFAIK there is currently no test suite for the rules)
- [ ] compare NGINX server variables with Caddyfile variables. Make sure they are semantically the same, don't differ in request normalization etc (See _Semantics.md_)
- [ ] provide a HTTP status code 403 snippet that matches (is triggered by) the _7g_fired_ variable

### License

See https://perishablepress.com/7g-firewall/#license

### Disclaimer

See https://perishablepress.com/7g-firewall/#disclaimer
