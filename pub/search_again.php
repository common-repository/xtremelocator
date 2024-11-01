<?php
/*if ( isset( $_GET['lid'] ) ) {
	$a = explode( "&", $_SERVER["REQUEST_URI"] );
	if ( count( $a ) > 2 ) {
		$b = $a[0] . '&' . $a[2];
	} else {

	}
} else {
	$p = strrpos( $_SERVER["REQUEST_URI"], "?" );
	if ( $p > 0 ) {
		$b = substr( $_SERVER["REQUEST_URI"], 0, strrpos( $_SERVER["REQUEST_URI"], "?" ) );
	} else {
		$b = $_SERVER["REQUEST_URI"];
	}
}*/
$search_again_url = add_query_arg( array(
	'reset_search' => 'true',

) );

?>
<form method="post" action="<?php echo ADMIN_POST_URL?>"><input type="hidden" name="action"value="xtreme_locator"> <input type="submit" name="reset_xl_search" value="search again"></form>
