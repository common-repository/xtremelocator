<?php
function xl_init_admin_menus() {

	add_menu_page( __( "XtremeLocator", XL_TEXT_DOMAIN ), __( "XtremeLocator", XL_TEXT_DOMAIN ), "manage_options", "xtreme-locator", "control_panel", XL_BASE . '/icons/icon-16-xtremelocator.png' );
	add_submenu_page( "xtreme-locator", __( "Settings", XL_TEXT_DOMAIN ), __( "Settings", XL_TEXT_DOMAIN ), "manage_options", "xtreme-locator-settings", "xl_settings" );
	add_submenu_page( 'xtreme-locator', __( "CSS styles", XL_TEXT_DOMAIN ), __( "Css styles", XL_TEXT_DOMAIN ), "manage_options", "xtreme-locator-css", 'xl_css_styles' );
	add_submenu_page( 'xtreme-locator', __( "Standard search", XL_TEXT_DOMAIN ), __( "Standard search", XL_TEXT_DOMAIN ), "manage_options", "xtreme-locator-standard-search", 'xl_standard_search' );
	add_submenu_page( 'xtreme-locator', __( "Advanced search", XL_TEXT_DOMAIN ), __( "Advanced search", XL_TEXT_DOMAIN ), "manage_options", "xtreme-locator-advanced-search", 'xl_advanced_search' );
	add_submenu_page( 'xtreme-locator', __( "Custom search", XL_TEXT_DOMAIN ), __( "Custom search", XL_TEXT_DOMAIN ), "manage_options", "xtreme-locator-custom-search", 'xl_custom_search' );
	add_submenu_page( 'xtreme-locator', __( "All Locations Map", XL_TEXT_DOMAIN ), __( "All location map", XL_TEXT_DOMAIN ), "manage_options", "xtreme-locator-all-locations", 'xl_all_location' );
	add_submenu_page( 'xtreme-locator', __( "Location listing", XL_TEXT_DOMAIN ), __( "Location listing", XL_TEXT_DOMAIN ), "manage_options", "xtreme-locator-listing", 'xl_location_listing' );
	add_submenu_page( 'xtreme-locator', __( "Location admin login", XL_TEXT_DOMAIN ), __( "Location admin login", XL_TEXT_DOMAIN ), "manage_options", "xtreme-locator-admin", 'xl_location_admin' );

}

function xl_uninstall() {
	global $wpdb;
	$fields = $wpdb->get_results( "DROP TABLE `" . $wpdb->prefix . "xtremelocator_config`, `" . $wpdb->prefix . "xtremelocator_css`, `" . $wpdb->prefix . "xtremelocator_fields`, `" . $wpdb->prefix . "xtremelocator_layouts`" );
}

function xl_add_admin_stylesheet() {

	print "<link rel='stylesheet' type='text/css' href='" . XL_BASE . "/admin.css'>\n";
}

function xl_add_public_stylesheet() {

	print "<link rel='stylesheet' type='text/css' href='" . XL_BASE . "/xtremelocator_pub.css'>\n";
}

function control_panel() {

	include_once( XL_PATH . "/views/control_panel.php" );
}

function getConfig( $cid ) {
	global $wpdb;
	$config = $wpdb->get_results( "SELECT * FROM `" . $wpdb->prefix . "xtremelocator_config` WHERE type=" . $cid );

	return $config[0];
}

function getAllFields() {
	global $wpdb;
	$fields = $wpdb->get_results( "SELECT * FROM `" . $wpdb->prefix . "xtremelocator_fields`" );

	return $fields;
}

function getfieldlayout( $fid, $type ) {
	global $wpdb;
	if ( ! isset( $_GET['sort_by'] ) || $_GET['sort_by'] == "field_name" ) {
		$sort_by = 'fl.field_name';
	} else {
		$sort_by = "layouts." . $_GET['sort_by'];
	}
	//echo "$type: SELECT * FROM " . $wpdb->prefix . "xtremelocator_fields AS fl LEFT JOIN " . $wpdb->prefix . "xtremelocator_layouts AS layouts ON fl.id=layouts.field_id WHERE layouts.layout_id='" . $fid . "' AND layouts.field_type=" . $type . " AND fl.enabled=1 ORDER BY fl.enabled DESC, " . $sort_by . " ASC ";
	$rows = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "xtremelocator_fields AS fl LEFT JOIN " . $wpdb->prefix . "xtremelocator_layouts AS layouts ON fl.id=layouts.field_id WHERE layouts.layout_id='" . $fid . "' AND layouts.field_type=" . $type . " AND fl.enabled=1 ORDER BY fl.enabled DESC, " . $sort_by . " ASC " );
	$a    = array();
	foreach ( $rows as $row ) {
		$a[ $row->id ] = $row;
	}
	$rows = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "xtremelocator_fields AS fl WHERE fl.id NOT IN (SELECT field_id from " . $wpdb->prefix . "xtremelocator_layouts AS layouts WHERE layouts.layout_id='" . $fid . "' AND layouts.field_type=" . $type . ") AND fl.enabled=1" );
	//var_dump("SELECT * FROM " . $wpdb->prefix . "xtremelocator_fields AS fl WHERE fl.id NOT IN (SELECT field_id from " . $wpdb->prefix . "xtremelocator_layouts AS layouts WHERE layouts.layout_id='" . $fid . "' AND layouts.field_type=" . $type . ") AND fl.enabled=1");

	foreach ( $rows as $row ) {
		$row->layout_id   = $fid;
		$row->field_type  = $type;
		$row->visible     = 0;
		$row->show_title  = 0;
		$row->field_order = 0;
		$row->lincable    = 0;
		$a[ $row->id ]    = $row;
	}

	return $a;
}

function saveConfigData( $results, $cid ) {
	global $wpdb;
	$fields = "";
	foreach ( $results as $name => $value ) {
		if ( is_countable( $value ) && count( $value ) == 1 && $name != 'lincable' && $name != 'field list' && $name != 'title' && $name != 'URLcode' ) {
			$fields .= " " . $name . '="' . $value . '",';
		}
	}
	if ( empty( $fields ) === false ) {
		$update_result = $wpdb->get_results( " UPDATE `" . $wpdb->prefix . "xtremelocator_config` SET " . substr( $fields, 0, - 1 ) . " WHERE type =" . $cid );
	}
}

function saveLayoutData( $results, $lid ) {
	global $wpdb;
	$visible    = $results['visible'];
	$show_title = $results['title'];
	$order      = $results['order'];
	unset( $results['visible'] );
	unset( $results['title'] );
	unset( $results['order'] );
	$r = $wpdb->get_results( "DELETE  FROM " . $wpdb->prefix . "xtremelocator_layouts WHERE layout_id=" . $lid );

	$fields = getAllFields();
	foreach ( $fields as $field ) {
		$wpdb->get_results( "INSERT INTO `" . $wpdb->prefix . "xtremelocator_layouts` ( `field_id` , `layout_id` , `type` ,  `visible` , `show_title` , `order` , `lincable` ) VALUES ( '" . $field->id . "', '" . $lid . "', '1', '" . ( isset( $visible[ '1_' . $lid . '_' . $field->id ] ) ? 1 : 0 ) . "', '" . ( isset( $show_title[ '1_' . $lid . '_' . $field->id ] ) ? 1 : 0 ) . "', '" . ( $order[ '1_' . $lid . '_' . $field->id ] > 0 ? $order[ '1_' . $lid . '_' . $field->id ] : $field->id ) . "', '" . ( $results['lincable'] == $field->id ? 1 : 0 ) . "' )" );
		$r = $wpdb->get_results( "INSERT INTO `" . $wpdb->prefix . "xtremelocator_layouts` ( `field_id` , `layout_id` , `type` ,  `visible` , `show_title` , `order` , `lincable` ) VALUES ( '" . $field->id . "', '" . $lid . "', '2', '" . ( isset( $visible[ '2_' . $lid . '_' . $field->id ] ) ? 1 : 0 ) . "', '" . ( isset( $show_title[ '2_' . $lid . '_' . $field->id ] ) ? 1 : 0 ) . "', '" . ( $order[ '2_' . $lid . '_' . $field->id ] > 0 ? $order[ '2_' . $lid . '_' . $field->id ] : $field->id ) . "', '" . ( $results['lincable'] == $field->id ? 1 : 0 ) . "' )" );

	}
	unset( $results['lincable'] );

	return $results;
}

