<?php
$data = get_transient(get_transient_key('xl_results')) ;
if ( isset( $_GET['lid'] ) ) {
	$r[] =$data[ $_GET['lid'] ];
} else {
	$r = $data;
}
if ( isset( $_GET["pos"] ) ) {
	$position = max( $_GET["pos"], 0 );
} else {
	$position = 0;
}

$start = $position;

$pageSize = $conf->locations_per_page;

$end = min( count( $r ), $position + $pageSize );
?>
<div id="<?php echo $css['xl_search_results']['class']; ?>">
    <div id="<?php echo $css['xl_search_locations']['class']; ?>">
		<?php
		$resultType      = isset( $_GET['lid'] ) ? $conf->result_type_details : $conf->result_type_list;
		$columns         = $list_fields;
		$locationColumns = $detailed_fields;
		$lok             = isset( $_GET['lid'] ) ? $locationColumns : $columns;
		$map_pos         = isset( $_GET['lid'] ) ? $conf->map_layout_details : $conf->map_layout_list;
		$c               = 0;
		for ( $i = $start;
		$i < $end;
		$i ++ )
		{

		$row = $r[ $i ];
		$map = "";
		if ( $resultType == 3 ) {
			$map_it  = "";
			$map_it2 = "";
		} else {
			$map_it  = "<div class='map_it'><a href='#' onClick=\"NewWindow('" . $gconf->domain . "/visitor/googlemap.php?lid=" . $row['_id'] . "&sid=" . $gconf->site_id . "&zoom=14&language=&info=1&width=" . ( isset( $_GET['lid'] ) ? $conf->map_width_details : $conf->map_width_list ) . "%&height=" . ( isset( $_GET['lid'] ) ? $conf->map_height_details : $conf->map_height_list ) . "%', '_blank', " . ( isset( $_GET['lid'] ) ? $conf->map_width_details : $conf->map_width_list ) . ", " . ( isset( $_GET['lid'] ) ? $conf->map_height_details : $conf->map_height_list ) . ", 0);return false;\"><img src='" . XL_BASE . "/icons/mapit.gif" . "'/></a><br/>" . __( "Map&Directions", XL_TEXT_DOMAIN ) . "</div>";
			$map_it2 = ! isset( $_GET['lid'] ) ? "<div class='zoom_it'><a href='#' onClick=\"NewWindow('" . $gconf->domain . "/visitor/googlemap.php?lid=" . $row['_id'] . "&sid=" . $gconf->site_id . "&zoom=14&language=&info=1&width=" . $conf->map_width_details . "%&height=" . $conf->map_height_details . "%', '_blank', " . $conf->map_width_details . ", " . $conf->map_height_details . ", 0);return false;\"><img src='" . XL_BASE . "/icons/glass.gif'/></a></div>" : "";
		}
		if ( $resultType == 2 ) {
			$map = '<div class="' . $css['xl_result_item_map']['class'] . '">
                             <iframe width="' . ( ! isset( $_GET['lid'] ) ? $conf->map_width_list : $conf->map_width_details ) . '%" height="' . ( ! isset( $_GET['lid'] ) ? $conf->map_height_list : $conf->map_height_details ) . '%" scrolling="no" frameborder="0" hspace="0" vspace="0" marginheight="0"  marginwidth="0" src="' . $gconf->domain . '/visitor/googlemap.php?infoWindow=false&lid=' . $row['_id'] . '&sid=' . $gconf->site_id . '&_center=0&zoom=10&width=' . ( ! isset( $_GET['lid'] ) ? $conf->map_width_list : $conf->map_width_details ) . '%&height=' . ( ! isset( $_GET['lid'] ) ? $conf->map_height_list : $conf->map_height_details ) . '%"></iframe>  
       ' . $map_it2 . '</div>';
		}
		$style = "";
		if ( isset( $_GET['lid'] ) && ( $conf->text_width_details != "0" || $conf->text_height_details != "0" ) ) {
			$style = "style='";
			if ( $conf->text_width_details != "0" ) {
				$style .= "width:" . $conf->text_width_details . "%;";
			}
			if ( $conf->text_height_details != "0" ) {
				$style .= "height:" . $conf->text_height_details . "%;";
			}
			$style .= "'";
		} elseif ( ! isset( $_GET['lid'] ) && ( $conf->text_width_list != "0" || $conf->text_height_list != "0" ) ) {
			$style = "style='";
			if ( $conf->text_width_list != "0" ) {
				$style .= "width:" . $conf->text_width_list . "%;";
			}
			if ( $conf->text_height_list != "0" ) {
				$style .= "height:" . $conf->text_height_list . "%;";
			}
			$style .= "'";
		}
		echo "<div class='" . $css['xl_result']['class'] . ( ! isset( $_GET['lid'] ) && $conf->location_columns > 1 ? '_columns' . $conf->location_columns : '' ) . "'>" . ( $map_pos == 1 || $map_pos == 2 ? $map : '' ) . ( $map_pos == 2 ? '<div class="xl_clear"></div>' : '' ) . "<div class='" . $css['xl_result_location']['class'] . ( $resultType == 2 ? '_map' : '' ) . "' " . $style . "/>";

		$c ++;

		foreach ( $lok as $k => $v ) {
			$k = ucwords( $k );

			if ( $k != '' && $k != 'Id' ) {
				if ( substr( $row[ $k ], 7, 4 ) == "file" ) {

					$row[ $k ] = "<a href=\"" . $gconf->domain . "/common/file.php?id=$row[$k]\">" . __( "Download", XL_TEXT_DOMAIN ) . "</a>";

				}
				if ( ( $k == 'Image' || $k == 'Location Image' ) && $row[ $k ] != "" ) {

					$row[ $k ] = "<img src='" . $gconf->domain . "/common/file.php?id=" . $row[ $k ] . "'>";

				}
				if ( $k == "E-mail" && $row[ $k ] != "" ) {
					$row[ $k ] = "<a href='mailto:" . $row[ $k ] . "'>" . $row[ $k ] . "</a>";
				}
				if ( $k == "Url" && $row[ $k ] != "" ) {
					$url = $row[ $k ];
					if ( ! strstr( $url, "://" ) ) {
						$url = "http://" . $url;
					}
					$row[ $k ] = "<a href='" . $url . "' target='_blank'>" . $row[ $k ] . "</a>";
				}
				if ( $k == "Distance" ) {
					$row[ $k ] = $row[ $k ] >= 0 ? sprintf( "%.1f", $row[ $k ] ) . " " . __( "Miles", XL_TEXT_DOMAIN ) : null;
				}
				if ( ! isset( $_GET['lid'] ) && $v['lincable'] == 1 ) {
					$row[ $k ] = "<a href='?q&lid=" . $i . ( isset( $_GET['search_type'] ) ? "&search_type=" . $_GET['search_type'] : "" ) . ( isset( $_GET['page_id'] ) ? "&page_id=" . $_GET['page_id'] : "" ) . ( isset( $_GET['p'] ) ? "&p=" . $_GET['p'] : "" ) . "'>" . $row[ $k ] . "</a>";
				}
				echo $row[ $k ] != '' ? '<div class="' . $css['xl_result_item']['class'] . '"><div id="xl_' . $k . '_title" class="' . $css['xl_results_title']['class'] . '">' . ( $v['show_title'] == '1' ? $lok[ $k ]['display_name'] : '' ) . '</div> <div id="xl_' . $k . '_value" class="' . $css['xl_results_value']['class'] . '">' . str_replace( "\\n", "<br>", $row[ $k ] ) . '</div><div class="xl_clear"></div></div>' : '';

			}

		}
		echo '</div>' . ( $map_pos == 3 ? '<div class="xl_clear"></div>' : '' ) . ( $map_pos == 0 || $map_pos == 3 || $map_pos == 4 ? $map : '' ) . ( $resultType == 1 ? $map_it : "" );
		?>

    </div>
	<?php if ( $c == $conf->location_columns ) {
		echo '<div class="xl_clear"></div>';
		$c = 0;
	}
	}
	?>
