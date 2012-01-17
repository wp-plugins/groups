<?php
/**
 * class-groups-shortcodes.php
 *
 * Copyright (c) "kento" Karim Rahimpur www.itthinx.com
 *
 * This code is released under the GNU General Public License.
 * See COPYRIGHT.txt and LICENSE.txt.
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This header and all notices must be kept intact.
 *
 * @author Karim Rahimpur
 * @package groups
 * @since groups 1.0.0
 */
class Groups_Shortcodes {
	
	/**
	 * Adds shortcodes.
	 */
	public static function init() {
		// group info
		add_shortcode( 'groups_group_info', array( __CLASS__, 'groups_group_info' ) );
	}
	
	/**
	 * Renders information about a group.
	 * Attributes:
	 * - "group" : group name or id
	 * - "show" : what to show, can be "name", "description", "count" 
	 * 
	 * @param array $atts attributes
	 * @param string $content content to render
	 * @return rendered information
	 */
	public static function groups_group_info( $atts, $content = null ) {
		global $wpdb;
		$output = "";
		$options = shortcode_atts(
			array(
				'group' => '',
				'show' => '',
				'format' => '',
				'single' => '1',
				'plural' => '%d'
			),
			$atts
		);
		$group = trim( $options['group'] );
		$current_group = Groups_Group::read( $group );
		if ( !$current_group ) {
			$current_group = Groups_Group::read_by_name( $group );
		}
		if ( $current_group ) {
			switch( $options['show'] ) {
				case 'name' :
					$output .= wp_filter_nohtml_kses( $current_group->name );
					break;
				case 'description' :
					$output .= wp_filter_nohtml_kses( $current_group->description );
					break;
				case 'count' :
					$user_group_table = _groups_get_tablename( "user_group" );
					$count = $wpdb->get_var( $wpdb->prepare(
						"SELECT COUNT(*) FROM $user_group_table WHERE group_id = %d",
						Groups_Utility::id( $current_group->group_id )
					) );
					if ( $count === null ) {
						$count = 0;
					} else {
						$count = intval( $count );
					}
					$output .= _n( $options['single'], sprintf( $options['plural'], $count ), $count, GROUPS_PLUGIN_DOMAIN );
					break;
			}
		}
		return $output;
	}
}
Groups_Shortcodes::init();