function xl_settings() {
	global $wpdb;

	if ( isset( $_POST['field_action'] ) ) {
		if ( $_POST['field_action'] == "save" ) {
			if ( isset( $_POST['xl']['id'] ) ) {
				$update_result = $wpdb->get_results( "UPDATE `" . $wpdb->prefix . "xtremelocator_fields` SET `field_id2` = '" . $_POST['xl']['field_id2'] . "', field_name='" . $_POST['xl']['field_name'] . "' , display_name='" . $_POST['xl']['display_name'] . "' WHERE `" . $wpdb->prefix . "xtremelocator_fields`.`id` =" . $_POST['xl']['id'] . ";" );
			} else {
				$update_result = $wpdb->get_results( "INSERT INTO`" . $wpdb->prefix . "xtremelocator_fields` (id,field_id2,field_name,enabled,display_name) VALUES (NULL,'" . $_POST['xl']['field_id2'] . "', '" . $_POST['xl']['field_name'] . "',1,'" . $_POST['xl']['display_name'] . "');" );
			}
		}
	}

	if ( ( isset( $_GET['id'] ) || ( isset( $_POST['action'] ) && $_POST['action'] == "add_field" ) ) && ! isset( $_POST['field_action'] ) ) {
		if ( isset( $_GET['id'] ) ) {
			$field = $wpdb->get_results( "SELECT * FROM `" . $wpdb->prefix . "xtremelocator_fields` WHERE id=" . $_GET['id'] );

		}
		include_once( XL_PATH . "/views/add_field.php" );

	} else {
		if ( count( $_POST ) > 0 && isset( $_POST['action'] ) ) {
			if ( $_POST['action'] == "update" ) {
				$update_result = $wpdb->get_results( "UPDATE `" . $wpdb->prefix . "xtremelocator_config` SET `site_id` = '" . $_POST['xl']['site_id'] . "', domain='" . $_POST['xl']['domain'] . "', not_found='" . $_POST['xl']['not_found'] . "' WHERE `" . $wpdb->prefix . "xtremelocator_config`.`id` =1;" );

			} elseif ( $_POST['action'] == "remove_field" ) {
				if ( count( $_POST['remove'] > 0 ) ) {
					$ids           = implode( ',', array_keys( $_POST['remove'] ) );
					$update_result = $wpdb->get_results( "DELETE FROM " . $wpdb->prefix . "xtremelocator_fields WHERE id in (" . $ids . ")" );
				}
			} elseif ( $_POST['action'] == "update_states" ) {
				if ( count( $_POST['enabled'] ) > 0 ) {
					$keys = array_keys( $_POST['enabled'] );
				} else {
					$keys = array( 0 );
				}

				$update_result = $wpdb->get_results( " UPDATE `" . $wpdb->prefix . "xtremelocator_fields` SET enabled=0 WHERE id NOT IN (" . implode( ',', $keys ) . ")" );
				$update_result = $wpdb->get_results( " UPDATE `" . $wpdb->prefix . "xtremelocator_fields` SET enabled=1 WHERE id IN (" . implode( ',', $keys ) . ")" );

			}
		}
		$config = getConfig( 1 );
		$fields = getAllFields();
		include_once( XL_PATH . "/views/settings.php" );
	}
}

function xl_get_css() {
	global $wpdb;
	$css = $wpdb->get_results( "SELECT * FROM `" . $wpdb->prefix . "xtremelocator_css`" );

	return $css;
}

function xl_get_css_array() {
	global $wpdb;
	$css = $wpdb->get_results( "SELECT * FROM `" . $wpdb->prefix . "xtremelocator_css`" );
	$ff  = array();
	foreach ( $css as $field ) {
		$ff[ $field->name ]['name']  = $field->name;
		$ff[ $field->name ]['class'] = $field->class;
	}

	return $ff;
}

function xl_css_styles() {
	global $wpdb;
	//chmod(XL_PATH."/xtremelocator_pub.css", 0777);
	if ( count( $_POST ) > 0 ) {
		foreach ( $_POST['xl'] as $nm => $css ) {
			$result = $wpdb->get_results( "UPDATE " . $wpdb->prefix . "xtremelocator_css SET `class` = '" . $css . "' WHERE `id` =" . $nm );
		}
		$cssFile = XL_PATH . "/xtremelocator_pub.css";
		$fh = fopen( $cssFile, 'w' ) or die( "can't open file" );
		$stringData = $_POST["css_source"];
		fwrite( $fh, $stringData );
		fclose( $fh );
	}
	$css = xl_get_css();
	include_once( XL_PATH . "/views/css_styles.php" );
}

function xl_standard_search() {
	global $wpdb;
	if ( count( $_POST ) > 0 ) {
		$post = saveLayoutData( $_POST, 2 );
		saveConfigData( $_POST, 2 );
	}
	$config  = $wpdb->get_results( "SELECT * FROM `" . $wpdb->prefix . "xtremelocator_config` WHERE type=1 OR type=2" );
	$row     = $config[1];
	$fields  = getfieldLayout( 2, 1 );
	$fields2 = getfieldLayout( 2, 2 );
	$afields = getAllFields();

	include_once( XL_PATH . "/views/standard_search.php" );
}

function xl_advanced_search() {
	global $wpdb;

	if ( count( $_POST ) > 0 ) {
		$post = saveLayoutData( $_POST, 3 );
		saveConfigData( $_POST, 3 );
	}
	$config  = $wpdb->get_results( "SELECT * FROM `" . $wpdb->prefix . "xtremelocator_config` WHERE type=1 OR type=3" );
	$row     = $config[1];
	$fields  = getfieldLayout( 3, 1 );
	$fields2 = getfieldLayout( 3, 2 );
	$afields = getAllFields();

	include_once( XL_PATH . "/views/advanced_search.php" );
}

function xl_custom_search() {
	global $wpdb;

	if ( count( $_POST ) > 0 ) {
		$post = saveLayoutData( $_POST, 6 );
		saveConfigData( $_POST, 6 );
	}
	$config  = $wpdb->get_results( "SELECT * FROM `" . $wpdb->prefix . "xtremelocator_config` WHERE type=1 OR type=6" );
	$row     = $config[1];
	$fields  = getfieldLayout( 6, 1 );
	$fields2 = getfieldLayout( 6, 2 );
	$afields = getAllFields();
	include_once( XL_PATH . "/views/custom_search.php" );
}

function xl_all_location() {
	global $wpdb;
	if ( count( $_POST ) > 0 ) {
		saveConfigData( $_POST, 5 );
	}
	$config       = $wpdb->get_results( "SELECT * FROM `" . $wpdb->prefix . "xtremelocator_config` WHERE type=1 OR type=5" );
	$row          = $config[1];
	$gobal_config = $config[0];
	include_once( XL_PATH . "/views/all_location.php" );
}

function xl_location_listing() {
	global $wpdb;
	if ( count( $_POST ) > 0 ) {
		$post = saveLayoutData( $_POST, 4 );
		saveConfigData( $_POST, 4 );
	}
	$config  = $wpdb->get_results( "SELECT * FROM `" . $wpdb->prefix . "xtremelocator_config` WHERE type=1 OR type=4" );
	$row     = $config[1];
	$fields  = getfieldLayout( 4, 1 );
	$fields2 = getfieldLayout( 4, 2 );
	$afields = getAllFields();
	include_once( XL_PATH . "/views/location_listing.php" );
}

