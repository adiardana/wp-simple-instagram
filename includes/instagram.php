<?php

use MetzWeb\Instagram\Instagram;

/**
* 
*/
class SimpleInstagram
{
    protected $endpoint;
    protected $instagram;
    protected $token;
    protected $sig_key;
    protected $sig_secret;
    protected $api_callback;

    protected $plugin_name;
    protected $plugin_slug;

    function __construct()
    {
        $this->plugin_name  = 'Simple Instagram';
        $this->plugin_slug  = 'simple-ig';

        $this->endpoint = 'https://api.instagram.com/v1/';
        $this->token = get_option( 'sig_token' );
        
        $this->sig_key      = esc_attr( get_option('sig_key') );
        $this->sig_secret   = esc_attr( get_option('sig_secret') );
        $this->api_callback = admin_url( 'admin.php?page='.$this->plugin_slug );

        $this->instagram = new Instagram(array(
            'apiKey'      => $this->sig_key,
            'apiSecret'   => $this->sig_secret,
            'apiCallback' => $this->api_callback
        ));

        add_action( 'admin_init', array($this, 'adminSettings') );
        add_action( 'admin_menu', array($this, 'adminMenu') );

        add_action( 'admin_enqueue_scripts', array($this, 'enqueueScripts') );

        add_shortcode( 'sig', array($this, 'registerShortcode') );
    }

    public function enqueueScripts()
    {
        wp_enqueue_script( 'sig_js', SIG_URL.'admin/js/admin.js', false, '1.0.0' );
        wp_enqueue_style( 'sig_css', SIG_URL.'admin/css/admin.css', false, '1.0.0' );
    }

    public function adminSettings()
    {
        register_setting( 'sig-opt', 'sig_key' );
        register_setting( 'sig-opt', 'sig_secret' );

        register_setting( 'sig-opt', 'sig_token' );
        register_setting( 'sig-opt', 'sig_id' );
        register_setting( 'sig-opt', 'sig_userdata' );
    }

    public function adminMenu()
    {
        add_menu_page( 
            $this->plugin_name,
            $this->plugin_name, 
            'manage_options', 
            $this->plugin_slug, 
            array($this, 'adminPage'), 
            'dashicons-format-image'
        );

        add_submenu_page( 
            $this->plugin_slug,
            __('Shortcode Options'),
            __('Shortcode Options'),
            'manage_options',
            'sig_embed',
            array($this, 'adminPageEmbed')
        );

        // add options page
        // add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function );
    }

    public function adminPage()
    {
        $loginUrl = $this->instagram->getLoginUrl();

        $sig_userdata = esc_attr( get_option( 'sig_userdata' ) );

        if (!$this->token) {
            if (isset($_GET['code'])) {

                $code = $_GET['code'];

                // receive OAuth token object
                $data = $this->instagram->getOAuthToken($code);
                
                if (isset($data->user)) {
                    $sig_userdata = base64_encode( serialize( $data->user ) );
                }

            } else {

                // check whether an error occurred
                if (isset($_GET['error'])) {
                    echo 'An error occurred: ' . $_GET['error_description'];
                }

            }
        }

        include('admin-page.php');
    }

    public function adminPageEmbed()
    {
        include('admin-shortcode.php');
    }

    public function getInstagramData($id, $limit = 4)
    {
        if (!$id) { return false; }

        $query = http_build_query(array(
            'access_token' => $this->token,
            'count' => $limit
        ));

        $url = $this->endpoint.'users/self/media/recent/?'.$query;

        $request = wp_remote_get( $url );

        var_dump($request);
    }

    public function registerShortcode($atts, $content = '')
    {
        extract(shortcode_atts( array(
            'id' => 'self'
        ), $atts));

        var_dump($id);
        $result = $this->getInstagramData($id);
    }
}