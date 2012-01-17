<?php
/**
 * class-groups-access-meta-boxes.php
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

/**
 * Adds meta boxes to edit screens.
 * 
 * @link http://codex.wordpress.org/Function_Reference/add_meta_box
 */
class Groups_Access_Meta_Boxes {
	
	const NONCE = 'groups-meta-box-nonce';
	const SET_CAPABILITY = 'set-capability';
	const READ_ACCESS = 'read-access';
	
	/**
	 * Hooks for capabilities meta box and saving options.
	 */
	public static function init() {
		add_action( 'add_meta_boxes', array( __CLASS__, "add_meta_boxes" ) );
		add_action( 'save_post', array( __CLASS__, "save_post" ) );
	}
	
	/**
	 * Triggered by init() to add capability meta box. 
	 */
	public static function add_meta_boxes() {
		global $wp_version;
		if ( $wp_version < 3.3 ) {
			$post_types = get_post_types();
			foreach ( $post_types as $post_type ) {
				add_meta_box(
					"groups-access",
					__( "Access restrictions", GROUPS_PLUGIN_DOMAIN ),
					array( __CLASS__, "capability" ),
					$post_type,
					"side",
					"high"
				);
			}
		} else {
			add_meta_box(
				"groups-access",
				__( "Access restrictions", GROUPS_PLUGIN_DOMAIN ),
				array( __CLASS__, "capability" ),
				null,
				"side",
				"high"
			);
		}
	}
	
	/**
	 * Render meta box for capabilities.
	 * 
	 * @see do_meta_boxes()
	 * 
	 * @param Object $object
	 * @param Object $box
	 */
	public static function capability( $object = null, $box = null ) {
		
		$output = "";
				
		$post_id = isset( $object->ID ) ? $object->ID : null;
		$post_type = isset( $object->post_type ) ? $object->post_type : null;
		$post_singular_name = __( "Post", GROUPS_PLUGIN_DOMAIN );
		if ( $post_type !== null ) {
			$post_type_object = get_post_type_object( $post_type );
			$labels = isset( $post_type_object->labels ) ? $post_type_object->labels : null;
			if ( $labels !== null ) {
				if ( isset( $labels->singular_name ) )  {
					$post_singular_name = __( $labels->singular_name );
				}
			}
		}
		$read_access = false;
		if ( $post_id !== null ) {
			if ( Groups_Post_Access::read( $post_id, Groups_Post_Access::READ_POST_CAPABILITY ) ) {
				$read_access = true;
			}
		}
		if ( $read_access ) {
			$checked = ' checked="checked" ';
		} else {
			$checked = '';
		}
		
		$output .= '<input type="checkbox" name="' . self::READ_ACCESS . '" ' . $checked . ' />';
		$output .= '&nbsp;';
		$output .=  '<label for="' . self::READ_ACCESS . '">';
		$output .= __( "Enforce read access", GROUPS_PLUGIN_DOMAIN );
		$output .= '</label> ';
		$output .= '<p class="description">';
		$output .= sprintf( __( "Only groups or users that have the <b>%s</b> capability are allowed to read this %s.", GROUPS_PLUGIN_DOMAIN ), Groups_Post_Access::READ_POST_CAPABILITY, $post_singular_name );
		$output .= '</p>';
		$output .= wp_nonce_field( self::SET_CAPABILITY, self::NONCE, true, false );
		echo $output;
	}
	
	/**
	 * Save capability options.
	 * 
	 * @param int $post_id
	 * @param mixed $post post data
	 */
	public static function save_post( $post_id = null, $post = null ) {
		if ( ( defined( "DOING_AUTOSAVE" ) && DOING_AUTOSAVE ) ) {
		} else {
			if ( isset( $_POST[self::NONCE] ) && wp_verify_nonce( $_POST[self::NONCE], self::SET_CAPABILITY ) ) {
				$post_type = isset( $_POST["post_type"] ) ? $_POST["post_type"] : null;
				if ( $post_type !== null ) {
					if ( current_user_can( 'edit_'.$post_type ) ) {
						$read_access = isset( $_POST[self::READ_ACCESS] ) ? !empty( $_POST[self::READ_ACCESS] ) : false;
						if ( $read_access ) {
							Groups_Post_Access::create( array( "post_id" => $post_id ) );
						} else {
							Groups_Post_Access::delete( $post_id );
						}
					}
				}
			}
		}
	}
	
} 
Groups_Access_Meta_Boxes::init();
