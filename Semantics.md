# NGINX server variables and their replacement

* [alphabetical list of NGINX server variables](http://NGINX.org/en/docs/varindex.html)
* [placeholder list of Caddyserver http module](https://caddyserver.com/docs/modules/http)

For the maps and the regex patterns to work as originally intended it is necessary to use a correct Caddyserver equivalent.

## Caddyserver test

When including the `7g-debug.snippet` this request:

```
curl -H "Referer: https://developer.mozilla.org/en-US/docs/Web/JavaScript" -I https://localhost/some/path/index.php\?foo\=bar\&bar\=encodedampersand%26
```

results in

```
x-method: HEAD
x-query: foo=bar&bar=encodedampersand%26
x-referer: https://developer.mozilla.org/en-US/docs/Web/JavaScript
x-uri: /some/path/index.php?foo=bar&bar=encodedampersand%26
x-useragent: curl/7.64.1
```

### NGINX $query_string

[NGINX $query_string](http://nginx.org/en/docs/http/ngx_http_core_module.html#var_query_string) says _arguments in the request line_

Replaced in the snippet for Caddyserver by **{query}**

### NGINX $request_uri

* Clarify "request uri"? E.g. [rfc](https://www.rfc-editor.org/rfc/rfc3986#section-3) path+query+fragment or scheme+authority+path+query-fragment? (it is path+query+fragment)
* in NGINX $request_uri is not normalized (as opposed to NGINX $uri = _current URI in request, normalized_)

[NGINX $request_uri](https://nginx.org/en/docs/http/ngx_http_core_module.html) (no direct link) says _full original request URI (with arguments)_

Replaced in the snippet for Caddyserver by  **{uri}**

### NGINX $http_user_agent

[NGINX $http_user_agent](http://nginx.org/en/docs/http/ngx_http_core_module.html#variables) (via Embedded variables)

Replaced in the snippet for Caddyserver by **{header.user-agent}**

### NGINX $http_referer

[NGINX $http_referer](http://nginx.org/en/docs/http/ngx_http_core_module.html#variables) (via Embedded variables)

Replaced in the snippet for Caddyserver by **{header.referer}**

### NGINX $request_method

[NGINX $request_method](http://nginx.org/en/docs/http/ngx_http_core_module.html#variables) (via Embedded variables) says _request method, usually “GET” or “POST”_

Replaced in the snippet for Caddyserver by **{method}**
