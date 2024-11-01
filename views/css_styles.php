<form action="#" method="post" name="adminForm" id="adminForm" class="adminForm">
    <table width="500px" border="0" cellspacing="0" cellpadding="0" class='widefat'>
        <tr>
            <th>
                <img src=<?php print "'" . XL_BASE . "/icons/icon-48-css.png'"; ?> align="absmiddle"/> <?php print __( "Css styles", XL_TEXT_DOMAIN ) ?>
            </th>
        </tr>
        <tr>
            <td>
                <table class="adminform">
                    <tr>
                        <td><input type="submit" value="<?php print __( "Update", XL_TEXT_DOMAIN ) ?>"
                                   class='button-primary'/></td>
                    </tr>
                    <tr>
                        <td width="55%" valign="top">
                            <div class="xl_styles_layout">
                                <div>
                                    <table width="100%">
										<?php foreach ( $css as $style ) { ?>
                                            <tr>
                                                <td>
                                                    <strong><?php print __( strtoupper( $style->name ), XL_TEXT_DOMAIN ) ?></strong>
                                                </td>
                                                <td><input type="text" name="xl[<?php echo $style->id; ?>]"
                                                           value="<?php echo $style->class; ?>"/></td>
                                            </tr>
										<?php } ?>
                                    </table>
                                </div>
                                <div>
                                    <h3><?php print __( "CSS style layout", XL_TEXT_DOMAIN ) ?></h3>
                                    <img src=<?php print "'" . XL_BASE . "/icons/layouts.jpg'"; ?> align="absmiddle"/>
                                </div>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
        <tr>
            <td><textarea name="css_source" rows="80" cols="150">
<?php

$cssFile = XL_PATH . "/xtremelocator_pub.css";
if ( is_file( $cssFile ) ) {
	$fh      = fopen( $cssFile, 'r' );
	$cssData = fread( $fh, filesize( $cssFile ) );
	fclose( $fh );
	echo $cssData;
}
?>
</textarea></td>
        </tr>
    </table>
</form>
