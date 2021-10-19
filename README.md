# 7g-firewall-caddyserver
An attempt to port the great 7G Firewall from Jeff Star to Caddyserver.

**This is currently work in progress and should not be used in production. There is no matcher that would actually abort any request.**

At the time being the current work is based on version 1.4 of the firewall file. Originally I tried to work with the NGINX version as it already was based on a *map* directive but some patterns seemed to be different so I switched to the original file.

## Prerequisite

For the map directive to work [you need to build the latest Caddyserver](https://caddyserver.com/docs/build). (Background see [Fix regex mappings](https://github.com/caddyserver/caddy/commit/95c035060f66577f52158312c75ca379d7ddc21e) and [this post](https://caddy.community/t/map-directive-and-regular-expressions/13866)).

## How to use

The `examples/` directory has a current snippet with mappings ready to be tested.

If you want to work with the latest 7G-Firewall.txt file from the original author:

The PHP script `transform.php` expects the 7G-Firewall.conf (tested is version 1.4) and simply applies some regular expressions and string replacements. The result is a Caddyserver snippet that can be imported where needed. See the example _Caddyfile_.

1. Go to the [7G Firewall](https://perishablepress.com/7g-firewall/#download) page and download und unzip the ZIP file.
2. Run the `php transform.php` script after copying the _7G-Firewall.txt_ file of the ZIP archive into the same directory, redirect output to a file (see below)

```
# copy 7G-Firewall.txt into same directory as the PHP script
php transform.php > 7g-caddy.snippet
```

## Todo and bugs

- [ ] fix two patterns that either not compile or differ in regex flavor/nuances
- [ ] write tests for every section to make sure the patterns match what they are supposed to match (AFAIK there is currently no test suite for the rules)
- [ ] compare Apache server variables with Caddyfile variables. Make sure they are semantically the same, don't differ in request normalization etc (Similarly to the _Semantics.md_ comparison with NGINX variables)
- [ ] provide a HTTP status code 403 snippet that matches (is triggered by) the mapped variables

### License

See https://perishablepress.com/7g-firewall/#license

### Disclaimer

See https://perishablepress.com/7g-firewall/#disclaimer
