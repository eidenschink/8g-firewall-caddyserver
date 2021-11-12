# Apache mod_rewrite variables and their replacement

* [mod_rewrite Documentation](https://httpd.apache.org/docs/2.4/mod/mod_rewrite.html)
* [placeholder list of Caddyserver http module](https://caddyserver.com/docs/modules/http)

For the maps and the regex patterns to work as originally intended it is necessary to use a correct Caddy equivalent.

## Caddy test

When including the `7g-debug.snippet` this request:

```
curl -H "Referer: https://developer.mozilla.org/en-US/docs/Web/JavaScript" -I https://localhost/some/path/index.php\?foo\=bar\&bar\=encodedampersand%26
```

results in (something like)

```
x-method: HEAD
x-path: /some/path/index.php
x-query: foo=bar&bar=encodedampersand%26
x-referer: https://developer.mozilla.org/en-US/docs/Web/JavaScript
x-uri: /some/path/index.php?foo=bar&bar=encodedampersand%26
x-useragent: curl/7.64.1
```

### %{QUERY_STRING}

Replaced in the snippet for Caddy by **{query}**

### %{REQUEST_URI}

Replaced in the snippet for Caddy by **{path}**

We should really work here with only the path, no query or even the fragment.

### %{HTTP_USER_AGENT}

Replaced in the snippet for Caddy by **{header.user-agent}**
### %{HTTP_REFERER}

Replaced in the snippet for Caddy by **{header.referer}**
### %{REMOTE_HOST}

Replaced in the snippet for Caddy by **{remote_host}**

### %{REQUEST_METHOD}
Replaced in the snippet for Caddy by **{method}**



