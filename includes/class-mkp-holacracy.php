<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       http://wordpress.org/plugins/mkp-holacracy
 * @since      1.0.0
 *
 * @package    MKP_Holacracy
 * @subpackage MKP_Holacracy/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    MKP_Holacracy
 * @subpackage MKP_Holacracy/includes
 * @author     Mickey Kay mickey@mickeykaycreative.com
 */
class MKP_Holacracy {

	/**
	 * The main plugin file.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_file    The main plugin file.
	 */
	protected $plugin_file;

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      MKP_Holacracy_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $slug    The string used to uniquely identify this plugin.
	 */
	protected $slug;

	/**
	 * The display name of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $name    The plugin display name.
	 */
	protected $name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
     * Plugin options.
     *
     * @since  1.0.0
     *
     * @var    string
     */
    protected $options;

	/**
	 * The instance of this class.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      MKP_Holacracy    $instance    The instance of this class.
	 */
	private static $instance = null;

	/**
     * Creates or returns an instance of this class.
     *
     * @return    MKP_Holacracy    A single instance of this class.
     */
    public static function get_instance( $args = array() ) {

        if ( null == self::$instance ) {
            self::$instance = new self( $args );
        }

        return self::$instance;

    }

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $args ) {

		$this->plugin_file = $args['plugin_file'];

		$this->slug = 'mkp-holacracy';
		$this->name = __( 'MKP Holacracy', 'mkp-holacracy' );
		$this->version = '1.0.0';
		$this->options = get_option( $this->slug );

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_shared_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - MKP_Holacracy_Loader. Orchestrates the hooks of the plugin.
	 * - MKP_Holacracy_i18n. Defines internationalization functionality.
	 * - MKP_Holacracy_Admin. Defines all hooks for the dashboard.
	 * - MKP_Holacracy_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mkp-holacracy-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mkp-holacracy-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-mkp-holacracy-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-mkp-holacracy-public.php';

		/**
		 * Include plugin metabox class.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-mkp-holacracy-metaboxes.php';

		/**
		 * Include CMB2 library.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/webdevstudios/cmb2/init.php';

		$this->loader = new MKP_Holacracy_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the MKP_Holacracy_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new MKP_Holacracy_i18n();
		$plugin_i18n->set_domain( $this->slug );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = MKP_Holacracy_Admin::get_instance( $this );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Add settings page and fields.
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_settings_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'add_settings_fields' );

		// Add and populate custom admin columns.
		$this->loader->add_action( 'manage_mkp-action-item_posts_columns', $plugin_admin, 'edit_admin_columns' );
		$this->loader->add_action( 'manage_mkp-action-item_posts_custom_column', $plugin_admin, 'do_custom_column_output', 10, 2 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = MKP_Holacracy_Public::get_instance( $this );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to both the admin and public-facing
	 * functionality of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_shared_hooks() {

		$plugin_shared = $this;

		// Register post types.
		$this->loader->add_action( 'init', $plugin_shared, 'register_post_types' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    MKP_Holacracy_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Get any plugin property.
	 *
	 * @since     1.0.0
	 * @return    mixed    The plugin property.
	 */
	public function get( $property = '' ) {
		return $this->$property;
	}

	/**
	 * Return minified suffix unless SCRIPT_DEBUG is enabled.
	 *
	 * @since 1.0.0
	 *
	 * @return string Null or .min, depending on whether debugging is enabled.
	 */
	public function get_min_suffix() {
		return ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	}

	/**
	 * Register various post types for plugin.
	 *
	 * @since 1.0.0
	 */
	public function register_post_types() {


		/**
		 * Register Action Item post type.
		 */
		register_post_type( 'mkp-role', array(
			'labels'			=> array(
			    'name'					=> __( 'Roles', 'mkp-holacracy' ),
				'singular_name'			=> __( 'Role', 'mkp-holacracy' ),
			    'add_new'				=> __( 'Add New' , 'mkp-holacracy' ),
			    'add_new_item'			=> __( 'Add New Role' , 'mkp-holacracy' ),
			    'edit_item'				=> __( 'Edit Role' , 'mkp-holacracy' ),
			    'new_item'				=> __( 'New Role' , 'mkp-holacracy' ),
			    'view_item'				=> __( 'View Role', 'mkp-holacracy' ),
			    'all_items'             => __( 'All Roles', 'mkp-holacracy' ),
			    'search_items'			=> __( 'Search Roles', 'mkp-holacracy' ),
			    'not_found'				=> __( 'No Roles found', 'mkp-holacracy' ),
			    'not_found_in_trash'	=> __( 'No Roles found in Trash', 'mkp-holacracy' ),
			),
			'public'			=> true,
			'show_ui'			=> true,
			'show_in_menu'		=> true,
			'menu_position'		=> 100,
			'menu_icon'         => 'dashicons-universal-access',
			'capability_type'	=> 'page',
			'hierarchical'		=> true,
			'rewrite'			=> false,
			'supports' 			=> array('title'),
		));

		/**
		 * Register Project post type.
		 */
		register_post_type( 'mkp-project', array(
			'labels'			=> array(
			    'name'					=> __( 'Projects', 'mkp-holacracy' ),
				'singular_name'			=> __( 'Project', 'mkp-holacracy' ),
			    'add_new'				=> __( 'Add New' , 'mkp-holacracy' ),
			    'add_new_item'			=> __( 'Add New Project' , 'mkp-holacracy' ),
			    'edit_item'				=> __( 'Edit Project' , 'mkp-holacracy' ),
			    'new_item'				=> __( 'New Project' , 'mkp-holacracy' ),
			    'view_item'				=> __( 'View Project', 'mkp-holacracy' ),
			    'all_items'             => __( 'All Projects', 'mkp-holacracy' ),
			    'search_items'			=> __( 'Search Projects', 'mkp-holacracy' ),
			    'not_found'				=> __( 'No Projects found', 'mkp-holacracy' ),
			    'not_found_in_trash'	=> __( 'No Projects found in Trash', 'mkp-holacracy' ),
			),
			'public'			=> true,
			'show_ui'			=> true,
			'show_in_menu'		=> true,
			'menu_position'		=> 100,
			'menu_icon'         => 'dashicons-hammer',
			'capability_type'	=> 'page',
			'hierarchical'		=> true,
			'rewrite'			=> false,
			'supports' 			=> array('title'),
		));

		/**
		 * Register Action Items post type.
		 */
		register_post_type( 'mkp-action-item', array(
			'labels'			=> array(
			    'name'					=> __( 'Action Items', 'mkp-holacracy' ),
				'singular_name'			=> __( 'Action Item', 'mkp-holacracy' ),
			    'add_new'				=> __( 'Add New' , 'mkp-holacracy' ),
			    'add_new_item'			=> __( 'Add New Action Item' , 'mkp-holacracy' ),
			    'edit_item'				=> __( 'Edit Action Item' , 'mkp-holacracy' ),
			    'new_item'				=> __( 'New Action Item' , 'mkp-holacracy' ),
			    'view_item'				=> __( 'View Action Item', 'mkp-holacracy' ),
			    'all_items'             => __( 'All Action Items', 'mkp-holacracy' ),
			    'search_items'			=> __( 'Search Action Items', 'mkp-holacracy' ),
			    'not_found'				=> __( 'No Action Items found', 'mkp-holacracy' ),
			    'not_found_in_trash'	=> __( 'No Action Items found in Trash', 'mkp-holacracy' ),
			),
			'public'			=> true,
			'show_ui'			=> true,
			'show_in_menu'		=> true,
			'menu_position'		=> 100,
			'menu_icon'         => 'dashicons-controls-forward',
			'capability_type'	=> 'page',
			'hierarchical'		=> true,
			'rewrite'			=> false,
			'supports' 			=> array('title'),
		));

	}

}
