<?php
/*
Plugin Name: Quietly Insights
Description: A plugin for embedding the Quietly Insights analytics code in your WordPress blog
Author: Quietly
Version: 1.2.2
*/

    // Plugin constants
    define( 'QUIETLY_ANALYTICS_VERSION', '1.2.2' );
    define( 'QUIETLY_ANALYTICS_SLUG', 'quietly-analytics' );
    define( 'QUIETLY_ANALYTICS_APP_URL',  'https://insights.quiet.ly/app/');
    //define( 'QUIETLY_ANALYTICS_APP_URL',  'https://qa.quiet.ly/app/');
    //define( 'QUIETLY_ANALYTICS_APP_URL',  'https://development.quiet.ly/app/');
    //define( 'QUIETLY_ANALYTICS_APP_URL',  'http://localhost:9000/');

    define( 'QUIETLY_ANALYTICS_SCRIPT_URL', QUIETLY_ANALYTICS_APP_URL.'analytics.min.js' );
    define( 'QUIETLY_ANALYTICS_VERIFY_PROP', QUIETLY_ANALYTICS_APP_URL.'api/wordpress/verify_domain/' );
    define( 'QUIETLY_ANALYTICS_RESET_PROP', QUIETLY_ANALYTICS_APP_URL.'api/wordpress/reset/' );


    /* plugin vars */
    $qap_path = plugin_basename(__FILE__);
    $qap_id = get_option( 'qap_id' );

    /* plugin actions */
    add_action('admin_init', 'qap_init');
    add_action('admin_menu', 'quietly_analytics_create_menu');
    add_action('admin_enqueue_scripts', 'enqueue_scripts');
    add_action('admin_notices', 'add_activation_notice');
    add_action('wp_ajax_save_qap_id', 'save_qap_id');
    add_action('wp_ajax_verify_pin', 'verify_pin');

    // include tracking code in header (for now. May want to make this an option at some point)
    if (isset($qap_id) && $qap_id != '') {
        add_action('wp_head', 'inject_quietly_tracker');
    }


    /* hooks */
    register_activation_hook( __FILE__,  'activated' );
    register_deactivation_hook( __FILE__, 'deactivated' );
    register_uninstall_hook( __FILE__, 'deleted' );

    /* functions */

    /* inject tracker snippet */
    function inject_quietly_tracker() {
        global $qap_id;
        $postId = get_the_ID();
        $tags = wp_get_post_tags($postId);

        ?>
        <script>!function(t,e,a,c,x){var s='q';t[s]={publication_id: c, tags: x};var s=e.createElement(a),r=e.getElementsByTagName(a)[0];s.async=1,s.src='<?php echo QUIETLY_ANALYTICS_SCRIPT_URL ?>',r.parentNode.insertBefore(s,r)}(window,document,'script','<?php echo $qap_id; ?>','<?php echo json_encode($tags); ?>' );</script>

        <?php
    }

    /* activations functions */
    function activated() {
        add_option(  QUIETLY_ANALYTICS_SLUG . '_admin_activation_notice', 'true' );
        add_option( 'qap_id', '');
    }

    function deactivated() {
        delete_option( QUIETLY_ANALYTICS_SLUG . '_admin_activation_notice' );
    }

    function deleted() {
        $args = array (
            'headers' => array(
                'content-type' => 'application/json'
            ),
            'method' => 'PUT',
            'sslverify' => false,
            'body' => json_encode(array(
                'propertyId' => get_option('qap_id')
            ))
        );

        wp_remote_request(QUIETLY_ANALYTICS_RESET_PROP, $args);
        delete_option('qap_id');
    }

    function qap_init() {
        register_setting('qap_plugin_options', 'qap_id', 'qap_validate_options');
    }

    // sanitize and validate input
    function qap_validate_options($input) {
        $input = wp_filter_nohtml_kses($input);
        return $input;
    }

    function add_activation_notice() {
        // Show first-time activation message
        if ( 'plugins' === get_current_screen()->id &&
            current_user_can( 'install_plugins' ) &&
            'true' === get_option( QUIETLY_ANALYTICS_SLUG . '_admin_activation_notice' ) ) {
            include_once( 'views/quietly-analytics-activation-notice.php' );
            delete_option( QUIETLY_ANALYTICS_SLUG . '_admin_activation_notice' );
        }
    }

    function quietly_analytics_create_menu(){
        add_menu_page( 'Quietly Insights Settings', 'Quietly Insights', 'manage_options', 'quietly-analytics', 'settings_init', 'none');
    }

    function enqueue_scripts(){
            wp_register_style( 'quietly-analytics-admin', plugins_url( 'css/quietly-analytics-admin.css', __FILE__ ));
            wp_enqueue_style( 'quietly-analytics-admin' );
    }

    function settings_init(){
            wp_register_style( 'quietly-analytics-settings', plugins_url( 'css/quietly-analytics-plugin.css', __FILE__ ));
            wp_enqueue_style( 'quietly-analytics-settings' );
            wp_register_script( 'quietly-analytics-settings', plugins_url( 'js/quietly-analytics-plugin.js', __FILE__ ));
            wp_enqueue_script( 'quietly-analytics-settings' );
            include_once('views/quietly-analytics-settings.php');
    }

    function test_settings(){
            wp_register_style( 'arch-plugin-admin-menu', plugins_url( 'css/arch-plugin.css', __FILE__ ));
            wp_enqueue_style( 'arch-plugin-admin-menu' );
            wp_register_script( 'arch-plugin-admin-menu', plugins_url( 'js/arch-plugin.js', __FILE__ ));
            wp_enqueue_script( 'arch-plugin-admin-menu' );
            include_once('views/plugin-settings.php');
    }

    function save_qap_id() {
        // Handle request then generate response using WP_Ajax_Response
        $option = $_POST['option'];
        $new_value = $_POST['new_value'];

        if( !isset( $option ) || $option == '' || !isset( $new_value ) || $new_value == '' ) {
            die(
                json_encode(
                    array(
                        'success' => false,
                        'message' => 'Missing required information.'
                    )
                )
            );
        }

        update_option( $option, $new_value );
        die(
            json_encode(
                array(
                    'option was' => $option,
                    'value was' => $new_value,
                    'success' => true,
                    'message' => 'Database updated successfully.'
                )
            )
        );
    }

    // display settings link on plugin page
    function quietly_analytics_action_links($links, $file) {
        global $qap_path;
        //echo $ap_path;
        if ($file == $qap_path) {
            $qap_links = '<a href="' . get_admin_url() . 'admin.php?page=quietly-analytics">' . esc_html__('Settings', 'quietly-analytics') .'</a>';
            array_push($links, $qap_links);
        }
        return $links;
    }
    add_filter ('plugin_action_links', 'quietly_analytics_action_links', 10, 2);

    // Verifies the user supplied Property ID
    function verify_pin() {
        $url = QUIETLY_ANALYTICS_VERIFY_PROP;
        $pid = $_POST['pid'];
        $domain = $_POST['domain'];
        $option = $_POST['option'];
        $args = array (
            'headers' => array(
                'content-type' => 'application/json'
            ),
            'method' => 'PUT',
            'sslverify' => false,
            'body' => json_encode(array(
                'pid' => $pid,
                'domain' => $domain
            ))
        );
        $response = wp_remote_request( $url, $args );

        if( !isset( $pid ) || $pid == '' || wp_remote_retrieve_response_code($response) != '200') {
            die(
                json_encode(
                    array(
                        'success' => false,
                        'message' => 'Missing some information.',
                        'body' => $response['body'],
                        'status' => wp_remote_retrieve_response_code($response)
                    )
                )
            );
        }
        update_option( $option, $pid );
        die(
            json_encode(
                array(
                    'success' => true,
                    'message' => 'Database updated successfully.',
                    'body' => $response['body'],
                    'status' => wp_remote_retrieve_response_code($response)
                )
            )

        );
    }

?>
