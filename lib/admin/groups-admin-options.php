<?php
/**
 * groups-admin-options.php
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
 * @var string options form nonce name
 */
define( 'GROUPS_ADMIN_OPTIONS_NONCE', 'groups-admin-nonce' );

function groups_admin_options() {
	
	global $wpdb, $wp_roles;
	
	if ( !current_user_can( GROUPS_ADMINISTER_OPTIONS ) ) {
		wp_die( __( 'Access denied.', GROUPS_PLUGIN_DOMAIN ) );
	}
	
	echo
		'<div>' .
			'<h2>' .
				__( 'Groups options', GROUPS_PLUGIN_DOMAIN ) .
			'</h2>' .
		'</div>';
	
	$caps = array(
		GROUPS_ACCESS_GROUPS	  => __( 'Access Groups', GROUPS_PLUGIN_DOMAIN ),
		GROUPS_ADMINISTER_GROUPS  => __( 'Administer Groups', GROUPS_PLUGIN_DOMAIN ),
		GROUPS_ADMINISTER_OPTIONS => __( 'Administer Groups plugin options', GROUPS_PLUGIN_DOMAIN ),
	);
	
	//
	// handle options form submission
	//
	if ( isset( $_POST['submit'] ) ) {
		if ( wp_verify_nonce( $_POST[GROUPS_ADMIN_OPTIONS_NONCE], 'admin' ) ) {
			
			// admin override
			if ( empty( $_POST[GROUPS_ADMINISTRATOR_ACCESS_OVERRIDE] ) ) {
				$admin_override = false;
			} else {
				$admin_override = true;
			}
			// Don't move this to the plugin options, access will be faster
			update_option( GROUPS_ADMINISTRATOR_ACCESS_OVERRIDE, $admin_override );
			
			// tree view
			if ( !empty( $_POST[GROUPS_SHOW_TREE_VIEW] ) ) {
				Groups_Options::update_option( GROUPS_SHOW_TREE_VIEW, true );
			} else {
				Groups_Options::update_option( GROUPS_SHOW_TREE_VIEW, false );
			}
			
			// roles & capabilities
			$rolenames = $wp_roles->get_names();
			foreach ( $rolenames as $rolekey => $rolename ) {
				$role = $wp_roles->get_role( $rolekey );
				foreach ( $caps as $capkey => $capname ) {
					$role_cap_id = $rolekey.'-'.$capkey;
					if ( !empty($_POST[$role_cap_id] ) ) {
						$role->add_cap( $capkey );
					} else {
						$role->remove_cap( $capkey );
					}
				}
			}
			Groups_Controller::assure_capabilities();
			
			// delete data
			if ( !empty( $_POST['delete-data'] ) ) {
				Groups_Options::update_option( 'groups_delete_data', true );
			} else {
				Groups_Options::update_option( 'groups_delete_data', false );
			}
		}
	}
	
	$admin_override = get_option( GROUPS_ADMINISTRATOR_ACCESS_OVERRIDE, GROUPS_ADMINISTRATOR_ACCESS_OVERRIDE_DEFAULT );
	
	$show_tree_view = Groups_Options::get_option( GROUPS_SHOW_TREE_VIEW, GROUPS_SHOW_TREE_VIEW_DEFAULT );
	
	$rolenames = $wp_roles->get_names();
	$caps_table = '<table class="groups-permissions">';
	$caps_table .= '<thead>';
	$caps_table .= '<tr>';
	$caps_table .= '<td class="role">';
	$caps_table .= __( 'Role', GROUPS_PLUGIN_DOMAIN );
	$caps_table .= '</td>';
	foreach ( $caps as $cap ) {
		$caps_table .= '<td class="cap">';
		$caps_table .= $cap;
		$caps_table .= '</td>';				
	}
	
	$caps_table .= '</tr>';
	$caps_table .= '</thead>';
	$caps_table .= '<tbody>';
	foreach ( $rolenames as $rolekey => $rolename ) {
		$role = $wp_roles->get_role( $rolekey );
		$caps_table .= '<tr>';
		$caps_table .= '<td>';
		$caps_table .= translate_user_role( $rolename );
		$caps_table .= '</td>';
		foreach ( $caps as $capkey => $capname ) {
						
			if ( $role->has_cap( $capkey ) ) {
				$checked = ' checked="checked" ';
			} else {
				$checked = '';
			}
			 
			$caps_table .= '<td class="checkbox">';
			$role_cap_id = $rolekey.'-'.$capkey;
			$caps_table .= '<input type="checkbox" name="' . $role_cap_id . '" id="' . $role_cap_id . '" ' . $checked . '/>';
			$caps_table .= '</td>';
		}
		$caps_table .= '</tr>';
	}
	$caps_table .= '</tbody>';
	$caps_table .= '</table>';
	
	$delete_data = Groups_Options::get_option( 'groups_delete_data', false );
	
	//
	// print the options form
	//
	echo
		'<form action="" name="options" method="post">' .		
			'<div>' .
				'<h3>' . __( 'Administrator Access Override', GROUPS_PLUGIN_DOMAIN ) . '</h3>' .
				'<p>' .
					'<input name="' . GROUPS_ADMINISTRATOR_ACCESS_OVERRIDE . '" type="checkbox" ' . ( $admin_override ? 'checked="checked"' : '' ) . '/>' .
					'<label for="' . GROUPS_ADMINISTRATOR_ACCESS_OVERRIDE . '">' . __( 'Administrators override all access permissions derived from Groups capabilities.', GROUPS_PLUGIN_DOMAIN ) . '</label>' .
				'</p>';
	
	echo
				'<h3>' . __( 'Tree view', GROUPS_PLUGIN_DOMAIN ) . '</h3>' .
					'<p>' .
						'<input name="' . GROUPS_SHOW_TREE_VIEW . '" type="checkbox" ' . ( $show_tree_view ? 'checked="checked"' : '' ) . '/>' .
						'<label for="' . GROUPS_SHOW_TREE_VIEW . '">' . __( 'Show the Groups tree view.', GROUPS_PLUGIN_DOMAIN ) . '</label>' .
					'</p>';	
	echo
				'<h3>' . __( 'Permissions', GROUPS_PLUGIN_DOMAIN ) . '</h3>' .
				'<p>' . __( 'These permissions apply to Groups management. They do not apply to access permissions derived from Groups capabilities.', GROUPS_PLUGIN_DOMAIN ) . '</p>' .
				$caps_table .
				'<p class="description">' .
				__( 'A minimum set of permissions will be preserved.', GROUPS_PLUGIN_DOMAIN ) .
				'<br/>' .
				__( 'If you lock yourself out, please ask an administrator to help.', GROUPS_PLUGIN_DOMAIN ) .
				'</p>';	
	echo
				'<h3>' . __( 'Deactivation and data persistence', GROUPS_PLUGIN_DOMAIN ) . '</h3>' .
				'<p>' .
					'<input name="delete-data" type="checkbox" ' . ( $delete_data ? 'checked="checked"' : '' ) . '/>' .
					'<label for="delete-data">' . __( 'Delete all plugin data on deactivation', GROUPS_PLUGIN_DOMAIN ) . '</label>' .
				'</p>' .
				'<p class="description warning">' .
						__( 'CAUTION: If this option is active while the plugin is deactivated, ALL plugin settings and data will be DELETED. If you are going to use this option, now would be a good time to make a backup. By enabling this option you agree to be solely responsible for any loss of data or any other consequences thereof.', GROUPS_PLUGIN_DOMAIN ) .
				'</p>';
	echo
				'<p>' .
					wp_nonce_field( 'admin', GROUPS_ADMIN_OPTIONS_NONCE, true, false ) .
					'<input type="submit" name="submit" value="' . __( 'Save', GROUPS_PLUGIN_DOMAIN ) . '"/>' .
				'</p>' .
			'</div>' .
		'</form>';
	Groups_Help::footer();
}
?>