function xl_location_admin() {
	global $wpdb;
	$config = $wpdb->get_results( "SELECT * FROM `" . $wpdb->prefix . "xtremelocator_config` WHERE type=1" );

	include_once( XL_PATH . "/views/location_admin.php" );
}

function makeList( $name, $elements, $active, $id = "", $extra = "" ) {
	$res = '<select name="' . $name . '" id="' . $id . '">';
	foreach ( $elements as $element => $value ) {
		$res .= '<option value="' . $value . '" ' . ( $value == $active ? 'selected="selected"' : '' ) . ' ' . $extra . '>' . $element . '</option>';
	}
	$res .= '</select>';

	return $res;
}

function getFields() {
	global $wpdb;
	$fields = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "xtremelocator_fields WHERE enabled=1" );
	$ff     = array();
	foreach ( $fields as $field ) {
		$ff[] = $field->field_name;
	}
	$ff[] = '_id';

	return $ff;
}

function getLayout( $fid, $type ) {
	global $wpdb;
	$rows   = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "xtremelocator_layouts AS layouts LEFT JOIN " . $wpdb->prefix . "xtremelocator_fields AS fl  ON fl.id=layouts.field_id WHERE layouts.layout_id='" . $fid . "' AND layouts.field_type=" . $type . " AND fl.enabled=1 AND layouts.visible=1 ORDER BY `layouts`.`field_order` ASC " );
	$fields = array();
	foreach ( $rows as $row ) {
		$fields[ $row->field_name ]                 = array();
		$fields[ $row->field_name ]['field_name']   = $row->field_name;
		$fields[ $row->field_name ]['display_name'] = $row->display_name;
		$fields[ $row->field_name ]['show_title']   = $row->show_title;
		$fields[ $row->field_name ]['lincable']     = $row->lincable;
	}

	return $fields;
}

function getCountries() {
	return array( " " => 0, "USA" => 1, "Canada" => 2 );
}

function getLocations( $fields, $type ) {
	global $wpdb;
	$config     = $wpdb->get_results( "SELECT * FROM `" . $wpdb->prefix . "xtremelocator_config` WHERE type=1 OR type=" . $type );
	$searchC    = $config[1];
	$sysFields  = array( 'option', 'view', 'jfcookie', 'Itemid', 'format' );
	$globalC    = $config[0];
	$allColumns = implode( ",", getFields() );

	$searchOptions = array(
		"sid"         => $globalC->site_id,
		"type"        => "advanced",
		"format"      => "CSV",
		"csv_columns" => $allColumns
	);


	foreach ( $_REQUEST as $k => $v ) {
		if ( ! in_array( $k, $sysFields ) ) {
			$searchOptions[ $k ] = $v;
		}

	}
	$fields = "";
	foreach ( $searchOptions as $name => $val ) {
		if ( strlen( $fields ) > 0 ) {
			$fields .= "&";
		}
		if ( $name == 'url' || $name === 'url_hash' ) {
			continue;
		}
		$fields .= $name . "=" . urlencode( $val );
	}
	//print_r($searchOptions);

	$curl = curl_init();

	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $curl, CURLOPT_HEADER, false );
	curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt( $curl, CURLOPT_URL, $globalC->domain . "/visitor/findLocations.php" );
	curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, false );
	curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $curl, CURLOPT_POSTFIELDS, $fields );
	curl_setopt( $curl, CURLOPT_POST, true );
	curl_setopt( $curl, CURLOPT_PROXY, '127.0.0.1:8888' );
	$response = curl_exec( $curl );

	//var_dump($response);
	$rows = explode( "\n", trim( $response ) );
	$r    = array();
	$ca   = explode( ",", $allColumns );
	foreach ( $rows as $row ) {
		if ( strstr( $row, '"' ) ) {
			$ce    = explode( ",", $row );
			$f     = array();
			$z     = array();
			$field = "";
			foreach ( $ce as $v ) {
				$field .= $v;
				if ( $field[0] == '"' && ( substr( $field, strlen( $field ) - 1, 1 ) != '"' || strlen( $field ) == 1 ) ) {
					$field .= ",";
				} else {
					$field = str_replace( "\"\"", "\"", $field );
					if ( $field[0] == '"' ) {
						$field = substr( $field, 1, strlen( $field ) - 2 );
					}
					$f[]   = $field;
					$field = "";
				}
			}
			foreach ( $f as $k => $v ) {
				$z[ ucwords( $ca[ $k ] ) ] = $f[ $k ];
			}
			$r[] = $z;
		}
	}
	error_log('setting : ');
	set_transient( get_transient_key( 'xl_results' ), $r, 60 );
	set_transient( get_transient_key( 'pos' ), 0, 60 );
	//$_SESSION["xl_results"] = $r;
	//$_SESSION["pos"]        = 0;
	//print_r($_SESSION["xl_results"]);
}

function shortcode_handler_function_standard( $attr, $t, $b ) {



	if ( isset( $_GET['search_type'] ) && $_GET['search_type'] == 'advanced' ) {
		$out1 = includeAdvancedSearch();
	} elseif ( isset( $_GET['search_type'] ) && $_GET['search_type'] == 'listing' ) {
		$out1 = includeLocationListing();
	} else {
		$out1 = includeStandardSearch();
	}


	ob_start();
	require_once( XL_PATH . "/pub/js.php" );
	$contents = ob_get_contents();
	ob_clean();

	return $contents . $out1;

}

function shortcode_handler_function_advanced( $attr, $t, $b ) {




	if ( isset( $_GET['search_type'] ) && $_GET['search_type'] == 'listing' ) {
		$out1 = includeLocationListing();
	} else {
		$out1 = includeAdvancedSearch();
	}
	ob_start();
	require_once( XL_PATH . "/pub/js.php" );
	$contents = ob_get_contents();
	ob_clean();

	return $contents . $out1;
}

function shortcode_handler_function_custom( $attr, $t, $b ) {



	if ( isset( $_GET['search_type'] ) && $_GET['search_type'] == 'listing' ) {
		$out1 = includeLocationListing();
	} else {
		$out1 = includeCustomSearch();
	}
	ob_start();
	require_once( XL_PATH . "/pub/js.php" );
	$contents = ob_get_contents();
	ob_clean();

	return $contents . $out1;
}

function shortcode_handler_function_all( $attr, $t, $b ) {



	if ( isset( $_GET['search_type'] ) && $_GET['search_type'] == 'advanced' ) {
		$out1 = includeAdvancedSearch();
	} elseif ( isset( $_GET['search_type'] ) && $_GET['search_type'] == 'listing' ) {
		$out1 = includeLocationListing();
	} else {
		$out1 = includeAllMap();
	}
	ob_start();
	require_once( XL_PATH . "/pub/js.php" );
	$contents = ob_get_contents();
	ob_clean();

	return $contents . $out1;
}

function shortcode_handler_function_listing( $attr, $t, $b ) {


	if ( isset( $_GET['search_type'] ) && $_GET['search_type'] == 'advanced' ) {
		$out1 = includeAdvancedSearch();
	} else {
		$out1 = includeLocationListing();
	}

	ob_start();
	require_once( XL_PATH . "/pub/js.php" );
	$contents = ob_get_contents();
	ob_clean();

	return $contents . $out1;
}


