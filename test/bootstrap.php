<?php

$found = false;
$dir = dirname( __FILE__ );
while ( !$found ) {
	$tmp = $dir . '/vendor/autoload.php';
	if ( file_exists( $tmp ) ) {
		require_once $tmp;
		$found = true;
	}
	else {
		$dir = dirname( $dir );
	}
}

