<?php
/**
 * class-groups-post-access.php
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
 * Post access restrictions.
 */
class Groups_Post_Access {
	
	const POSTMETA_PREFIX = 'groups-';
	
	const READ_POST_CAPABILITY = "groups_read_post";
	const READ_POST_CAPABILITY_NAME = "Read Post";
	
	/**
	 * Create needed capabilities on plugin activation.
	 * Must be called explicitly or hooked into activation.
	 */
	public static function activate() {
		if ( !Groups_Capability::read_by_capability( self::READ_POST_CAPABILITY ) ) {
			Groups_Capability::create( array( "capability" => self::READ_POST_CAPABILITY ) );
		}
	}
	
	public static function init() {
		
		// for translation
		// @see self::READ_POST_CAPABILITY_NAME
		__( "Read Post", GROUPS_PLUGIN_DOMAIN );
		
		// post access
		add_filter( 'get_pages', array( __CLASS__, "get_pages" ), 1 );
		add_filter( 'the_posts', array( __CLASS__, "the_posts" ), 1, 2 );
		add_filter( 'wp_get_nav_menu_items', array( __CLASS__, "wp_get_nav_menu_items" ), 1, 3 );
		
		// content access
		add_filter( "get_the_excerpt", array( __CLASS__, "get_the_excerpt" ), 1 );
		add_filter( "the_content", array( __CLASS__, "the_content" ), 1 );
		
		// @todo these could be interesting to add later ...
		// add_filter( "plugin_row_meta", array( __CLASS__, "plugin_row_meta" ), 1 );
		// add_filter( "posts_join_paged", array( __CLASS__, "posts_join_paged" ), 1 );
		// add_filter( "posts_where_paged", array( __CLASS__, "posts_where_paged" ), 1 );
		}
	
	/**
	 * Filter pages by access capability.
	 * 
	 * @param array $pages
	 */
	public static function get_pages( $pages ) {
		
		$result = array();
		foreach ( $pages as $page ) {
			$read_post_capability = self::read( $page->ID, self::READ_POST_CAPABILITY );
			if ( $read_post_capability ) {
				$user_id = get_current_user_id();
				$groups_user = new Groups_User( $user_id );
				if ( $groups_user->can( self::READ_POST_CAPABILITY ) ) {
					$result[] = $page;
				}
			} else {
				$result[] = $page;
			}
		}
		return $result;
		
	}
	
	/**
	 * Filter posts by access capability.
	 * 
	 * @param array $posts list of posts
	 * @param WP_Query $query
	 */
	public static function the_posts( $posts, $query ) {
		
		$result = array();
		foreach ( $posts as $post ) {
			$read_post_capability = self::read( $post->ID, self::READ_POST_CAPABILITY );
			if ( $read_post_capability ) {
				$user_id = get_current_user_id();
				$groups_user = new Groups_User( $user_id );
				if ( $groups_user->can( self::READ_POST_CAPABILITY ) ) {
					$result[] = $post;
				}
			} else {
				$result[] = $post;
			}
		}
		return $result;
	}
	
	/**
	 * Filter menu items by access capability.
	 * 
	 * @param array $items
	 * @param mixed $menu
	 * @param array $args
	 */
	public static function wp_get_nav_menu_items( $items = null, $menu = null, $args = null ) {
		$result = array();
		foreach ( $items as $item ) {
			// @todo might want to check $item->object and $item->type first,
			// for example these are 'page' and 'post_type' for a page
			$read_post_capability = self::read( $item->object_id, self::READ_POST_CAPABILITY );
			if ( $read_post_capability ) {
				$user_id = get_current_user_id();
				$groups_user = new Groups_User( $user_id );
				if ( $groups_user->can( self::READ_POST_CAPABILITY ) ) {
					$result[] = $item;
				}
			} else {
				$result[] = $item;
			}
		}
		return $result;
	}
	
	/**
	 * Filter excerpt by access capability.
	 * 
	 * @param string $output
	 * @return $output if access granted, otherwise ''
	 */
	public static function get_the_excerpt( $output ) {
		global $post;
		$result = '';
		if ( isset( $post->ID ) ) {
			$read_post_capability = self::read( $post->ID, self::READ_POST_CAPABILITY );
			if ( $read_post_capability ) {
				$user_id = get_current_user_id();
				$groups_user = new Groups_User( $user_id );
				if ( $groups_user->can( self::READ_POST_CAPABILITY ) ) {
					$result = $output;
				}
			} else {
				$result = $output;
			}
		}
		return $result;
	}
	
	/**
	 * Filter content by access capability.
	 *
	 * @param string $output
	 * @return $output if access granted, otherwise ''
	 */
	public static function the_content( $output ) {
		global $post;
		$result = '';
		if ( isset( $post->ID ) ) {
			$read_post_capability = self::read( $post->ID, self::READ_POST_CAPABILITY );
			if ( $read_post_capability ) {
				$user_id = get_current_user_id();
				$groups_user = new Groups_User( $user_id );
				if ( $groups_user->can( self::READ_POST_CAPABILITY ) ) {
					$result = $output;
				}
			} else {
				$result = $output;
			}
		}
		return $result;
	}
	
	/**
	 * Adds an access capability requirement.
	 * 
	 * $map must contain 'post_id'
	 * 
	 * For now this only should be used to add the READ_POST_CAPABILITY which
	 * it does automatically. Nothing else is checked for granting access.
	 * 
	 * @param array $map
	 * @return true if the capability could be added to the post, otherwis false
	 */
	public static function create( $map ) {
		extract( $map );
		$result = false;

		if ( !isset( $capability ) ) {
			$capability = self::READ_POST_CAPABILITY;
		}
				
		if ( !empty( $post_id ) && !empty( $capability) ) {
			$result = update_post_meta( $post_id, self::POSTMETA_PREFIX . $capability, true );
		}
		return $result;
	}
	
	/**
	 * Returns true if the post requires the given capability to grant access.
	 * 
	 * Currently only READ_POST_CAPABILITY should be used, this is also taken
	 * as the default.
	 * 
	 * @param int $post_id
	 * @param string $capability capability label
	 * @return true if the capability is required, otherwise false
	 */
	public static function read( $post_id, $capability = self::READ_POST_CAPABILITY ) {
		return get_post_meta( $post_id, self::POSTMETA_PREFIX . $capability );
	}
	
	/**
	 * Currently does nothing, always returns false.
	 * 
	 * @param array $map
	 * @return false
	 */
	public static function update( $map ) {
		return false;
	}
	
	/**
	 * Removes a capability requirement from a post.
	 * 
	 * @param int $post_id
	 * @param string $capability
	 * @return true on success, otherwise false
	 */
	public static function delete( $post_id, $capability = self::READ_POST_CAPABILITY ) {
		$result = false;
		   if ( !empty( $post_id ) && !empty( $capability) ) {
			$result = delete_post_meta( $post_id, self::POSTMETA_PREFIX . $capability );
		}
		return $result;
	}
}
Groups_Post_Access::init();
