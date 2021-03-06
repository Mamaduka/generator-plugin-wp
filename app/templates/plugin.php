<?php
/**
* Plugin Name: <%= name %>
* Plugin URI:  <%= homepage %>
* Description: <%= description %>
* Version:     <%= version %>
* Author:      <%= author %>
* Author URI:  <%= authorurl %>
* Donate link: <%= homepage %>
* License:     <%= license %>
* Text Domain: <%= slug %>
 * Domain Path: /languages
 */

/**
 * Copyright (c) <%= year %> <%= author %> (email : <%= authoremail %>)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Built using generator-plugin-wp
 */

<% if ( autoloader == 'Basic' ) { %>
/**
 * Autoloads files with classes when needed
 * @since  0.1.0
 * @param  string $class_name Name of the class being requested
 */
function <%= prefix %>_autoload_classes( $class_name ) {
	if ( class_exists( $class_name, false ) || false === stripos( $class_name, '<%= classprefix %>' ) ) {
		return;
	}
	$filename = strtolower( str_ireplace(
		array( '<%= classprefix %>', '_' ),
		array( '', '-' ),
		$class_name
	) );

	<%= classname %>::include_file( $filename );
}
spl_autoload_register( '<%= prefix %>_autoload_classes' );
<% } else if ( autoloader == 'Composer' ) { %>
// User composer autoload.
require 'vendor/autoload_52.php';
<% } else { %>
	// Include additional php files here
	// require 'includes/admin.php';
<% } %>

/**
 * Main initiation class
 */
class <%= classname %> {

	const VERSION = '<%= version %>';

	protected $url      = '';
	protected $path     = '';
	protected $basename = '';
	protected static $single_instance = null;

	/**
	 * Creates or returns an instance of this class.
	 * @since  0.1.0
	 * @return <%= classname %> A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$single_instance ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;
	}

	/**
	 * Sets up our plugin
	 * @since  <%= version %>
	 */
	protected function __construct() {
		$this->basename = plugin_basename( __FILE__ );
		$this->url      = plugin_dir_url( __FILE__ );
		$this->path     = plugin_dir_path( __FILE__ );

		$instance->plugin_classes();
		$instance->hooks();
	}

	/**
	 * Attach other plugin classes to the base plugin class.
	 * @since <%= version %>
	 */
	function plugin_classes() {
		// Attach other plugin classes to the base plugin class.
		// $this->admin = new <%= classprefix %>Admin( $this );
	}

	/**
	 * Add hooks and filters
	 * @since <%= version %>
	 */
	public function hooks() {
		register_activation_hook( __FILE__, array( $this, '_activate' ) );
		register_deactivation_hook( __FILE__, array( $this, '_deactivate' ) );

		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Activate the plugin
	 * @since  <%= version %>
	 */
	function _activate() {
		// Make sure any rewrite functionality has been loaded
		flush_rewrite_rules();
	}

	/**
	 * Deactivate the plugin
	 * Uninstall routines should be in uninstall.php
	 * @since  <%= version %>
	 */
	function _deactivate() {}

	/**
	 * Init hooks
	 * @since  <%= version %>
	 * @return null
	 */
	public function init() {
		if ( $this->check_requirements() ) {
			$locale = apply_filters( 'plugin_locale', get_locale(), '<%= slug %>' );
			load_textdomain( '<%= slug %>', WP_LANG_DIR . '/<%= slug %>/<%= slug %>-' . $locale . '.mo' );
			load_plugin_textdomain( '<%= slug %>', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}
	}

	/**
	 * Check that all plugin requirements are met
	 * @since  <%= version %>
	 * @return boolean
	 */
	public static function meets_requirements() {
		// Do checks for required classes / functions
		// function_exists('') & class_exists('')

		// We have met all requirements
		return true;
	}

	/**
	 * Check if the plugin meets requirements and
	 * disable it if they are not present.
	 * @since  <%= version %>
	 * @return boolean result of meets_requirements
	 */
	public function check_requirements() {
		if ( ! $this->meets_requirements() ) {
			// Display our error
			echo '<div id="message" class="error">';
			echo '<p>' . sprintf( __( '<%= name %> is missing requirements and has been <a href="%s">deactivated</a>. Please make sure all requirements are available.', '<%= slug %>' ), admin_url( 'plugins.php' ) ) . '</p>';
			echo '</div>';
			// Deactivate our plugin
			deactivate_plugins( $this->basename );

			return false;
		}

		return true;
	}

	/**
	 * Magic getter for our object.
	 *
	 * @since  <%= version %>
	 * @param string $field
	 * @throws Exception Throws an exception if the field is invalid.
	 * @return mixed
	 */
	public function __get( $field ) {
		switch ( $field ) {
			case 'version':
				return self::VERSION;
			case 'basename':
			case 'url':
			case 'path':
				return $this->$field;
			default:
				throw new Exception( 'Invalid '. __CLASS__ .' property: ' . $field );
		}
	}<% if ( autoloader == 'Basic' ) { %>

	/**
	 * Include a file from the includes directory
	 * @since  <%= version %>
	 * @param  string $filename Name of the file to be included
	 */
	public static function include_file( $filename ) {
		$file = self::dir( 'includes/'. $filename .'.php' );
		if ( file_exists( $file ) ) {
			return include_once( $file );
		}
	}<% } %>
}

/**
 * Grab the <%= classname %> object and return it.
 * Wrapper for <%= classname %>::get_instance()
 */
function <%= prefix %>() {
	return <%= classname %>::get_instance();
}

// Kick it off
<%= prefix %>();
