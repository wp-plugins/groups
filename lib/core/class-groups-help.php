<?php
/**
 * class-groups-help.php
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
class Groups_Help {
	
	public static function init() {
		add_action( 'contextual_help', array( __CLASS__, 'contextual_help' ), 10, 3 );
	}
	
	public static function contextual_help( $contextual_help, $screen_id, $screen ) {

		$show_groups_help = false;
		$help = '<h3><a href="http://www.itthinx.com/plugins/groups" target="_blank">Groups</a></h3>';
		$help .= '<p>';
		$help .= __( 'The complete documentation is available on the <a href="http://www.itthinx.com/plugins/groups" target="_blank">Groups plugin page</a>', GROUPS_PLUGIN_DOMAIN );
		$help .= '</p>';
	
		switch ( $screen_id ) {
			case 'toplevel_page_groups-admin' :
			case 'groups_page_groups-admin-groups':
				$show_groups_help = true;
				$help .= '<p>' . __( 'Here you can <strong>add</strong>, <strong>edit</strong> and <strong>remove</strong> groups.', GROUPS_PLUGIN_DOMAIN ) . '</p>';
				break;
			case 'groups_page_groups-admin-options' :
				$show_groups_help = true;
				break;
			default:
		}
	
		$help .= '<p>';
		$help .= __( 'If you require <em>consulting services</em>, <em>support</em> or <em>customization</em>, you may <a href="http://www.itthinx.com/" target="_blank">contact me here</a>.', GROUPS_PLUGIN_DOMAIN );
		$help .= '</p>';
		$help .= '<p>';
		$help .= __( 'If you find this plugin useful, please consider making a donation:', GROUPS_PLUGIN_DOMAIN );
		$help .= self::donate( false, true );
		$help .= '</p>';
	
		if ( $show_groups_help ) {
			return $help;
		} else {
			return $contextual_help;
		}
	}

	/**
	 * Returns or renders the footer.
	 * @param boolean $render
	 */
	public static function footer( $render = true ) {
		$footer = '<div class="groups-footer">' .
			'<p>' .
			__( 'Thank you for using the <a href="http://www.itthinx.com/plugins/groups" target="_blank">Groups</a> plugin by <a href="http://www.itthinx.com" target="_blank">itthinx</a>.', GROUPS_PLUGIN_DOMAIN ) .
			'</p>' .
			'<p>' .
			__( 'If you require <em>consulting services</em>, <em>support</em> or <em>customization</em>, you may <a href="http://www.itthinx.com/" target="_blank">contact me here</a>.', GROUPS_PLUGIN_DOMAIN ) .
			'</p>' .
			'<p>' .
			__( 'If you find this plugin useful, please consider making a donation:', GROUPS_PLUGIN_DOMAIN ) .
			self::donate( false ) .
			'</p>' .
			'</div>';
		$footer = apply_filters( 'groups_footer', $footer );
		if ( $render ) {
			echo $footer;
		} else {
			return $footer;
		}
	}

	/**
	 * Render or return a donation button.
	 * Thanks for supporting me!
	 * @param boolean $render
	 */
	public static function donate( $render = true, $small = false ) {
		if ( !$small ) {
			$donate = '
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="AGZGDNAD9L4YS">
				<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img alt="" border="0" src="https://www.paypalobjects.com/es_ES/i/scr/pixel.gif" width="1" height="1">
				</form>
				 ';
		} else {
			$donate = '
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_donations">
				<input type="hidden" name="business" value="itthinx@itthinx.com">
				<input type="hidden" name="lc" value="US">
				<input type="hidden" name="item_name" value="Support WordPress Plugins from itthinx">
				<input type="hidden" name="item_number" value="WordPress Plugins">
				<input type="hidden" name="no_note" value="0">
				<input type="hidden" name="currency_code" value="EUR">
				<input type="hidden" name="bn" value="PP-DonationsBF:btn_donate_SM.gif:NonHostedGuest">
				<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img alt="" border="0" src="https://www.paypalobjects.com/es_ES/i/scr/pixel.gif" width="1" height="1">
				</form>			
				';
		}
		if ( $render ) {
			echo $donate;
		} else {
			return $donate;
		}
	}
}
Groups_Help::init();