function includeLocator( $content ) {
	global $wpdb, $hide_widget;

	if ( preg_match( '|\[Xtreme-locator-standard|', $content ) ) {
		if ( isset( $_GET['search_type'] ) && $_GET['search_type'] == 'advanced' ) {
			$out1 = includeAdvancedSearch();
		} elseif ( isset( $_GET['search_type'] ) && $_GET['search_type'] == 'listing' ) {
			$out1 = includeLocationListing();
		} else {
			$out1 = includeStandardSearch();
		}

		return str_replace( "[Xtreme-locator-standard]", $out1, $content );
	} elseif ( preg_match( '|\[Xtreme-locator-advanced|', $content ) ) {
		if ( isset( $_GET['search_type'] ) && $_GET['search_type'] == 'listing' ) {
			$out1 = includeLocationListing();
		} else {
			$out1 = includeAdvancedSearch();
		}

		return str_replace( "[Xtreme-locator-advanced]", $out1, $content );
	} elseif ( preg_match( '|\[Xtreme-locator-custom|', $content ) ) {
		if ( isset( $_GET['search_type'] ) && $_GET['search_type'] == 'listing' ) {
			$out1 = includeLocationListing();
		} else {
			$out1 = includeCustomSearch();
		}

		return str_replace( "[Xtreme-locator-custom]", $out1, $content );
	} elseif ( preg_match( '|\[Xtreme-locator-listing|', $content ) ) {
		if ( isset( $_GET['search_type'] ) && $_GET['search_type'] == 'advanced' ) {
			$out1 = includeAdvancedSearch();
		} else {
			$out1 = includeLocationListing();
		}

		return str_replace( "[Xtreme-locator-listing]", $out1, $content );
	} elseif ( preg_match( '|\[Xtreme-locator-all|', $content ) ) {
		if ( isset( $_GET['search_type'] ) && $_GET['search_type'] == 'advanced' ) {
			$out1 = includeAdvancedSearch();
		} elseif ( isset( $_GET['search_type'] ) && $_GET['search_type'] == 'listing' ) {
			$out1 = includeLocationListing();
		} else {
			$out1 = includeAllMap();
		}

		return str_replace( "[Xtreme-locator-all]", $out1, $content );
	} else {

		return $content;
	}
}

function xtremelocator_form_parser() {

	if(isset($_POST['reset_xl_search'])) {
		//error_log('deleting');
		$status = delete_transient(get_transient_key('xl_results'));
		//var_dump($status);
		wp_safe_redirect( $_SERVER['HTTP_REFERER'] );
		exit;
	}
	error_log('step2');
	// Handle request then generate response using echo or leaving PHP and using HTML
	getLocations( $_REQUEST, 2 );
	if ( isset( $_POST['url'] ) ) {
		$hash = sha1( $_POST['url'] . XTREME_LOCATOR_SALT );
		if ( $hash === $_POST['url_hash'] ) {
			wp_redirect( $_POST['url'] );
			exit();
		}
	}

	if(isset($_REQUEST['reset_xl_search'])) {
		error_log('deleting xl_search');
		delete_transient(get_transient_key('xl_search'));
	}

	wp_safe_redirect( $_SERVER['HTTP_REFERER'] );
	exit;
}

function includeStandardSearch() {

	global $wpdb;
	$config          = $wpdb->get_results( "SELECT * FROM `" . $wpdb->prefix . "xtremelocator_config` WHERE type=1 OR type=2" );
	$conf            = $config[1];
	$gconf           = $config[0];
	$list_fields     = getLayout( 2, 1 );
	$detailed_fields = getLayout( 2, 2 );
	$css             = xl_get_css_array();


		if ( ! isset( $_GET['lid'] ) && ! isset( $_GET['pos'] ) ) {
			if ( isset( $_SESSION["xl_search"] ) ) {
				unset( $_SESSION["xl_search"] );
			}

		}


	if ( ! isset( $_SESSION["xl_search"]['zip'] ) || $_SESSION["xl_search"]['zip'] == "" ) {
		if ( isset( $_SESSION["xl_search"] ) ) {
			unset( $_SESSION["xl_search"] );
		}
	}

	ob_start();

	$data = get_transient( get_transient_key( 'xl_results' ) );

	if ( $data ) {
		if ( ( isset( $_GET['lid'] ) && $conf->show_form_details == 1 ) || ( ! isset( $_GET['lid'] ) && $conf->show_form_list == 1 ) ) {
			require( XL_PATH . "/pub/standard_search_form.php" );
		}
		require( XL_PATH . "/pub/standard_search_results.php" );
	} else {
		require( XL_PATH . "/pub/standard_search_form.php" );
	}
	$out1 = ob_get_contents();

	ob_end_clean();

	return $out1;
}

function includeAdvancedSearch() {
	global $wpdb;
	$config          = $wpdb->get_results( "SELECT * FROM `" . $wpdb->prefix . "xtremelocator_config` WHERE type=1 OR type=3" );
	$conf            = $config[1];
	$gconf           = $config[0];
	$list_fields     = getLayout( 3, 1 );
	$detailed_fields = getLayout( 3, 2 );
	$css             = xl_get_css_array();


		if ( ! isset( $_GET['lid'] ) && ! isset( $_GET['pos'] ) ) {
			if ( isset( $_SESSION["xl_search"] ) ) {
				unset( $_SESSION["xl_search"] );
			}

		}

	ob_start();

	$data = get_transient( get_transient_key( 'xl_results' ) );

	if ( $data ) {
		if ( ( isset( $_GET['lid'] ) && $conf->show_form_details == 1 ) || ( ! isset( $_GET['lid'] ) && $conf->show_form_list == 1 ) ) {
			require( XL_PATH . "/pub/advanced_search_form.php" );
		}
		require( XL_PATH . "/pub/advanced_search_results.php" );
	} else {
		require( XL_PATH . "/pub/advanced_search_form.php" );
	}
	$out1 = ob_get_contents();
	ob_end_clean();

	return $out1;
}

function includeCustomSearch() {
	global $wpdb;
	$config          = $wpdb->get_results( "SELECT * FROM `" . $wpdb->prefix . "xtremelocator_config` WHERE type=1 OR type=6" );
	$conf            = $config[1];
	$gconf           = $config[0];
	$list_fields     = getLayout( 6, 1 );
	$detailed_fields = getLayout( 6, 2 );
	$css             = xl_get_css_array();


		if ( ! isset( $_GET['lid'] ) && ! isset( $_GET['pos'] ) ) {
			if ( isset( $_SESSION["xl_search"] ) ) {
				unset( $_SESSION["xl_search"] );
			}

		}

	ob_start();
	$data = get_transient( get_transient_key( 'xl_results' ) );

	if ( $data ) {
		if ( ( isset( $_GET['lid'] ) && $conf->show_form_details == 1 ) || ( ! isset( $_GET['lid'] ) && $conf->show_form_list == 1 ) ) {
			require( XL_PATH . "/pub/custom_search_form.php" );
		}
		require( XL_PATH . "/pub/custom_search_results.php" );
	} else {
		require( XL_PATH . "/pub/custom_search_form.php" );
	}
	$out1 = ob_get_contents();
	ob_end_clean();

	return $out1;
}

function includeLocationListing() {
	global $wpdb;
	$config          = $wpdb->get_results( "SELECT * FROM `" . $wpdb->prefix . "xtremelocator_config` WHERE type=1 OR type=4" );
	$conf            = $config[1];
	$gconf           = $config[0];
	$list_fields     = getLayout( 4, 1 );
	$detailed_fields = getLayout( 4, 2 );
	$css             = xl_get_css_array();
	getLocations( $_REQUEST, 3 );
	ob_start();
	require( XL_PATH . "/pub/location_listing.php" );
	$out1 = ob_get_contents();
	ob_end_clean();

	return $out1;
}

function includeAllMap() {
	global $wpdb;
	$config = $wpdb->get_results( "SELECT * FROM `" . $wpdb->prefix . "xtremelocator_config` WHERE type=1 OR type=5" );
	$conf   = $config[1];
	$gconf  = $config[0];
	$css    = xl_get_css_array();
	getLocations( $_REQUEST, 5 );
	ob_start();
	require( XL_PATH . "/pub/all_map.php" );
	$out1 = ob_get_contents();
	ob_end_clean();

	return $out1;
}

function xl_register_widgets() {
	global $wpdb;
	wp_register_sidebar_widget( "10", __( "XtremeLocator search box", XL_TEXT_DOMAIN ), 'xl_search_widget', array( "classname" ) );
	wp_register_widget_control( "10", __( "XtremeLocator search box", XL_TEXT_DOMAIN ), "xl_standard_search_widget_init", 300, 300 );

}

