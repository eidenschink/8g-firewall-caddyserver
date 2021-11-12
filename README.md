# 7g-firewall-caddyserver
An attempt to port the great 7G Firewall from Jeff Star to Caddy.

**This is currently work in progress and should not be used in production.**

At the time being the current work is based on version 1.5 of the firewall file (Apache version).

## Prerequisite

For the map directive to work you need release 2.4.6 or greater: [Caddy](https://github.com/Caddy/caddy/releases/tag/v2.4.6).

## How to use

Take a look at the example _Caddyfile_. It imports a snippet `examples/7g-caddy.snippet` that holds all the patterns and `examples/7g-enfoce.snippet` that aborts the connection with a 403 or 405 status code.

If you want to work with the latest 7G-Firewall.txt file from the original author:

The PHP script `transform.php` expects the 7G-Firewall.conf (tested is version 1.5) and simply applies some regular expressions and string replacements. The result is a Caddy snippet that can be imported where needed. See the example _Caddyfile_.

1. Go to the [7G Firewall](https://perishablepress.com/7g-firewall/#download) page and download und unzip the ZIP file.
2. Run the `php transform.php` script after copying the _7G-Firewall.txt_ file of the ZIP archive into the same directory, redirect output to a file (see below)

```
# copy 7G-Firewall.txt into same directory as the PHP script
php transform.php > 7g-caddy.snippet
```

## Todo and bugs

- [ ] find an equivalent for two patterns that either not compile or differ in regex flavor/nuances (see comments in transform.php)
- [ ] write tests for every section to make sure the patterns match what they are supposed to match (AFAIK there is currently no test suite for the rules)
- [x] provide a HTTP status code 403 snippet that matches (is triggered by) the mapped variables

**Variables:**
- [x] Use the equivalent of the respective mod_rewrite variable to the Caddy server variable (see _Semantics.md_).
- [ ] Verify the equivalent is in the same processed state of request normalization so that every pattern matches the intended payload


### License

See https://perishablepress.com/7g-firewall/#license

### Disclaimer

See https://perishablepress.com/7g-firewall/#disclaimer