</div>
<?php if ( count( $r ) == 0 ) {
	echo '<div id="' . $css['xl_search_footer']['class'] . '">' . __( $gconf->not_found, XL_TEXT_DOMAIN ) . '</div>';
} elseif ( ! isset( $_GET['lid'] ) ) {
	echo '<div id="' . $css['xl_search_footer']['class'] . '">' . ( $position > 0 ? "<a href='?q&pos=" . ( $position - $pageSize ) . ( isset( $_GET['search_type'] ) ? "&search_type=" . $_GET['search_type'] : "" ) . ( isset( $_GET['page_id'] ) ? "&page_id=" . $_GET['page_id'] : "" ) . ( isset( $_GET['p'] ) ? "&p=" . $_GET['p'] : "" ) . "'>&lt; " . __( "Previous", XL_TEXT_DOMAIN ) . "</a> " : "" ) . __( "Records", XL_TEXT_DOMAIN ) . ' ' . ( $start + 1 ) . " - " . ( $end ) . " (" . count( $r ) . ")" . ( $position + $pageSize < count( $r ) ? "<a href='?q&pos=" . ( $position + $pageSize ) . ( isset( $_GET['search_type'] ) ? "&search_type=" . $_GET['search_type'] : "" ) . ( isset( $_GET['page_id'] ) ? "&page_id=" . $_GET['page_id'] : "" ) . ( isset( $_GET['p'] ) ? "&p=" . $_GET['p'] : "" ) . "'> " . __( "Next", XL_TEXT_DOMAIN ) . "&gt;</a>" : "" ) . '</div>';
} ?>

<?php if ( isset( $_GET['lid'] ) ) { ?>
    <div id="xl_search_back"><a href="javascript:history.back();"><?php echo __( "Back", XL_TEXT_DOMAIN ); ?></a>
    </div><?php } ?>
</div>

