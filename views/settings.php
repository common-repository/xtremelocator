<table width="500px" border="0" cellspacing="0" cellpadding="0" class='widefat'>
    <tr>
        <th>
            <img src=<?php print "'" . XL_BASE . "/icons/icon-48-settings.png'"; ?> align="absmiddle"/> <?php print __( "Settings", XL_TEXT_DOMAIN ) ?>
        </th>
    </tr>
    <tr>
        <td>
            <form action="#" method="post" name="adminForm" id="adminForm" class="adminForm">
                <table class="adminform">
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td><?php print __( "Account id", XL_TEXT_DOMAIN ) ?>:
                                    </td>
                                    <td width="80%"><input type="text" size="10" maxsize="100" name="xl[site_id]"
                                                           value="<?php echo $config->site_id; ?>"/>
                                    </td>

                                </tr>
                                <tr>
                                    <td><?php print __( "Installation domain", XL_TEXT_DOMAIN ) ?>:
                                    </td>
                                    <td width="80%"><input type="text" size="30" maxsize="100" name="xl[domain]"
                                                           value="<?php echo $config->domain; ?>"/>
                                    </td>

                                </tr>
                                <tr>
                                    <td><?php print __( "CSS path", XL_TEXT_DOMAIN ) ?>:
                                    </td>
                                    <td width="80%"><input type="text" size="100" maxsize="100" value="<?php
										echo XL_PATH; ?>/xtremelocator_pub.css" readonly=""/>
                                    </td>

                                </tr>
                                <tr>
                                    <td><?php print __( "Not found message", XL_TEXT_DOMAIN ) ?>:
                                    </td>
                                    <td width="80%"><input type="text" size="100" maxsize="150" name="xl[not_found]"
                                                           value="<?php echo $config->not_found; ?>"/>
                                    </td>

                                </tr>
                            </table>

                        </td>

                    </tr>
                    <tr>
                        <td>
                            <button class='button-primary' type='submit' value='update'
                                    name="action"><?php print __( "Update", XL_TEXT_DOMAIN ) ?></button>
                            <hr/>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><?php print __( "Available fields", XL_TEXT_DOMAIN ) ?></strong>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <button class='button-primary' type='submit' value='add_field'
                                    name="action"><?php print __( "Add new field", XL_TEXT_DOMAIN ) ?></button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table class="adminlist">
                                <thead>
                                <tr>
                                    <th><?php print __( "Delete", XL_TEXT_DOMAIN ) ?></th>
                                    <th><?php print __( "Field", XL_TEXT_DOMAIN ) ?></th>
                                    <th><?php print __( "Display name", XL_TEXT_DOMAIN ) ?></th>
                                    <th><?php print __( "Enabled", XL_TEXT_DOMAIN ) ?></th>
                                    <th><?php print __( "Id", XL_TEXT_DOMAIN ) ?></th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                </tfoot>
                                <tbody>
								<?php foreach ( $fields as $field ) { ?>
                                    <tr>
                                        <td><input type="checkbox" name="remove[<?php echo $field->id; ?>]" value="1"/>
                                        </td>
                                        <td style="white-space: nowrap;"><a
                                                    href="admin.php?page=xtreme-locator-settings&id=<?php echo $field->id; ?>"><?php echo $field->field_name; ?></a>
                                        </td>
                                        <td style="white-space: nowrap;"><a
                                                    href="admin.php?page=xtreme-locator-settings&id=<?php echo $field->id; ?>"><?php echo $field->display_name; ?></a>
                                        </td>
                                        <td><input type="checkbox" name="enabled[<?php echo $field->id; ?>]"
                                                   value="1" <?php echo $field->enabled == 1 ? ' checked="checked"' : ''; ?>/>
                                        </td>
                                        <td><?php echo $field->field_id2; ?></td>
                                    </tr>
								<?php } ?>
                                </tbody>

                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <button class='button-primary' type='submit' value='remove_field'
                                    name="action"><?php print __( "Delete field", XL_TEXT_DOMAIN ) ?></button>
                            <button class='button-primary' type='submit' value='update_states'
                                    name="action"><?php print __( "Update enabled", XL_TEXT_DOMAIN ) ?></button>
                        </td>
                    </tr>

                </table>
            </form>
        </td>
    </tr>
</table>
