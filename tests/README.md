# Disclaimer

This is not some kind of unit test nor is it comprehensive.

The idea is to run Caddy with the provided Caddyfile, run test `test-maps.sh` file in another console
and see what rule does match or not, so that you can test your
own patterns or maybe fix existing rule patterns.

## Example

```
# Console A:
caddy run

# Console B:
./test-maps.sh

```
...
2024/02/16 03:12:23.739	ERROR	http.log.access.log0	handled request	{"request": {"remote_ip": "::1", "remote_port": "52540", "client_ip": "::1", "proto": "HTTP/2.0", "method": "GET", "host": "localhost", "uri": "/", "headers": {"Accept": ["*/*"], "User-Agent": ["fwdebug"], "Referer": ["https://example.com/?arg=ORDER%20BY%201--"]}, "tls": {"resumed": false, "version": 772, "cipher_suite": 4865, "proto": "h2", "server_name": "localhost"}}, "bytes_read": 0, "user_id": "", "duration": 0.000128303, "size": 11, "status": 403, "resp_headers": {"Alt-Svc": ["h3=\":443\"; ma=2592000"], "X-Firewall-Query": ["0"], "X-Firewall-Badrequest": ["0"], "X-Firewall-Baduseragent": ["0"], "X-Firewall-Badhost": ["0"], "X-Firewall-Badreferer": ["10"], "Content-Type": ["text/plain; charset=utf-8"], "Server": ["Caddy"]}}
...
```

In the "resp_headers" array you can see rule number "10" of the Referer rules matched.
