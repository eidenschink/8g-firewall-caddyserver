<?php
/**
 * Transform the original 8G-Firewall.txt file for Apache
 * into Caddy maps.
 *
 * Tested with 8G FIREWALL v1.3 20240222 (see https://perishablepress.com/8g-firewall/)
 * 
 */

$firewall = file_get_contents('8G-Firewall.txt');

$version     = get_version($firewall);
$bad_query   = get_conditions('QUERY STRING', $firewall);
$bad_uri     = get_conditions('REQUEST URI', $firewall);
$bad_agent   = get_conditions('USER AGENT', $firewall);
$bad_host    = get_conditions('REMOTE HOST', $firewall);
$bad_referer = get_conditions('HTTP REFERRER', $firewall);
$bad_cookie  = get_conditions('HTTP COOKIE', $firewall);

echo sprintf(
    '
# to be used as a Caddy snippet via import
# see https://caddyserver.com/docs/caddyfile/directives/import

%s
# @ https://perishablepress.com/8g-firewall/',
    $version
) . PHP_EOL . PHP_EOL;

echo prepare_patterns_from_rewritecond($bad_query, 'QUERY_STRING', 'query') . PHP_EOL . PHP_EOL;
echo prepare_patterns_from_rewritecond($bad_uri, 'REQUEST_URI', 'path') . PHP_EOL . PHP_EOL;
echo prepare_patterns_from_rewritecond($bad_agent, 'HTTP_USER_AGENT', 'header.user-agent') . PHP_EOL . PHP_EOL;
echo prepare_patterns_from_rewritecond($bad_host, 'REMOTE_HOST', 'remote_host') . PHP_EOL . PHP_EOL;
echo prepare_patterns_from_rewritecond($bad_referer, 'HTTP_REFERER', 'header.referer') . PHP_EOL . PHP_EOL;
echo prepare_patterns_from_rewritecond($bad_cookie, 'HTTP_COOKIE', 'header.cookie') . PHP_EOL . PHP_EOL;
echo '@bad_request_method_8g method CONNECT DEBUG TRACE MOVE TRACK' . PHP_EOL . PHP_EOL;

/**
 * Search for and return the firewall version indicator line.
 *
 * @param string $content
 * @return string
 */
function get_version(&$content)
{
    foreach (explode("\n", $content) as $line) {
        if (false !== stripos($line, '# 8G FIREWALL v')) {
            return $line;
        }
    }
    return '';
}

/**
 * Look for an return certain rule sets
 *
 * @param string $section a section name indicator like "QUERY STRING"
 * @param string $content the rule block that is part of the ifModule mod_rewrite condition.
 * @return void
 */
function get_conditions($section, &$content)
{
    if (1 === preg_match('/(# 8G:\[' . $section . '\]\s<IfModule mod_rewrite.c>)(.*?)(<\/IfModule>)/ms', $content, $matches)) {
        return $matches[2] ?? null;
    }
    return null;
}

/**
 * Prepare a ruleset block suitable to be read by Caddy.
 *
 * Note that some patterns might be patched, changed, excluded to work
 * with the Golang regular expression flavour. Suggestions welcome.
 *
 * @param string $line
 * @param string $section
 * @param string $caddy_server_var
 * @return string
 */
function prepare_patterns_from_rewritecond($line, $section, $caddy_server_var)
{
    if (empty($line)) {
        return '';
    }
    $mapping_variable_name = str_replace(' ', '_', strtolower($section));
    $_line                 = preg_replace(
        array(
            '/RewriteCond %{' . $section . '}/',
            '/\[NC,OR\]/',
            '/\[NC\]/',
        ),
        '',
        $line
    );

    /**
     * @todo Change or exclude patterns that cause problems
     * until a pattern that can be compiled is crafted.
     */
    $_line = str_replace(['{4000,}', '{2000,}'], '{1000,}', $_line); // query
    $_line = str_replace('(\{0\}', '# (\{0\}', $_line); // request
    /**
     * @todo Backtick is read by Caddy, but flagged by regex101.com Golang flavour.
     * Who is right? And more importantly, is the following transformation matching
     * in the intended way?
     */
    $_line = str_replace('`', '\x60', $_line); // found in QUERY STRING + REQUEST URI

    $_lines = array_map(
        function ($row) {
            return trim($row);
        },
        preg_split('/\r\n|\r|\n/', $_line)
    );
    $rule   = 10;
    $out    = '';
    foreach ($_lines as $row) {
        if (!empty($row) && $row[0] === '(') {
            $out .= '~(?i)' . $row . ' "' . $rule . '"' . PHP_EOL;
            $rule++;
        }
    }
    if ($out) {
        return 'map {' . $caddy_server_var . '} {bad_' . $mapping_variable_name . '_8g} {' . PHP_EOL . $out . PHP_EOL . 'default "0"' . PHP_EOL . '}';
    }
    return '';
}
