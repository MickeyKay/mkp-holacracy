<?php

/**
 * Metabox functionality - generated with CMB2.
 *
 * @link       http://wordpress.org/plugins/mkp-holacracy
 * @since      1.0.0
 *
 * @package    MKP_Holacracy
 * @subpackage MKP_Holacracy/admin
 */

class MKP_Holacracy_Metaboxes {

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
	 * @var      MKP_Holacracy_Admin    $instance    The instance of this class.
	 */
	private static $instance = null;

	/**
     * Creates or returns an instance of this class.
     *
     * @return    MKP_Holacracy_Admin    A single instance of this class.
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
	 * @var      string    $plugin_slug       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin ) {

		$this->plugin = $plugin;
		$this->plugin_slug = $this->plugin->get( 'slug' );
		$this->plugin_name = $this->plugin->get( 'name' );
		$this->version = $this->plugin->get( 'version' );
		$this->options = $this->plugin->get( 'options' );

		// Setup metaboxes.
		add_action( 'cmb2_admin_init', array( $this, 'register_role_metabox' ) );
		add_action( 'cmb2_admin_init', array( $this, 'register_project_metabox' ) );
		add_action( 'cmb2_admin_init', array( $this, 'register_action_item_metabox' ) );

		// Setup custom field types.
		add_action( 'cmb2_render_status_select', array( $this, 'render_status_select' ), 10, 4 );

	}

	/**
	 * Add Role metabox.
	 *
	 * @since 1.0.0
	 */
	public function register_role_metabox() {

		// Start with an underscore to hide fields from custom fields list
		$prefix = "_{$this->plugin_slug}_";

		$metabox = new_cmb2_box( array(
			'id'            => $prefix . 'metabox',
			'title'         => __( 'Details', 'mkp-holacracy' ),
			'object_types'  => array( 'mkp-role' ),
		) );

		$metabox->add_field( array(
			'name'             => __( 'Soul', 'mkp-holacracy' ),
			'id'               => $prefix . 'soul',
			'type'             => 'select',
			'show_option_none' => true,
			'options'          => $this->get_users(),
		) );

		$metabox->add_field( array(
			'name'       => __( 'Purpose', 'mkp-holacracy' ),
			'id'         => $prefix . 'purpose',
			'type'       => 'text',
		) );

		$metabox->add_field( array(
			'name'       => __( 'Domain', 'mkp-holacracy' ),
			'id'         => $prefix . 'domain',
			'type'       => 'text',
			'repeatable' => true,
		) );

		$metabox->add_field( array(
			'name'       => __( 'Accountabilities', 'mkp-holacracy' ),
			'id'         => $prefix . 'accountabilities',
			'type'       => 'text',
			'repeatable' => true,
		) );

	}

	/**
	 * Add Project metabox.
	 *
	 * @since 1.0.0
	 */
	public function register_project_metabox() {

		// Start with an underscore to hide fields from custom fields list
		$prefix = "_{$this->plugin_slug}_";

		$metabox = new_cmb2_box( array(
			'id'            => $prefix . 'metabox',
			'title'         => __( 'Details', 'mkp-holacracy' ),
			'object_types'  => array( 'mkp-project' ),
		) );

		$metabox->add_field( array(
			'name'             => __( 'Status', 'mkp-holacracy' ),
			'id'               => $prefix . 'status',
			'type'             => 'status_select',
			'options_cb'       => array( $this, 'get_project_statuses' ),
		) );

		$metabox->add_field( array(
			'name'             => __( 'Role', 'mkp-holacracy' ),
			'id'               => $prefix . 'role',
			'type'             => 'select',
			'show_option_none' => true,
			'options'          => $this->get_mkp_roles(),
		) );

		$metabox->add_field( array(
			'name'       => __( 'Next Step / Resolution', 'mkp-holacracy' ),
			'id'         => $prefix . 'next_step_resolution',
			'type'       => 'text',
		) );

		$metabox->add_field( array(
			'name' => __( 'Due Date', 'cmb2' ),
			'id'   => $prefix . 'due_date',
			'type' => 'text_date',
		) );

		$metabox->add_field( array(
			'name' => __( 'Date Completed', 'cmb2' ),
			'id'   => $prefix . 'date_completed',
			'type' => 'text_date',
		) );

	}

