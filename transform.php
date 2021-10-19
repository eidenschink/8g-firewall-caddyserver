<?php
/**
 *
 * Transform the original 7G-Firewall.txt file for Apache
 * into Caddyserver maps.
 *
 * Tested with 7G FIREWALL v1.4 20210821 (see https://perishablepress.com/7g-firewall/)
 *
 */
$firewall = file_get_contents( '7G-Firewall.txt' );

$bad_query   = get_conditions( 'QUERY STRING', $firewall );
$bad_uri     = get_conditions( 'REQUEST URI', $firewall );
$bad_agent   = get_conditions( 'USER AGENT', $firewall );
$bad_host    = get_conditions( 'REMOTE HOST', $firewall );
$bad_referer = get_conditions( 'HTTP REFERRER', $firewall );

echo '
# to be used as a Caddy snippet via import
# see https://caddyserver.com/docs/caddyfile/directives/import

# 7G FIREWALL v1.4 20210821
# @ https://perishablepress.com/7g-firewall/' . PHP_EOL . PHP_EOL;

echo prepare_patterns_from_rewritecond( $bad_query, 'QUERY_STRING', 'query' ) . PHP_EOL . PHP_EOL;
echo prepare_patterns_from_rewritecond( $bad_uri, 'REQUEST_URI', 'uri' ) . PHP_EOL . PHP_EOL;
echo prepare_patterns_from_rewritecond( $bad_agent, 'HTTP_USER_AGENT', 'header.user-agent' ) . PHP_EOL . PHP_EOL;
echo prepare_patterns_from_rewritecond( $bad_host, 'REMOTE_HOST', 'remote_host' ) . PHP_EOL . PHP_EOL;
echo prepare_patterns_from_rewritecond( $bad_referer, 'HTTP_REFERER', 'header.referer' ) . PHP_EOL . PHP_EOL;
echo '@bad_request_method_7g method CONNECT DEBUG TRACE MOVE TRACK' . PHP_EOL . PHP_EOL;

function get_conditions( $section, &$content ) {
	if ( 1 === preg_match( '/(# 7G:\[' . $section . '\]\s<IfModule mod_rewrite.c>)(.*?)(<\/IfModule>)/ms', $content, $matches ) ) {
		return $matches[2] ?? null;
	}
	return null;
}

function prepare_patterns_from_rewritecond( $line, $section, $caddy_server_var ) {
	if ( empty( $line ) ) {
		return '';
	}
	$mapping_variable_name = str_replace( ' ', '_', strtolower( $section ) );
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
	$_line = str_replace( '{2000,}', '{1000,}', $_line ); // query
	$_line = str_replace( '(\{0\}', '# (\{0\}', $_line ); // request

	$_lines = array_map(
		function( $row ) {
			return trim( $row );
		},
		preg_split( '/\r\n|\r|\n/', $_line )
	);
	$rule   = 1;
	$out    = '';
	foreach ( $_lines as $row ) {
		if ( ! empty( $row ) && $row[0] === '(' ) {
			$out .= '~(?i)' . $row . ' ' . $rule . PHP_EOL;
			$rule++;
		}
	}
	if ( $out ) {
		return 'map {' . $caddy_server_var . '} {bad_' . $mapping_variable_name . '_7g} {' . PHP_EOL . $out . PHP_EOL . 'default 0' . PHP_EOL . '}';
	}
	return '';
}
