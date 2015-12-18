<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://wordpress.org/plugins/mkp-holacracy
 * @since      1.0.0
 *
 * @package    MKP_Holacracy
 * @subpackage MKP_Holacracy/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    MKP_Holacracy
 * @subpackage MKP_Holacracy/public
 * @author     Mickey Kay mickey@mickeykaycreative.com
 */
class MKP_Holacracy_Public {

	/**
	 * The main plugin instance.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      MKP_Holacracy    $plugin    The main plugin instance.
	 */
	private $plugin;

	/**
	 * The slug of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_slug    The slug of this plugin.
	 */
	private $plugin_slug;

	/**
	 * The display name of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The plugin display name.
	 */
	protected $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The instance of this class.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      MKP_Holacracy_Public    $instance    The instance of this class.
	 */
	private static $instance = null;

	/**
     * Creates or returns an instance of this class.
     *
     * @return    MKP_Holacracy_Public    A single instance of this class.
     */
    public static function get_instance( $plugin ) {

        if ( null == self::$instance ) {
            self::$instance = new self( $plugin );
        }

        return self::$instance;

    }

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_slug    The name of the plugin.
	 * @var      string    $version        The version of this plugin.
	 */
	public function __construct( $plugin ) {

		$this->plugin = $plugin;
		$this->plugin_slug = $this->plugin->get( 'slug' );
		$this->plugin_name = $this->plugin->get( 'name' );
		$this->version = $this->plugin->get( 'version' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		$min_suffix = $this->plugin->get_min_suffix();
		wp_enqueue_style( "{$this->plugin_slug}-public", plugin_dir_url( __FILE__ ) . "css/mkp-holacracy-public{$min_suffix}.css", array(), $this->version, 'all' );

	}

	/**
	 * Register the scripts for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		$min_suffix = $this->plugin->get_min_suffix();
		wp_enqueue_script( "{$this->plugin_slug}-public", plugin_dir_url( __FILE__ ) . "js/mkp-holacracy-public{$min_suffix}.js", array( 'jquery' ), $this->version, false );

	}

}