function xl_search_widget() {
	global $wpdb;
	$url         = get_option( 'xl_widget_url' );
	$type        = get_option( 'xl_search_type' );
	$title       = get_option( 'xl_widget_title' );
	$description = get_option( 'xl_widget_desc' );
	$zip         = get_option( 'xl_widget_zip' );
	echo '<aside id="xl_pub_widget" class="widget  widget-container widget_xl"><h2 class="widget-title">' . $title . '</h2>';
	echo '<p id="xl_pub_widget_description">' . $description . '</p>';
	$config  = $wpdb->get_results( "SELECT * FROM `" . $wpdb->prefix . "xtremelocator_config` WHERE type=2" );
	$conf    = $config[0];
	$gconfig = $wpdb->get_results( "SELECT * FROM `" . $wpdb->prefix . "xtremelocator_config` WHERE type=1" );
	$gconf   = $gconfig[0];
	if ( $type == 1 ) {

		echo '<script type="text/javascript" > 
			function checkZipWidget(){									
					zip=document.getElementById("xl_pub_widget_zip");
					city=document.getElementById("xl_pub_widget_city");
					if(isNaN(zip.value)){
						city.value=zip.value;
						zip.value="";
					}						
			}</script>';

		echo '<form action="' . ADMIN_POST_URL . '" method="post" id="xl_pub_widget_form" onSubmit="checkZipWidget()">';
		if ( $conf->search_type == 0 ) {
			echo ' <div><label for="xl_search_country" id="xl_search_country_label">' . __( "Country", XL_TEXT_DOMAIN ) . ':</label><select id="XlocatorCountry" name="country" onChange="checkZip(this);">
          </select></div>';
		}
		echo '<div><label for="xl_pub_widget_zip" id="xl_pub_widget_zip_label">' . __( $zip, XL_TEXT_DOMAIN ) . ':</label><input type="text" id="XlocatorZip" class="xl_pub_widget_zip" name="zip"> <input type="hidden" name="city" id="xl_pub_widget_city"  value=""></div>';
		if ( $conf->search_type == 0 ) {
			echo ' <script type="text/javascript" src="https://app.xtremelocator.com/visitor/searchByCountryJS.php?siteId=' . $gconf->site_id . '"></script>';
		}
		if ( $conf->search_type == 2 ) {
			echo '<div><label for="xl_pub_search_distance" id="xl_pub_search_distance_label" >' . __( "Distance", XL_TEXT_DOMAIN ) . ':</label><input type="text" name="distance" id="xl_search_distance" value="' . isset( $_SESSION["xl_search"]['distance'] ) ? $_SESSION["xl_search"]['distance'] : "" . '" name="distance"/></div>';
		}
		echo '<input type="submit" value="' . __( "Search", XL_TEXT_DOMAIN ) . '" id="xl_pub_search_submit"></form>';
	} else {
		$config = $wpdb->get_results( "SELECT * FROM `" . $wpdb->prefix . "xtremelocator_config` WHERE type=3" );
		$conf   = $config[0];
		echo '<div id="xl_pub_advanced_form_code">' . $conf->form_code . '</div>';
	}
	echo '</aside>';
}

function xl_standard_search_widget_init() {
	global $wpdb;
	$url         = get_option( 'xl_widget_url' );
	$type        = get_option( 'xl_search_type' );
	$title       = get_option( 'xl_widget_title' );
	$description = get_option( 'xl_widget_desc' );
	$zip         = get_option( 'xl_widget_zip' );
	if ( count( $_POST ) > 0 ) {
		if ( isset( $_POST['xl_widget_url'] ) ) {
			update_option( 'xl_widget_url', $_POST['xl_widget_url'] );
		}
		if ( isset( $_POST['xl_search_type'] ) ) {
			update_option( 'xl_search_type', $_POST['xl_search_type'] );
		}
		if ( isset( $_POST['xl_widget_title'] ) ) {
			update_option( 'xl_widget_title', $_POST['xl_widget_title'] );
		}
		if ( isset( $_POST['xl_widget_desc'] ) ) {
			update_option( 'xl_widget_desc', $_POST['xl_widget_desc'] );
		}
		if ( isset( $_POST['xl_widget_zip'] ) ) {
			update_option( 'xl_widget_zip', $_POST['xl_widget_zip'] );
		}
	}
	echo __( "Search type", XL_TEXT_DOMAIN ) . ' <select name="xl_search_type"><option value="1" ' . ( $type == 1 ? 'selected="selected"' : "" ) . '>' . __( "Standard search", XL_TEXT_DOMAIN ) . '</option><option value="2" ' . ( $type == 2 ? 'selected="selected"' : "" ) . '>' . __( "Advanced search", XL_TEXT_DOMAIN ) . '</option></select><br/>';
	echo __( "Full search URL", XL_TEXT_DOMAIN ) . ' <br/><input type="name" name="xl_widget_url" value="' . $url . '"/><br/>';
	echo __( "Box title", XL_TEXT_DOMAIN ) . ' <br/><input type="name" name="xl_widget_title" value="' . $title . '"/><br/>';
	echo __( "Description", XL_TEXT_DOMAIN ) . ' <br/><textarea name="xl_widget_desc" cols="20" rows="5">' . $description . '</textarea><br/>';
	echo __( "Zip title", XL_TEXT_DOMAIN ) . ' <br/><textarea name="xl_widget_zip" cols="20" rows="5">' . $zip . '</textarea>';
}

function checkLocatorTag( $content ) {
	$url = get_option( 'xl_widget_url' );
	//if($url==""){
	if ( preg_match( '|\[Xtreme-locator-standard|', $content ) || preg_match( '|\[Xtreme-locator-advanced|', $content ) ) {
		update_option( 'xl_widget_url', get_permalink( $_POST['post_ID'] ) );
	}

	//}
	return $content;
}

