<table width="500px" border="0" cellspacing="0" cellpadding="0" class='widefat'>
    <tr>
        <th>
            <img src=<?php print "'" . XL_BASE . "/icons/icon-48-settings.png'"; ?> align="absmiddle"/> <?php print __( "Add field", XL_TEXT_DOMAIN ) ?>
        </th>
    </tr>
    <tr>
        <td>
            <form action="admin.php?page=xtreme-locator-settings" method="post" name="adminForm" id="adminForm"
                  class="adminForm">
                <input type="hidden" name="action" value="add_field"/>
				<?php if ( isset( $_GET['id'] ) ) {
					echo "<input type='hidden' name='xl[id]' value='" . $_GET['id'] . "'/>";
				} ?>
                <table class="adminform">
                    <tr>
                        <td><?php print __( "Field id", XL_TEXT_DOMAIN ) ?>:
                        </td>
                        <td width="80%"><input type="text" size="10" maxsize="100" name="xl[field_id2]"
                                               value="<?php echo isset( $field[0]->field_id2 ) ? $field[0]->field_id2 : ""; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td><?php print __( "Field name", XL_TEXT_DOMAIN ) ?>:
                        </td>
                        <td width="80%"><input type="text" size="30" maxsize="100" name="xl[field_name]"
                                               value="<?php echo isset( $field[0]->field_name ) ? $field[0]->field_name : ""; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td><?php print __( "Display name", XL_TEXT_DOMAIN ) ?>:
                        </td>
                        <td width="80%"><input type="text" size="30" maxsize="100" name="xl[display_name]"
                                               value="<?php echo isset( $field[0]->display_name ) ? $field[0]->display_name : ""; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <button class='button-primary' type='submit' value='save'
                                    name="field_action"><?php print __( "Save", XL_TEXT_DOMAIN ) ?></button>
                            <button class='button-primary' type='submit' value='cancel'
                                    name="field_action"><?php print __( "Cancel", XL_TEXT_DOMAIN ) ?></button>
                        </td>
                    </tr>
                </table>
            </form>
        </td>
    </tr>
</table>
</form>