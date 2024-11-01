<div id="<?php echo $css['xl_search_form']['class']; ?>">
	<?php if ( $conf->description != "" ) { ?>
        <div id="<?php echo $css['xl_form_message']['class']; ?>"><?php echo $conf->description; ?></div><?php } ?>
    <div id="xl_custom_form_code"><?php echo str_replace( "SITEID", $gconf->site_id, $conf->form_code ); ?></div>
	<?php if ( $conf->show_all_location_link == 1 ) {
		echo '<div id="' . $css['xl_all_locations_link']['class'] . '"><a href="' . '?search_type=listing' . ( isset( $_GET['page_id'] ) ? "&page_id=" . $_GET['page_id'] : "" ) . ( isset( $_GET['p'] ) ? "&p=" . $_GET['p'] : "" ) . '">' . __( "All locations", XL_TEXT_DOMAIN ) . '</a> </div>';
	} ?>
	<?php if ( $gconf->show_slogan == 1 ) {
		echo '<div id="xl_powered_link"><a href="https://www.xtremelocator.com">' . __( "Powered by", XL_TEXT_DOMAIN ) . '</a> </div>';
	} ?>
</div>