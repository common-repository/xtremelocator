<table width="500px" border="0" cellspacing="0" cellpadding="0" class='widefat'>
    <tr>
        <th><?php print __( "Xtremelocator", XL_TEXT_DOMAIN ) ?></th>
    </tr>
    <tr>
        <td>
            <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td><img src=<?php print "'" . XL_BASE . "/icons/xtremelocator.png'"; ?>/></td>
                    <td>
                        <table>
                            <tr>
                                <td><?php print __( "Version", XL_TEXT_DOMAIN ) ?>:
                                </td>
                                <td>
									<?php

									echo XL_VERSION ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php print __( "Developed by", XL_TEXT_DOMAIN ) ?>:
                                </td>
                                <td>
                                    <a href="http://www.iqservices.com">IQservices.com</a>
                                </td>
                            </tr>
                            <tr>
                                <td><?php print __( "Visit us", XL_TEXT_DOMAIN ) ?>:
                                </td>
                                <td>
                                    <a href="http://www.iqservices.com">www.IQservices.com</a> |
                                    <a href="http://www.xtremelocator.com">www.XtremeLocator.com</a>
                                </td>
                            </tr>
                            <tr>
                                <td><?php print __( "License", XL_TEXT_DOMAIN ) ?>:
                                </td>
                                <td>
                                    <a href="http://www.gnu.org/licenses/gpl-2.0.html">GPLv2</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>


            <div>
                <a href="admin.php?page=xtreme-locator-settings"
                   class="xl_menu_item"<?php print " alt='" . __( "Settings", XL_TEXT_DOMAIN ) . "' title='" . __( "Settings", XL_TEXT_DOMAIN ) . "'"; ?>>
                    <div>
                        <img src=<?php print "'" . XL_BASE . "/icons/icon-48-settings.png'"; ?>/><br/><?php print __( "Settings", XL_TEXT_DOMAIN ); ?>
                    </div>
                </a>

                <a href="admin.php?page=xtreme-locator-css"
                   class="xl_menu_item"<?php print " alt='" . __( "CSS Styles", XL_TEXT_DOMAIN ) . "' title='" . __( "CSS styles", XL_TEXT_DOMAIN ) . "'"; ?>>
                    <div>
                        <img src=<?php print "'" . XL_BASE . "/icons/icon-48-css.png'"; ?>/><br/><?php print __( "Css Styles", XL_TEXT_DOMAIN ); ?>
                    </div>
                </a>

                <a href="admin.php?page=xtreme-locator-standard-search"
                   class="xl_menu_item"<?php print " alt='" . __( "Standard search", XL_TEXT_DOMAIN ) . "' title='" . __( "Standard search", XL_TEXT_DOMAIN ) . "'"; ?>>
                    <div>
                        <img src=<?php print "'" . XL_BASE . "/icons/icon-48-search.png'"; ?>/><br/><?php print __( "Standard search", XL_TEXT_DOMAIN ); ?>
                    </div>
                </a>

                <a href="admin.php?page=xtreme-locator-advanced-search"
                   class="xl_menu_item"<?php print " alt='" . __( "Advanced search", XL_TEXT_DOMAIN ) . "' title='" . __( "Advanced search", XL_TEXT_DOMAIN ) . "'"; ?>>
                    <div>
                        <img src=<?php print "'" . XL_BASE . "/icons/icon-48-advanced_search.png'"; ?>/><br/><?php print __( "Advanced search", XL_TEXT_DOMAIN ); ?>
                    </div>
                </a>

                <a href="admin.php?page=xtreme-locator-custom-search"
                   class="xl_menu_item"<?php print " alt='" . __( "Custom search", XL_TEXT_DOMAIN ) . "' title='" . __( "Custom search", XL_TEXT_DOMAIN ) . "'"; ?>>
                    <div>
                        <img src=<?php print "'" . XL_BASE . "/icons/icon-48-custom.png'"; ?>/><br/><?php print __( "Custom search", XL_TEXT_DOMAIN ); ?>
                    </div>
                </a>

                <a href="admin.php?page=xtreme-locator-all-locations"
                   class="xl_menu_item"<?php print " alt='" . __( "All locations map setup", XL_TEXT_DOMAIN ) . "' title='" . __( "All Locations Map", XL_TEXT_DOMAIN ) . "'"; ?>>
                    <div>
                        <img src=<?php print "'" . XL_BASE . "/icons/icon-48-all_locations.png'"; ?>/><br/><?php print __( "All Locations Map", XL_TEXT_DOMAIN ); ?>
                    </div>
                </a>

                <a href="admin.php?page=xtreme-locator-listing"
                   class="xl_menu_item"<?php print " alt='" . __( "Location listing page setup", XL_TEXT_DOMAIN ) . "' title='" . __( "Location listing page setup", XL_TEXT_DOMAIN ) . "'"; ?>>
                    <div>
                        <img src=<?php print "'" . XL_BASE . "/icons/icon-48-list_locations.png'"; ?>/><br/><?php print __( "Location listing page setup", XL_TEXT_DOMAIN ); ?>
                    </div>
                </a>

                <a href="admin.php?page=xtreme-locator-admin"
                   class="xl_menu_item"<?php print " alt='" . __( "Location admin login form", XL_TEXT_DOMAIN ) . "' title='" . __( "Location admin login form", XL_TEXT_DOMAIN ) . "'"; ?>>
                    <div>
                        <img src=<?php print "'" . XL_BASE . "/icons/icon-48-xtremelocator.png'"; ?>/><br/><?php print __( "Location admin login form", XL_TEXT_DOMAIN ); ?>
                    </div>
                </a>


        </td>
    </tr>
</table>
<p><?php print __( "Component usage instructions", XL_TEXT_DOMAIN ) ?> <a
            href="http://www.xtremelocator.com/wordpress-plugin-help/" target="_blank">http://www.xtremelocator.com/wordpress-plugin-help/</a>
</p>
<p>Deploying the Xtreme Locator WordPress Plugin:</p>

<p>To deploy Xtreme Locator on your website you simply paste one of the following codes into the body of your
    webpage:</p>
<ul>
    <li>Standard Search: [xtreme_locator_standard]</li>
    <li>Advanced Search: [xtreme_locator_advanced]</li>
    <li>Custom Search: [xtreme_locator_custom]</li>
    <li> All Locations Map: [xtreme_locator_all]</li>
    <li>All Locations List: [xtreme_locator_listing]</li>
   <!-- <li>Widget: Drag the Xtreme Locator widget to the location of your choice to deploy it.</li>-->
</ul>