	/**
	 * Add Action Item metabox.
	 *
	 * @since 1.0.0
	 */
	public function register_action_item_metabox() {
echo '+++++++';
		// Start with an underscore to hide fields from custom fields list
		$prefix = "_{$this->plugin_slug}_";

		$metabox = new_cmb2_box( array(
			'id'            => $prefix . 'metabox',
			'title'         => __( 'Details', 'mkp-holacracy' ),
			'object_types'  => array( 'mkp-action-item' ),
		) );

		$metabox->add_field( array(
			'name'             => __( 'Status', 'mkp-holacracy' ),
			'id'               => $prefix . 'status',
			'type'             => 'status_select',
			'options_cb'       => array( $this, 'get_project_statuses' ),
		) );

		$metabox->add_field( array(
			'name'             => __( 'Role', 'mkp-holacracy' ),
			'id'               => $prefix . 'role',
			'type'             => 'select',
			'show_option_none' => true,
			'options'          => $this->get_mkp_roles(),
		) );

		$metabox->add_field( array(
			'name'       => __( 'Next Step / Resolution', 'mkp-holacracy' ),
			'id'         => $prefix . 'next_step_resolution',
			'type'       => 'textarea_small',
		) );

		$metabox->add_field( array(
			'name' => __( 'Date Completed', 'cmb2' ),
			'id'   => $prefix . 'date_completed',
			'type' => 'text_date',
		) );

	}

	/*===========================================
	 * Custom Field Types
	===========================================*/
	public function render_status_select( $field, $escaped_value = '', $object_id = '', $object_type = '' ) {

		// Get CMB2 types object for extending.
		$CMB2_Types = new CMB2_Types( $field );

		// Output base dropdown.
		echo $CMB2_Types->select();

		// Output color code.
		printf( '<span class="mkp-status-indicator %s"></span>', $escaped_value );

	}

	/*===========================================
	 * Helper Functions
	===========================================*/

	/**
	 * Get simple array of user ID and dislay names.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of users.
	 */
	public function get_users() {

		$users = get_users();
		$simple_users = array();
		foreach ( (array) $users as $user ) {
			$simple_users[ $user->data->ID ] = $user->data->display_name;
		}

		return $simple_users;

	}

	/**
	 * Get simple array of user ID and dislay names.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of users.
	 */
	public function get_mkp_roles( $args = '' ) {

		$defaults = array(
			'show_names' => true,
		);

		$args = wp_parse_args( $args, $defaults );

		$roles = get_posts( array( 'post_type' => 'mkp-role' ) );
		$simple_roles = array();

		foreach ( $roles as $role ) {

			$role_name = $role->post_title;

			// Show the soul for the role.
			if ( $args['show_names'] ) {

				$user_id = get_post_meta( $role->ID, "_{$this->plugin_slug}_role_soul", true );
				$soul = get_userdata( $user_id );
				$soul_name = $soul->data->display_name;

				if ( $soul_name ) {
					$role_name .= " ({$soul_name})";
				}

			}

			$simple_roles[ $role->ID ] = $role_name;

		}

		return $simple_roles;

	}

	/**
	 * Get simple array of user ID and dislay names.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of users.
	 */
	public function get_project_statuses() {

		$statuses = array(
			'inactive' => __( 'Inactive', 'mkp-holacracy' ),
			'active' => __( 'Active', 'mkp-holacracy' ),
			'blocked' => __( 'Blocked', 'mkp-holacracy' ),
			'complete' => __( 'Complete', 'mkp-holacracy' ),
		);

		return $statuses;

	}

}
