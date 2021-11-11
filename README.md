# 7g-firewall-caddyserver
An attempt to port the great 7G Firewall from Jeff Star to Caddyserver.

**This is currently work in progress and should not be used in production.**

At the time being the current work is based on version 1.4 of the firewall file. Originally I tried to work with the NGINX version as it already was based on a *map* directive but some patterns seemed to be different so I switched to the original file.

## Prerequisite

For the map directive to work you need the latest [Caddyserver](https://github.com/caddyserver/caddy/releases/tag/v2.4.6).

## How to use

Take a look at the example _Caddyfile_. It imports a snippet `examples/7g-caddy.snippet` that holds all the patterns and `examples/7g-enfoce.snippet` that aborts the connection with a 403 or 405 status code.

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
- [x] provide a HTTP status code 403 snippet that matches (is triggered by) the mapped variables

### License

See https://perishablepress.com/7g-firewall/#license

### Disclaimer

See https://perishablepress.com/7g-firewall/#disclaimer