function xl_install() {
	global $wpdb;
	$config_table_name  = $wpdb->prefix . "xtremelocator_config";
	$fields_table_name  = $wpdb->prefix . "xtremelocator_fields";
	$layouts_table_name = $wpdb->prefix . "xtremelocator_layouts";
	$css_table_name     = $wpdb->prefix . "xtremelocator_css";

	$sql[] = <<<SQL
DROP TABLE IF EXISTS $config_table_name;
SQL;
	$sql[] = <<<SQL
DROP TABLE IF EXISTS $css_table_name;
SQL;
	$sql[] = <<<SQL
DROP TABLE IF EXISTS $fields_table_name;
SQL;
	$sql[] = <<<SQL
DROP TABLE IF EXISTS $layouts_table_name;
SQL;

	$sql[] = <<<SQL
CREATE TABLE $config_table_name (
  id int(5) NOT NULL AUTO_INCREMENT,
  site_id int(10) NOT NULL DEFAULT 0,
  result_type_list int(1) NOT NULL DEFAULT 1,
  show_slogan int(1) NOT NULL DEFAULT 1,
  show_advanced_link int(1) NOT NULL DEFAULT 1,
  show_new_registration_link int(1) NOT NULL DEFAULT 0,
  show_all_location_link int(1) NOT NULL DEFAULT 0,
  locations_per_page int(5) NOT NULL DEFAULT 0,
  type int(1) NOT NULL DEFAULT 4,
  search_type int(1) NOT NULL DEFAULT 1,
  form_code text DEFAULT NULL,
  description text DEFAULT NULL,
  map_width_list varchar(4) DEFAULT NULL,
  map_height_list varchar(4) DEFAULT NULL,
  map_width_details varchar(4) NOT NULL DEFAULT '0',
  map_height_details varchar(4) NOT NULL DEFAULT '0',
  result_type_details int(1) NOT NULL DEFAULT 1,
  map_layout_details varchar(1) NOT NULL DEFAULT '0',
  map_layout_list int(1) NOT NULL DEFAULT 0,
  location_columns int(2) NOT NULL DEFAULT 1,
  zoom_level int(3) NOT NULL DEFAULT 10,
  center_coordinates varchar(50) NOT NULL DEFAULT '',
  text_width_list varchar(4) NOT NULL DEFAULT '0',
  text_height_list varchar(4) NOT NULL DEFAULT '0',
  text_width_details varchar(4) NOT NULL DEFAULT '0',
  text_height_details varchar(4) NOT NULL DEFAULT '0',
  domain varchar(150) NOT NULL,
  show_form_list int(1) NOT NULL,
  show_form_details int(1) NOT NULL,
  not_found varchar(150) NOT NULL DEFAULT '',
  PRIMARY KEY  (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;

	$sql[] = <<<SQL
CREATE TABLE $css_table_name (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(30) NOT NULL DEFAULT '',
  class varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY  (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;

	$sql[] = <<<SQL
CREATE TABLE $fields_table_name (
  id int(5) NOT NULL AUTO_INCREMENT,
  field_name varchar(40) NOT NULL DEFAULT '',
  field_id2 varchar(10) NOT NULL DEFAULT '0',
  enabled int(1) NOT NULL DEFAULT 1,
  display_name varchar(40) NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;

	$sql[] = <<<SQL
CREATE TABLE $layouts_table_name (
  xid int(5) NOT NULL AUTO_INCREMENT,
  field_id int(5) NOT NULL DEFAULT 0,
  layout_id int(5) NOT NULL DEFAULT 0,
  field_type int(1) NOT NULL DEFAULT 0,
  visible int(1) NOT NULL DEFAULT 1,
  show_title int(1) NOT NULL DEFAULT 1,
  field_order int(5) NOT NULL DEFAULT 0,
  lincable int(1) NOT NULL DEFAULT 0,
  KEY field_id (field_id),
  KEY layout_id (layout_id),
  PRIMARY KEY  (xid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;
	$url   = ADMIN_POST_URL;

	$sql[] = <<<SQL
INSERT INTO $config_table_name (id, site_id, result_type_list, show_slogan, show_advanced_link, show_new_registration_link, show_all_location_link, locations_per_page, type, search_type, form_code, description, map_width_list, map_height_list, map_width_details, map_height_details, result_type_details, map_layout_details, map_layout_list, location_columns, zoom_level, center_coordinates, text_width_list, text_height_list, text_width_details, text_height_details, domain, show_form_list, show_form_details, not_found) VALUES
	(1, 0, 1, 0, 1, 1, 1, 10, 1, 1, NULL, NULL, NULL, NULL, '0', '0', 0, '0', 0, 1, 10, '', '0', '0', '0', '0', 'https://app.xtremelocator.com', 0, 0, 'No records found'),
	(2, 0, 1, 1, 1, 1, 0, 4, 2, 1, NULL, 'Enter your zip code to find a dealer near you.', '750', '500', '750', '500', 3, '0', 0, 1, 10, '', '100', '0', '100', '0', '', 0, 0, ''),
	(6, 0, 1, 1, 0, 1, 0, 4, 3, 3, '<form id="searchForm" action="$url" method="post" name="searchForm"><input type="hidden" name="action" value="xtreme_locator"><input type="hidden" name="option" value="com_xtremelocator" /><input type="hidden" name="view" value="advanced" /><table cellpadding="4" cellspacing="0" border="0"><tr>      <td align="center">        <table border="0" cellpadding="0" cellspacing="0" width="100%"><tr>            <td valign="top">              <table cellpadding="0" border="0" width="100px"><tr>                  <td colspan="2"><hr size="1" width="99%"></td></tr><tr>                  <td colspan="2" align="center">                    <table border="0" cellpadding="0" cellspacing="0" width="100%"><tr>                        <td width="29%">ZIP</td><td align="left"><input type="text" name="zip" size="6" id="XlocatorZip"></td></tr></table></td></tr><tr>                  <td nowrap colspan="2"><hr size="1" width="99%"></td></tr><tr>                  <td width="225" nowrap>Name</td><td width="369"><input type=text name="name" size=0></td></tr><tr>                  <td nowrap>City</td><td><input type=text name="city" size=0></td></tr><tr>                  <td nowrap>State</td><td><input type=text name="state" size=0></td></tr><tr>                  <td nowrap>Country</td><td><select id="XlocatorCountry" name="country" onChange="checkZip(this)"> </select> <script type="text/javascript" src="https://app.xtremelocator.com/visitor/searchByCountryJS.php?siteId=SITEID"></script> </td></tr><tr>                  <td nowrap>Telephone Area Code</td><td><input type=text name="telephone area code" size=3></td></tr><tr>                  <td nowrap colspan="2"><hr size="1" width="99%"></td></tr><tr>                  <td valign="top">Special events </td><td valign="top"><input type="Checkbox" name="events"></td></tr><tr>                  <td colspan=2></td></tr><tr>                  <td valign="top" align="center" colspan="2">                    <input type="submit" value="SEARCH"><input type="reset" value="CLEAR"></td></tr></table></td></tr></table></td><tr>      <td></td></tr></table></FORM>', 'You can find locations by filling this form.', '750', '500', '750', '500', 1, '0', 0, 1, 10, '', '100', '0', '100', '0', '', 0, 0, ''),
	(7, 0, 1, 0, 1, 0, 0, 5, 4, 1, NULL, 'This is a listing of all of our locations.', '750', '500', '750', '500', 1, '0', 0, 1, 10, '', '100', '0', '100', '0', '', 0, 0, ''),
	(8, 0, 2, 0, 1, 0, 0, 0, 5, 1, NULL, 'Click on an icon near you for location information.  Zoom in using the bar and drag the map for a closer view of your area.', '750', '500', '750', '500', 0, '0', 0, 1, 3, '40.044438,-98.701172', '0', '0', '0', '0', '', 0, 0, ''),
	(9, 0, 1, 0, 1, 0, 0, 0, 4, 1, NULL, NULL, NULL, NULL, '0', '0', 1, '0', 0, 1, 10, '', '0', '0', '0', '0', '', 0, 0, ''),
	(10, 0, 1, 1, 0, 1, 0, 4, 6, 6, '<form id="searchForm" action="$url" method="post" name="searchForm"><input type="hidden" name="action" value="xtreme_locator"><input type="hidden" name="option" value="com_xtremelocator" /><input type="hidden" name="view" value="advanced" /><table cellpadding="4" cellspacing="0" border="0"><tr>      <td align="center">        <table border="0" cellpadding="0" cellspacing="0" width="100%"><tr>            <td valign="top">              <table cellpadding="0" border="0" width="100px"><tr>                  <td colspan="2"><hr size="1" width="99%"></td></tr><tr>                  <td colspan="2" align="center">                    <table border="0" cellpadding="0" cellspacing="0" width="100%"><tr>                        <td width="29%">ZIP</td><td align="left"><input type="text" name="zip" size="6" id="XlocatorZip"></td></tr></table></td></tr><tr>                  <td nowrap colspan="2"><hr size="1" width="99%"></td></tr><tr>                  <td width="225" nowrap>Name</td><td width="369"><input type=text name="name" size=0></td></tr><tr>                  <td nowrap>City</td><td><input type=text name="city" size=0></td></tr><tr>                  <td nowrap>State</td><td><input type=text name="state" size=0></td></tr><tr>                  <td nowrap>Country</td><td><select id="XlocatorCountry" name="country" onChange="checkZip(this)"> </select> <script type="text/javascript" src="https://app.xtremelocator.com/visitor/searchByCountryJS.php?siteId=SITEID"></script> </td></tr><tr>                  <td nowrap>Telephone Area Code</td><td><input type=text name="telephone area code" size=3></td></tr><tr>                  <td nowrap colspan="2"><hr size="1" width="99%"></td></tr><tr>                  <td valign="top">Special events </td><td valign="top"><input type="Checkbox" name="events"></td></tr><tr>                  <td colspan=2></td></tr><tr>                  <td valign="top" align="center" colspan="2">                    <input type="submit" value="SEARCH"><input type="reset" value="CLEAR"></td></tr></table></td></tr></table></td><tr>      <td></td></tr></table></FORM>', 'You can find locations by filling this form.', '750', '500', '750', '500', 1, '0', 0, 1, 10, '', '100', '0', '100', '0', '', 0, 0, '');
SQL;

	$sql[] = <<<SQL

INSERT INTO $css_table_name (id, name, class) VALUES
	(1, 'xl_search_form', 'xl_search_form'),
	(2, 'xl_form_message', 'xl_form_message'),
	(3, 'searchForm', 'searchForm'),
	(5, 'xl_wraper', 'xl_wraper'),
	(6, 'xl_advanced_search_link', 'xl_advanced_search_link'),
	(7, 'xl_all_locations_link', 'xl_all_locations_link'),
	(8, 'xl_search_results', 'xl_search_results'),
	(9, 'xl_search_locations', 'xl_search_locations'),
	(10, 'xl_result', 'xl_result'),
	(11, 'xl_result_item_map', 'xl_result_item_map'),
	(12, 'xl_result_location', 'xl_result_location'),
	(13, 'xl_result_item', 'xl_result_item'),
	(14, 'xl_search_footer', 'xl_search_footer'),
	(15, 'xl_results_title', 'xl_results_title'),
	(16, 'xl_results_value', 'xl_results_value');
SQL;

	$sql[] = <<<SQL
INSERT INTO $fields_table_name (id, field_name, field_id2, enabled, display_name) VALUES
	(6, 'Additional Info', '1', 1, 'Additional Info'),
	(8, 'Address', '3', 1, 'Address'),
	(9, 'City', '4', 0, 'City'),
	(10, 'Contact Name', '5', 1, 'Contact Name'),
	(11, 'Contact Position', '6', 1, 'Contact Position'),
	(12, 'Country', '7', 1, 'Country'),
	(13, 'Distance', '8', 1, 'Distance'),
	(14, 'E-mail', '9', 1, 'E-mail'),
	(15, 'Fax', '10', 1, 'Fax'),
	(17, 'Highlight', '12', 1, 'Highlight'),
	(18, 'Location Audio', '13', 1, 'Location Audio'),
	(19, 'Location Image', '14', 1, 'Location Image'),
	(20, 'Location Radius', '15', 0, 'Location Radius'),
	(21, 'Name', '16', 1, 'Name'),
	(22, 'Number', '17', 1, 'Number'),
	(23, 'Phone', '18', 1, 'Phone'),
	(24, 'Specialties', '19', 1, 'Specialties'),
	(25, 'Sponsor', '20', 0, 'Sponsor'),
	(26, 'State', '21', 0, 'State'),
	(27, 'Status', '22', 0, 'Status'),
	(28, 'Street', '23', 0, 'Street'),
	(30, 'Telephone Area Code', '25', 0, 'Telephone Area Code'),
	(31, 'Territory', '26', 0, 'Territory'),
	(32, 'Toll Free', '27', 1, 'Toll Free'),
	(33, 'Url', '28', 1, 'Url'),
	(34, 'Zip', '29', 0, 'Zip');
SQL;


	$sql[] = <<<SQL
INSERT INTO $layouts_table_name (field_id, layout_id, field_type, visible, show_title, field_order, lincable) VALUES
	(0, 0, 0, 1, 1, 0, 0),
	(6, 4, 1, 0, 0, 6, 0),
	(6, 4, 2, 1, 0, 11, 0),
	(8, 4, 1, 1, 0, 2, 0),
	(8, 4, 2, 1, 0, 2, 0),
	(9, 4, 1, 0, 0, 9, 0),
	(9, 4, 2, 0, 0, 9, 0),
	(10, 4, 1, 0, 0, 10, 0),
	(10, 4, 2, 1, 1, 9, 0),
	(11, 4, 1, 0, 0, 11, 0),
	(11, 4, 2, 1, 1, 10, 0),
	(12, 4, 1, 1, 0, 3, 0),
	(12, 4, 2, 1, 0, 3, 0),
	(13, 4, 1, 0, 0, 13, 0),
	(13, 4, 2, 0, 0, 13, 0),
	(14, 4, 1, 0, 0, 14, 0),
	(14, 4, 2, 1, 1, 7, 0),
	(15, 4, 1, 0, 0, 15, 0),
	(15, 4, 2, 1, 1, 6, 0),
	(17, 4, 1, 0, 0, 17, 0),
	(17, 4, 2, 0, 0, 17, 0),
	(18, 4, 1, 0, 0, 18, 0),
	(18, 4, 2, 0, 0, 18, 0),
	(19, 4, 1, 0, 0, 19, 0),
	(19, 4, 2, 0, 0, 19, 0),
	(20, 4, 1, 0, 0, 20, 0),
	(20, 4, 2, 0, 0, 20, 0),
	(21, 4, 1, 1, 0, 1, 1),
	(21, 4, 2, 1, 0, 1, 1),
	(22, 4, 1, 0, 0, 22, 0),
	(22, 4, 2, 0, 0, 22, 0),
	(23, 4, 1, 1, 0, 4, 0),
	(23, 4, 2, 1, 1, 4, 0),
	(24, 4, 1, 0, 0, 24, 0),
	(24, 4, 2, 1, 1, 24, 0),
	(25, 4, 1, 0, 0, 25, 0),
	(25, 4, 2, 0, 0, 25, 0),
	(26, 4, 1, 0, 0, 26, 0),
	(26, 4, 2, 0, 0, 26, 0),
	(27, 4, 1, 0, 0, 27, 0),
	(27, 4, 2, 0, 0, 27, 0),
	(28, 4, 1, 0, 0, 28, 0),
	(28, 4, 2, 0, 0, 28, 0),
	(30, 4, 1, 0, 0, 30, 0),
	(30, 4, 2, 0, 0, 30, 0),
	(31, 4, 1, 0, 0, 31, 0),
	(31, 4, 2, 0, 0, 31, 0),
	(32, 4, 1, 0, 0, 32, 0),
	(32, 4, 2, 1, 1, 5, 0),
	(33, 4, 1, 0, 0, 33, 0),
	(33, 4, 2, 1, 1, 8, 0),
	(34, 4, 1, 0, 0, 34, 0),
	(34, 4, 2, 0, 0, 34, 0),
	(6, 2, 1, 0, 0, 6, 0),
	(6, 2, 2, 1, 1, 9, 0),
	(8, 2, 1, 1, 0, 2, 0),
	(8, 2, 2, 1, 0, 2, 0),
	(9, 2, 1, 0, 0, 9, 0),
	(9, 2, 2, 0, 0, 9, 0),
	(10, 2, 1, 0, 0, 10, 0),
	(10, 2, 2, 1, 1, 10, 0),
	(11, 2, 1, 0, 0, 11, 0),
	(11, 2, 2, 1, 1, 11, 0),
	(12, 2, 1, 1, 0, 3, 0),
	(12, 2, 2, 1, 0, 3, 0),
	(13, 2, 1, 1, 1, 5, 0),
	(13, 2, 2, 1, 1, 13, 0),
	(14, 2, 1, 0, 0, 14, 0),
	(14, 2, 2, 1, 1, 7, 0),
	(15, 2, 1, 0, 0, 15, 0),
	(15, 2, 2, 1, 1, 6, 0),
	(17, 2, 1, 0, 0, 17, 0),
	(17, 2, 2, 1, 0, 17, 0),
	(18, 2, 1, 0, 0, 18, 0),
	(18, 2, 2, 0, 0, 18, 0),
	(19, 2, 1, 0, 0, 19, 0),
	(19, 2, 2, 1, 0, 19, 0),
	(20, 2, 1, 0, 0, 20, 0),
	(20, 2, 2, 0, 0, 20, 0),
	(21, 2, 1, 1, 0, 1, 1),
	(21, 2, 2, 1, 0, 1, 1),
	(22, 2, 1, 0, 0, 22, 0),
	(22, 2, 2, 0, 0, 22, 0),
	(23, 2, 1, 1, 1, 4, 0),
	(23, 2, 2, 1, 1, 4, 0),
	(24, 2, 1, 0, 0, 24, 0),
	(24, 2, 2, 1, 0, 24, 0),
	(25, 2, 1, 0, 0, 25, 0),
	(25, 2, 2, 0, 0, 25, 0),
	(26, 2, 1, 0, 0, 26, 0),
	(26, 2, 2, 0, 0, 26, 0),
	(27, 2, 1, 0, 0, 27, 0),
	(27, 2, 2, 0, 0, 27, 0),
	(28, 2, 1, 0, 0, 28, 0),
	(28, 2, 2, 0, 0, 28, 0),
	(30, 2, 1, 0, 0, 30, 0),
	(30, 2, 2, 0, 0, 30, 0),
	(31, 2, 1, 0, 0, 31, 0),
	(31, 2, 2, 0, 0, 31, 0),
	(32, 2, 1, 0, 0, 32, 0),
	(32, 2, 2, 1, 1, 5, 0),
	(33, 2, 1, 0, 0, 33, 0),
	(33, 2, 2, 1, 1, 8, 0),
	(34, 2, 1, 0, 0, 34, 0),
	(34, 2, 2, 0, 0, 34, 0),
	(6, 3, 1, 0, 0, 7, 0),
	(6, 3, 2, 1, 0, 10, 0),
	(8, 3, 1, 1, 1, 2, 0),
	(8, 3, 2, 1, 1, 2, 0),
	(9, 3, 1, 0, 0, 9, 0),
	(9, 3, 2, 0, 0, 9, 0),
	(10, 3, 1, 0, 0, 10, 0),
	(10, 3, 2, 0, 0, 11, 0),
	(11, 3, 1, 0, 0, 11, 0),
	(11, 3, 2, 0, 0, 12, 0),
	(12, 3, 1, 1, 1, 3, 0),
	(12, 3, 2, 1, 1, 3, 0),
	(13, 3, 1, 1, 1, 5, 0),
	(13, 3, 2, 1, 1, 9, 0),
	(14, 3, 1, 0, 0, 14, 0),
	(14, 3, 2, 1, 1, 7, 0),
	(15, 3, 1, 0, 0, 15, 0),
	(15, 3, 2, 1, 1, 6, 0),
	(17, 3, 1, 1, 0, 6, 0),
	(17, 3, 2, 0, 0, 17, 0),
	(18, 3, 1, 0, 0, 18, 0),
	(18, 3, 2, 0, 0, 18, 0),
	(19, 3, 1, 0, 0, 19, 0),
	(19, 3, 2, 0, 0, 19, 0),
	(20, 3, 1, 0, 0, 20, 0),
	(20, 3, 2, 0, 0, 20, 0),
	(21, 3, 1, 1, 1, 1, 1),
	(21, 3, 2, 1, 1, 1, 1),
	(22, 3, 1, 0, 0, 22, 0),
	(22, 3, 2, 0, 0, 22, 0),
	(23, 3, 1, 1, 1, 4, 0),
	(23, 3, 2, 1, 1, 4, 0),
	(24, 3, 1, 0, 0, 24, 0),
	(24, 3, 2, 0, 0, 24, 0),
	(25, 3, 1, 0, 0, 25, 0),
	(25, 3, 2, 0, 0, 25, 0),
	(26, 3, 1, 0, 0, 26, 0),
	(26, 3, 2, 0, 0, 26, 0),
	(27, 3, 1, 0, 0, 27, 0),
	(27, 3, 2, 0, 0, 27, 0),
	(28, 3, 1, 0, 0, 28, 0),
	(28, 3, 2, 0, 0, 28, 0),
	(30, 3, 1, 0, 0, 30, 0),
	(30, 3, 2, 0, 0, 30, 0),
	(31, 3, 1, 0, 0, 31, 0),
	(31, 3, 2, 0, 0, 31, 0),
	(32, 3, 1, 0, 0, 8, 0),
	(32, 3, 2, 1, 1, 5, 0),
	(33, 3, 1, 0, 0, 33, 0),
	(33, 3, 2, 1, 1, 8, 0),
	(34, 3, 1, 0, 0, 34, 0),
	(34, 3, 2, 0, 0, 34, 0),
	(6, 6, 1, 0, 0, 7, 0),
	(6, 6, 2, 1, 0, 10, 0),
	(8, 6, 1, 1, 1, 2, 0),
	(8, 6, 2, 1, 1, 2, 0),
	(9, 6, 1, 0, 0, 9, 0),
	(9, 6, 2, 0, 0, 9, 0),
	(10, 6, 1, 0, 0, 10, 0),
	(10, 6, 2, 0, 0, 11, 0),
	(11, 6, 1, 0, 0, 11, 0),
	(11, 6, 2, 0, 0, 12, 0),
	(12, 6, 1, 1, 1, 3, 0),
	(12, 6, 2, 1, 1, 3, 0),
	(13, 6, 1, 1, 1, 5, 0),
	(13, 6, 2, 1, 1, 9, 0),
	(14, 6, 1, 0, 0, 14, 0),
	(14, 6, 2, 1, 1, 7, 0),
	(15, 6, 1, 0, 0, 15, 0),
	(15, 6, 2, 1, 1, 6, 0),
	(17, 6, 1, 1, 0, 6, 0),
	(17, 6, 2, 0, 0, 17, 0),
	(18, 6, 1, 0, 0, 18, 0),
	(18, 6, 2, 0, 0, 18, 0),
	(19, 6, 1, 0, 0, 19, 0),
	(19, 6, 2, 0, 0, 19, 0),
	(20, 6, 1, 0, 0, 20, 0),
	(20, 6, 2, 0, 0, 20, 0),
	(21, 6, 1, 1, 1, 1, 1),
	(21, 6, 2, 1, 1, 1, 1),
	(22, 6, 1, 0, 0, 22, 0),
	(22, 6, 2, 0, 0, 22, 0),
	(23, 6, 1, 1, 1, 4, 0),
	(23, 6, 2, 1, 1, 4, 0),
	(24, 6, 1, 0, 0, 24, 0),
	(24, 6, 2, 0, 0, 24, 0),
	(25, 6, 1, 0, 0, 25, 0),
	(25, 6, 2, 0, 0, 25, 0),
	(26, 6, 1, 0, 0, 26, 0),
	(26, 6, 2, 0, 0, 26, 0),
	(27, 6, 1, 0, 0, 27, 0),
	(27, 6, 2, 0, 0, 27, 0),
	(28, 6, 1, 0, 0, 28, 0),
	(28, 6, 2, 0, 0, 28, 0),
	(30, 6, 1, 0, 0, 30, 0),
	(30, 6, 2, 0, 0, 30, 0),
	(31, 6, 1, 0, 0, 31, 0),
	(31, 6, 2, 0, 0, 31, 0),
	(32, 6, 1, 0, 0, 8, 0),
	(32, 6, 2, 1, 1, 5, 0),
	(33, 6, 1, 0, 0, 33, 0),
	(33, 6, 2, 1, 1, 8, 0),
	(34, 6, 1, 0, 0, 34, 0),
	(34, 6, 2, 0, 0, 34, 0);
SQL;


	//checking if table already exists
	if ( $wpdb->get_var( "SHOW TABLES LIKE '$config_table_name'" ) !== $config_table_name ) {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
	add_option( 'xl_widget_url', '' );
	add_option( 'xl_search_type', 1 );
	add_option( 'xl_widget_title', "Dealer locator" );
	add_option( 'xl_widget_zip', "Zip" );
	add_option( 'xl_widget_desc', "Enter locator zip" );
}

function xl_prevent_upgrade( $opt ) {
	global $update_class;
	$plugin = plugin_basename( __FILE__ );
	if ( $opt && isset( $opt->response[ $plugin ] ) ) {
		$update_class = "update-message";
	}

	return $opt;
}

function get_transient_key( $name ) {
	//error_log(session_id() . $name);
	return session_id() . $name;
}