<?php 

/* Plugin installation/update functions*/

function xl_install(){
	global $wpdb;
	$config_table_name = $wpdb->prefix . "xtremelocator_config";
	$fields_table_name = $wpdb->prefix . "xtremelocator_fields";
	$layouts_table_name = $wpdb->prefix . "xtremelocator_layouts";
	$css_table_name = $wpdb->prefix . "xtremelocator_css";
	$advanced_form="";
	$advanced_form2='<form id="searchForm" action="#" method="post" name="searchForm"><input type="hidden" name="option" value="com_xtremelocator" /><input type="hidden" name="view" value="advanced" /><table cellpadding="4" cellspacing="0" border="0"><tr>      <td align="center">        <table border="0" cellpadding="0" cellspacing="0" width="100%"><tr>            <td valign="top">              <table cellpadding="0" border="0" width="100px"><tr>                  <td colspan="2"><hr size="1" width="99%"></td></tr><tr>                  <td colspan="2" align="center">                    <table border="0" cellpadding="0" cellspacing="0" width="100%"><tr>                        <td width="29%">ZIP</td><td align="left"><input type="text" name="zip" size="6" id="XlocatorZip"></td></tr></table></td></tr><tr>                  <td nowrap colspan="2"><hr size="1" width="99%"></td></tr><tr>                  <td width="225" nowrap>Name</td><td width="369"><input type=text name="name" size=0></td></tr><tr>                  <td nowrap>City</td><td><input type=text name="city" size=0></td></tr><tr>                  <td nowrap>State</td><td><input type=text name="state" size=0></td></tr><tr>                  <td nowrap>Country</td><td><select id="XlocatorCountry" name="country" onChange="checkZip(this)"> </select> <script type="text/javascript" src="https://app.xtremelocator.com/visitor/searchByCountryJS.php?siteId=SITEID"></script> </td></tr><tr>                  <td nowrap>Telephone Area Code</td><td><input type=text name="telephone area code" size=3></td></tr><tr>                  <td nowrap colspan="2"><hr size="1" width="99%"></td></tr><tr>                  <td valign="top">Special events </td><td valign="top"><input type="Checkbox" name="events"></td></tr><tr>                  <td colspan=2></td></tr><tr>                  <td valign="top" align="center" colspan="2">                    <input type="submit" value="SEARCH"><input type="reset" value="CLEAR"></td></tr></table></td></tr></table></td><tr>      <td></td></tr></table></FORM>';
	$sql = "
	DROP TABLE IF EXISTS " . $config_table_name . ";
	CREATE TABLE " . $config_table_name . " (
			  `id` int(5) NOT NULL auto_increment,
			  `site_id` int(10) NOT NULL default '0',
			  `result_type_list` int(1) NOT NULL default '1',
			  `show_slogan` int(1) NOT NULL default '1',
			  `show_advanced_link` int(1) NOT NULL default '1',
			  `show_new_registration_link` int(1) NOT NULL default '0',
			  `show_all_location_link` int(1) NOT NULL default '0',
			  `locations_per_page` int(5) NOT NULL default '0',
			  `type` int(1) NOT NULL default '4',
			  `search_type` int(1) NOT NULL default '1',
			  `form_code` text,
			  `describtion` text,
			  `map_width_list` varchar(4) default NULL,
			  `map_height_list` varchar(4) default NULL,
			  `map_width_details` varchar(4) NOT NULL default '0',
			  `map_height_details` varchar(4) NOT NULL default '0',
			  `result_type_details` int(1) NOT NULL default '1',
			  `map_layout_details` varchar(1) NOT NULL default '0',
			  `map_layout_list` int(1) NOT NULL default '0',
			  `location_columns` int(2) NOT NULL default '1',
			  `zoom_level` int(3) NOT NULL default '10',
			  `center_coordinates` varchar(50) NOT NULL default '',
			  `text_width_list` varchar(4) NOT NULL default '0',
			  `text_height_list` varchar(4) NOT NULL default '0',
			  `text_width_details` varchar(4) NOT NULL default '0',
			  `text_height_details` varchar(4) NOT NULL default '0',
			  `domain` varchar(150) NOT NULL,
			  `show_form_list` int(1) NOT NULL,
			  `show_form_details` int(1) NOT NULL,
			  `not_found` varchar(150) NOT NULL DEFAULT '',
			  PRIMARY KEY  (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;
			
			-- 
			-- Dumping data for table `xtremelocator_config`
			-- 
			
			INSERT INTO `".$config_table_name."` VALUES (1, 0, 1, 0, 1, 1, 1, 10, 1, 1, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 1, 10, '', 0, 0, 0, 0,'https://app.xtremelocator.com',0,0,'No records found');
			INSERT INTO `".$config_table_name."` VALUES (2, 0, 1, 1, 1, 1, 0, 4, 2, 1, NULL, 'Enter your zip code to find a dealer near you.', 750, 500, 750, 500, 3, 0, 0, 1, 10, '', 100, 0, 100, 0,'',0,0,'');
			INSERT INTO `".$config_table_name."` VALUES (6, 0, 1, 1, 0, 1, 0, 4, 3, 3, '".$advanced_form."', 'You can find locations by filling this form.', 750, 500, 750, 500, 1, 0, 0, 1, 10, '', 100, 0, 100, 0,'',0,0,'');
			INSERT INTO `".$config_table_name."` VALUES (7, 0, 1, 0, 1, 0, 0, 5, 4, 1, NULL, 'This is a listing of all of our locations.', 750, 500, 750, 500, 1, 0, 0, 1, 10, '', 100, 0, 100, 0,'',0,0,'');
			INSERT INTO `".$config_table_name."` VALUES (8, 0, 2, 0, 1, 0, 0, 0, 5, 1, NULL, 'Click on an icon near you for location information.  Zoom in using the bar and drag the map for a closer view of your area.', 750, 500, 750, 500, 0, 0, 0, 1, 3, '40.044438,-98.701172', 0, 0, 0, 0,'',0,0,'');
			INSERT INTO `".$config_table_name."` VALUES (9, 0, 1, 0, 1, 0, 0, 0, 4, 1, NULL, NULL, NULL, NULL, 0, 0, 1, 0, 0, 1, 10, '', 0, 0, 0, 0,'',0,0,'');
			
			INSERT INTO `".$config_table_name."` VALUES (10, 0, 1, 1, 0, 1, 0, 4, 6, 6, '".$advanced_form."', 'You can find locations by filling this form.', 750, 500, 750, 500, 1, 0, 0, 1, 10, '', 100, 0, 100, 0,'',0,0,'');
			
			
			
			DROP TABLE IF EXISTS `".$fields_table_name."`;		
			
			CREATE TABLE IF NOT EXISTS `".$fields_table_name."` (
			  `id` int(5) NOT NULL AUTO_INCREMENT,
			  `field_name` varchar(40) NOT NULL DEFAULT '',
			  `field_id2` varchar(10) NOT NULL DEFAULT '0',
			  `enabled` int(1) NOT NULL DEFAULT '1',
			  `display_name` varchar(40) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

						
			INSERT INTO `".$fields_table_name."` (`id`, `field_name`, `field_id2`, `enabled`, `display_name`) VALUES
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

			
			DROP TABLE IF EXISTS `".$layouts_table_name."`;
			CREATE TABLE `".$layouts_table_name."` (
			  `field_id` int(5) NOT NULL default '0',
			  `layout_id` int(5) NOT NULL default '0',
			  `type` int(1) NOT NULL default '0',
			  `visible` int(1) NOT NULL default '1',
			  `show_title` int(1) NOT NULL default '1',
			  `order` int(5) NOT NULL default '0',
			  `lincable` int(1) NOT NULL default '0',
			  KEY `field_id` (`field_id`),
			  KEY `layout_id` (`layout_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			
			INSERT INTO `".$layouts_table_name."` VALUES (0, 0, 0, 1, 1, 0, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (6, 4, 1, 0, 0, 6, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (6, 4, 2, 1, 0, 11, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (8, 4, 1, 1, 0, 2, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (8, 4, 2, 1, 0, 2, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (9, 4, 1, 0, 0, 9, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (9, 4, 2, 0, 0, 9, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (10, 4, 1, 0, 0, 10, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (10, 4, 2, 1, 1, 9, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (11, 4, 1, 0, 0, 11, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (11, 4, 2, 1, 1, 10, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (12, 4, 1, 1, 0, 3, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (12, 4, 2, 1, 0, 3, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (13, 4, 1, 0, 0, 13, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (13, 4, 2, 0, 0, 13, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (14, 4, 1, 0, 0, 14, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (14, 4, 2, 1, 1, 7, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (15, 4, 1, 0, 0, 15, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (15, 4, 2, 1, 1, 6, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (17, 4, 1, 0, 0, 17, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (17, 4, 2, 0, 0, 17, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (18, 4, 1, 0, 0, 18, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (18, 4, 2, 0, 0, 18, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (19, 4, 1, 0, 0, 19, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (19, 4, 2, 0, 0, 19, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (20, 4, 1, 0, 0, 20, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (20, 4, 2, 0, 0, 20, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (21, 4, 1, 1, 0, 1, 1);
			INSERT INTO `".$layouts_table_name."` VALUES (21, 4, 2, 1, 0, 1, 1);
			INSERT INTO `".$layouts_table_name."` VALUES (22, 4, 1, 0, 0, 22, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (22, 4, 2, 0, 0, 22, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (23, 4, 1, 1, 0, 4, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (23, 4, 2, 1, 1, 4, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (24, 4, 1, 0, 0, 24, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (24, 4, 2, 1, 1, 24, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (25, 4, 1, 0, 0, 25, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (25, 4, 2, 0, 0, 25, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (26, 4, 1, 0, 0, 26, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (26, 4, 2, 0, 0, 26, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (27, 4, 1, 0, 0, 27, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (27, 4, 2, 0, 0, 27, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (28, 4, 1, 0, 0, 28, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (28, 4, 2, 0, 0, 28, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (30, 4, 1, 0, 0, 30, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (30, 4, 2, 0, 0, 30, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (31, 4, 1, 0, 0, 31, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (31, 4, 2, 0, 0, 31, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (32, 4, 1, 0, 0, 32, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (32, 4, 2, 1, 1, 5, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (33, 4, 1, 0, 0, 33, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (33, 4, 2, 1, 1, 8, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (34, 4, 1, 0, 0, 34, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (34, 4, 2, 0, 0, 34, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (6, 2, 1, 0, 0, 6, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (6, 2, 2, 1, 1, 9, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (8, 2, 1, 1, 0, 2, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (8, 2, 2, 1, 0, 2, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (9, 2, 1, 0, 0, 9, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (9, 2, 2, 0, 0, 9, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (10, 2, 1, 0, 0, 10, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (10, 2, 2, 1, 1, 10, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (11, 2, 1, 0, 0, 11, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (11, 2, 2, 1, 1, 11, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (12, 2, 1, 1, 0, 3, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (12, 2, 2, 1, 0, 3, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (13, 2, 1, 1, 1, 5, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (13, 2, 2, 1, 1, 13, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (14, 2, 1, 0, 0, 14, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (14, 2, 2, 1, 1, 7, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (15, 2, 1, 0, 0, 15, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (15, 2, 2, 1, 1, 6, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (17, 2, 1, 0, 0, 17, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (17, 2, 2, 1, 0, 17, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (18, 2, 1, 0, 0, 18, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (18, 2, 2, 0, 0, 18, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (19, 2, 1, 0, 0, 19, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (19, 2, 2, 1, 0, 19, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (20, 2, 1, 0, 0, 20, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (20, 2, 2, 0, 0, 20, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (21, 2, 1, 1, 0, 1, 1);
			INSERT INTO `".$layouts_table_name."` VALUES (21, 2, 2, 1, 0, 1, 1);
			INSERT INTO `".$layouts_table_name."` VALUES (22, 2, 1, 0, 0, 22, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (22, 2, 2, 0, 0, 22, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (23, 2, 1, 1, 1, 4, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (23, 2, 2, 1, 1, 4, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (24, 2, 1, 0, 0, 24, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (24, 2, 2, 1, 0, 24, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (25, 2, 1, 0, 0, 25, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (25, 2, 2, 0, 0, 25, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (26, 2, 1, 0, 0, 26, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (26, 2, 2, 0, 0, 26, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (27, 2, 1, 0, 0, 27, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (27, 2, 2, 0, 0, 27, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (28, 2, 1, 0, 0, 28, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (28, 2, 2, 0, 0, 28, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (30, 2, 1, 0, 0, 30, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (30, 2, 2, 0, 0, 30, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (31, 2, 1, 0, 0, 31, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (31, 2, 2, 0, 0, 31, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (32, 2, 1, 0, 0, 32, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (32, 2, 2, 1, 1, 5, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (33, 2, 1, 0, 0, 33, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (33, 2, 2, 1, 1, 8, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (34, 2, 1, 0, 0, 34, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (34, 2, 2, 0, 0, 34, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (6, 3, 1, 0, 0, 7, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (6, 3, 2, 1, 0, 10, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (8, 3, 1, 1, 1, 2, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (8, 3, 2, 1, 1, 2, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (9, 3, 1, 0, 0, 9, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (9, 3, 2, 0, 0, 9, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (10, 3, 1, 0, 0, 10, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (10, 3, 2, 0, 0, 11, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (11, 3, 1, 0, 0, 11, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (11, 3, 2, 0, 0, 12, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (12, 3, 1, 1, 1, 3, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (12, 3, 2, 1, 1, 3, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (13, 3, 1, 1, 1, 5, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (13, 3, 2, 1, 1, 9, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (14, 3, 1, 0, 0, 14, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (14, 3, 2, 1, 1, 7, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (15, 3, 1, 0, 0, 15, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (15, 3, 2, 1, 1, 6, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (17, 3, 1, 1, 0, 6, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (17, 3, 2, 0, 0, 17, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (18, 3, 1, 0, 0, 18, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (18, 3, 2, 0, 0, 18, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (19, 3, 1, 0, 0, 19, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (19, 3, 2, 0, 0, 19, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (20, 3, 1, 0, 0, 20, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (20, 3, 2, 0, 0, 20, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (21, 3, 1, 1, 1, 1, 1);
			INSERT INTO `".$layouts_table_name."` VALUES (21, 3, 2, 1, 1, 1, 1);
			INSERT INTO `".$layouts_table_name."` VALUES (22, 3, 1, 0, 0, 22, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (22, 3, 2, 0, 0, 22, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (23, 3, 1, 1, 1, 4, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (23, 3, 2, 1, 1, 4, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (24, 3, 1, 0, 0, 24, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (24, 3, 2, 0, 0, 24, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (25, 3, 1, 0, 0, 25, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (25, 3, 2, 0, 0, 25, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (26, 3, 1, 0, 0, 26, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (26, 3, 2, 0, 0, 26, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (27, 3, 1, 0, 0, 27, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (27, 3, 2, 0, 0, 27, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (28, 3, 1, 0, 0, 28, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (28, 3, 2, 0, 0, 28, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (30, 3, 1, 0, 0, 30, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (30, 3, 2, 0, 0, 30, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (31, 3, 1, 0, 0, 31, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (31, 3, 2, 0, 0, 31, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (32, 3, 1, 0, 0, 8, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (32, 3, 2, 1, 1, 5, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (33, 3, 1, 0, 0, 33, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (33, 3, 2, 1, 1, 8, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (34, 3, 1, 0, 0, 34, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (34, 3, 2, 0, 0, 34, 0);
			
			
			INSERT INTO `".$layouts_table_name."` VALUES (6, 6, 1, 0, 0, 7, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (6, 6, 2, 1, 0, 10, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (8, 6, 1, 1, 1, 2, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (8, 6, 2, 1, 1, 2, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (9, 6, 1, 0, 0, 9, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (9, 6, 2, 0, 0, 9, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (10, 6, 1, 0, 0, 10, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (10, 6, 2, 0, 0, 11, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (11, 6, 1, 0, 0, 11, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (11, 6, 2, 0, 0, 12, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (12, 6, 1, 1, 1, 3, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (12, 6, 2, 1, 1, 3, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (13, 6, 1, 1, 1, 5, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (13, 6, 2, 1, 1, 9, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (14, 6, 1, 0, 0, 14, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (14, 6, 2, 1, 1, 7, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (15, 6, 1, 0, 0, 15, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (15, 6, 2, 1, 1, 6, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (17, 6, 1, 1, 0, 6, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (17, 6, 2, 0, 0, 17, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (18, 6, 1, 0, 0, 18, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (18, 6, 2, 0, 0, 18, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (19, 6, 1, 0, 0, 19, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (19, 6, 2, 0, 0, 19, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (20, 6, 1, 0, 0, 20, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (20, 6, 2, 0, 0, 20, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (21, 6, 1, 1, 1, 1, 1);
			INSERT INTO `".$layouts_table_name."` VALUES (21, 6, 2, 1, 1, 1, 1);
			INSERT INTO `".$layouts_table_name."` VALUES (22, 6, 1, 0, 0, 22, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (22, 6, 2, 0, 0, 22, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (23, 6, 1, 1, 1, 4, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (23, 6, 2, 1, 1, 4, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (24, 6, 1, 0, 0, 24, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (24, 6, 2, 0, 0, 24, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (25, 6, 1, 0, 0, 25, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (25, 6, 2, 0, 0, 25, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (26, 6, 1, 0, 0, 26, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (26, 6, 2, 0, 0, 26, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (27, 6, 1, 0, 0, 27, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (27, 6, 2, 0, 0, 27, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (28, 6, 1, 0, 0, 28, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (28, 6, 2, 0, 0, 28, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (30, 6, 1, 0, 0, 30, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (30, 6, 2, 0, 0, 30, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (31, 6, 1, 0, 0, 31, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (31, 6, 2, 0, 0, 31, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (32, 6, 1, 0, 0, 8, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (32, 6, 2, 1, 1, 5, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (33, 6, 1, 0, 0, 33, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (33, 6, 2, 1, 1, 8, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (34, 6, 1, 0, 0, 34, 0);
			INSERT INTO `".$layouts_table_name."` VALUES (34, 6, 2, 0, 0, 34, 0);
			
			DROP TABLE IF EXISTS `".$css_table_name."`;
			CREATE TABLE `".$css_table_name."` (
			  `id` int(11) NOT NULL auto_increment,
			  `name` varchar(30) NOT NULL default '',
			  `class` varchar(30) NOT NULL default '',
			  PRIMARY KEY  (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

			-- 
			-- Dumping data for table `".$css_table_name."`
			-- 

			INSERT INTO `".$css_table_name."` VALUES (1, 'xl_search_form', 'xl_search_form');
			INSERT INTO `".$css_table_name."` VALUES (2, 'xl_form_message', 'xl_form_message');
			INSERT INTO `".$css_table_name."` VALUES (3, 'searchForm', 'searchForm');
			INSERT INTO `".$css_table_name."` VALUES (5, 'xl_wraper', 'xl_wraper');
			INSERT INTO `".$css_table_name."` VALUES (6, 'xl_advanced_search_link', 'xl_advanced_search_link');
			INSERT INTO `".$css_table_name."` VALUES (7, 'xl_all_locations_link', 'xl_all_locations_link');
			INSERT INTO `".$css_table_name."` VALUES (8, 'xl_search_results', 'xl_search_results');
			INSERT INTO `".$css_table_name."` VALUES (9, 'xl_search_locations', 'xl_search_locations');
			INSERT INTO `".$css_table_name."` VALUES (10, 'xl_result', 'xl_result');
			INSERT INTO `".$css_table_name."` VALUES (11, 'xl_result_item_map', 'xl_result_item_map');
			INSERT INTO `".$css_table_name."` VALUES (12, 'xl_result_location', 'xl_result_location');
			INSERT INTO `".$css_table_name."` VALUES (13, 'xl_result_item', 'xl_result_item');
			INSERT INTO `".$css_table_name."` VALUES (14, 'xl_search_footer', 'xl_search_footer');
			INSERT INTO `".$css_table_name."` VALUES (15, 'xl_results_title', 'xl_results_title');
			INSERT INTO `".$css_table_name."` VALUES (16, 'xl_results_value', 'xl_results_value');                        
			UPDATE  `".$config_table_name."` set form_code='".$advanced_form2."' where id=6 or id=10;
			";
	
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);		
	}
	add_option('xl_widget_url', '');
	add_option('xl_search_type', 1);
	add_option('xl_widget_title', "Dealer locator");
	add_option('xl_widget_zip', "Zip");
	add_option('xl_widget_desc', "Enter locator zip");
}
function xl_prevent_upgrade($opt) {
	global $update_class;
	$plugin = plugin_basename(__FILE__);
	if ( $opt && isset($opt->response[$plugin]) ) {		
		$update_class="update-message";		
	}
	return $opt;
}

?>