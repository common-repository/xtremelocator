<form action="<?php echo $config[0]->domain; ?>/login/login.php" method="post" target="_blank">
    <table class="adminform">
        <tr>
            <td valign="top">
                <table width="100%">
                    <tr>
                        <td colspan="2"><h3><?php print __( "Login", XL_TEXT_DOMAIN ) ?> </h3>
                        </td>
                    </tr>
                    <tr>
                        <td width="100px"><strong><?php print __( "Username", XL_TEXT_DOMAIN ) ?></strong>
                        </td>
                        <td><input type="text" name="user"/>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><?php print __( "Password", XL_TEXT_DOMAIN ) ?></strong>
                        </td>
                        <td><input type="password" name="password"/>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="submit" value="Login">
                        </td>
                        <td>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</form>
