<div id="<?php echo $css['xl_search_form']['class']; ?>">
	<?php if ( $conf->description != "" ) { ?>
        <div id="<?php echo $css['xl_form_message']['class']; ?>"><?php echo $conf->description; ?></div><?php } ?>
    <script type="text/javascript">if (document.getElementById('xl_pub_widget') != null) {
            document.getElementById("xl_pub_widget").remove()
        }
        </script>
    <form id="<?php echo $css['searchForm']['class']; ?>"
          action="<?php echo ADMIN_POST_URL; ?>" method="post"
          name="searchForm" onSubmit="checkZip()">
        <div class="<?php echo $css['xl_wraper']['class']; ?>">
			<?php if ( $conf->search_type == 0 ) { ?>
                <div class="<?php echo $css['xl_wraper']['class']; ?>">
                    <label for="xl_search_country"
                           id="xl_search_country_label"><?php print __( "Country", XL_TEXT_DOMAIN ); ?>:</label><select
                            id="XlocatorCountry" name="country" onChange="checkZip(this);">
                    </select>
                </div>
			<?php } ?>
            <label for="XlocatorZip" id="xl_search_zip_label"><?php print __( "Zip/Postal Code", XL_TEXT_DOMAIN ); ?>
                :</label><input type="text" name="zip" id="XlocatorZip"
                                value="<?php echo isset( $_SESSION["xl_search"]['zip'] ) ? $_SESSION["xl_search"]['zip'] : "";
			                    if ( $conf->search_type == 1 ) {
				                    echo isset( $_SESSION["xl_search"]['city'] ) ? $_SESSION["xl_search"]['city'] : "";
			                    } ?>"/>
        </div>
		<?php if ( $conf->search_type == 0 ) { ?>
            <script type="text/javascript"
                    src="https://app.xtremelocator.com/visitor/searchByCountryJS.php?siteId=<?php echo $gconf->site_id; ?>"></script>
		<?php } ?>
		<?php if ( $conf->search_type == 1 ) { ?>
            <input type="hidden" name="city" id="xl_search_city"
                   value="<?php echo isset( $_SESSION["xl_search"]['city'] ) ? $_SESSION["xl_search"]['city'] : ""; ?>"/>
            <input type="hidden" name="type" id="xl_search_city" value="1"/>
		<?php } ?>
		<?php if ( $conf->search_type == 2 ) { ?>
            <div class="<?php echo $css['xl_wraper']['class']; ?>">
                <label for="xl_search_distance"
                       id="xl_search_distance_label"><?php print __( "Distance", XL_TEXT_DOMAIN ); ?>:</label><input
                        type="text" name="distance" id="xl_search_distance"
                        value="<?php echo isset( $_SESSION["xl_search"]['distance'] ) ? $_SESSION["xl_search"]['distance'] : ""; ?>"/>
            </div>
		<?php } ?>
        <input type="hidden" name="action" value="xtreme_locator">
        <?php global $wp;
        $current_url = home_url(add_query_arg(array($_GET), $wp->request));
        ?>
        <input type="hidden" name="url" value="<?php echo $current_url ?>">
        <input type="hidden" name="url_hash" value="<?php echo sha1($current_url.XTREME_LOCATOR_SALT);?>">
        <input type="hidden" name="option" value="com_xtremelocator"/>
        <input type="hidden" name="view" value="search"/>
		<?php if ( isset( $_GET['cat'] ) ) {
			echo '<input type="hidden" name="category" value="' . str_replace( '_', ' ', $_GET['cat'] ) . '"/>';
		} ?>
        <input type="submit" value="<?php print __( "Search", XL_TEXT_DOMAIN ) ?>" id="xl_search_submit"/>
		<?php if ( $conf->show_advanced_link == 1 ) {
			echo '<div id="' . $css['xl_advanced_search_link']['class'] . '"><a href="' . '?search_type=advanced' . ( isset( $_GET['page_id'] ) ? "&page_id=" . $_GET['page_id'] : "" ) . ( isset( $_GET['p'] ) ? "&p=" . $_GET['p'] : "" ) . '">' . __( "Advanced search", XL_TEXT_DOMAIN ) . '</a> </div>';
		} ?>
		<?php if ( $conf->show_all_location_link == 1 ) {
			echo '<div id="' . $css['xl_all_locations_link']['class'] . '"><a href="' . '?search_type=listing' . ( isset( $_GET['page_id'] ) ? "&page_id=" . $_GET['page_id'] : "" ) . ( isset( $_GET['p'] ) ? "&p=" . $_GET['p'] : "" ) . '">' . __( "All locations", XL_TEXT_DOMAIN ) . '</a> </div>';
		} ?>
		<?php if ( $gconf->show_slogan == 1 ) {
			echo '<div id="xl_powered_link"><a href="https://www.xtremelocator.com">' . __( "Powered by", XL_TEXT_DOMAIN ) . '</a> </div>';
		} ?>
    </form>
</div>