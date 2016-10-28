<?php
use MetzWeb\Instagram\Instagram;
/**
* SimpleInstagram
*/
class SimpleInstagram
{
    protected $client_id;
    protected $client_secret;
    protected $redirect_url;

    protected $token;
    protected $endpoint;

    public $instagram;
    
    function __construct()
    {
        $this->client_id     = get_option( 'sig_id', '' );
        $this->client_secret = get_option( 'sig_secret', '' );
        $this->redirect_url  = admin_url( 'admin.php?page='.SIG_SLUG );

        $this->instagram = new Instagram(array(
            'apiKey'      => $this->client_id,
            'apiSecret'   => $this->client_secret,
            'apiCallback' => $this->redirect_url
        ));

        $this->token         = get_option('sig_token');
        $this->endpoint      = 'https://api.instagram.com/v1/';

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
        register_setting( 'sig-opt', 'sig_id' );
        register_setting( 'sig-opt', 'sig_secret' );

        register_setting( 'sig-opt', 'sig_token' );
        register_setting( 'sig-opt', 'sig_user_id' );
        register_setting( 'sig-opt', 'sig_userdata' );
    }

    public function adminMenu()
    {
        add_menu_page( SIG_NAME, SIG_NAME, 'manage_options', SIG_SLUG, array($this, 'adminPage'), 'dashicons-format-image');

        add_submenu_page( SIG_SLUG, __('Shortcode Options'), __('Shortcode Options'), 'manage_options', 'sig_embed', array($this, 'adminPageEmbed') );
    }

    public function adminPage()
    {
        $login_url = '';
        $data = '';
        $sig_userdata = get_option( 'sig_userdata', '' );
        $sig_user_id = get_option( 'sig_user_id', '' );

        if ($this->client_id || $this->client_secret || $this->redirect_url) {
            if (!$this->token) {
                $login_url = $this->instagram->getLoginUrl(array('basic', 'public_content'));

                if (isset($_GET['code'])) {
                    $code = $_GET['code'];

                    $data = $this->instagram->getOAuthToken($code);

                    if (isset($data->access_token)) {
                        $this->token = $data->access_token;
                    }

                    if (isset($data->user)) {
                        $sig_userdata = base64_encode(serialize($data->user));
                        $sig_user_id = $data->user->id;
                    }
                }
            }
        }
        include('admin-page.php');
    }

    public function adminPageEmbed()
    {
        
    }

    public function registerShortcode($atts, $content = '')
    {
        extract(shortcode_atts( array(
            'count'   => 6,
            'heading' => 'Your latest post',
            'size'    => 'low_resolution' //low_resolution, thumbnail, standard_resolution
        ), $atts));

        $sig_user_id = get_option( 'sig_user_id' );

        $query = http_build_query(array(
            'access_token' => $this->token,
            'count'        => $count
        ));

        $url = $this->endpoint.'users/'.$sig_user_id.'/media/recent?'.$query;

        $request = wp_remote_get( $url );

        if ( isset($request['response']['code']) && $request['response']['code'] == 200 ) {
            $data = json_decode($request['body']);

            $data = $data->data;

            if ($data) {
                echo '<h2 class="restricted-el">'.$heading.'</h2>';
                
                echo '<div class="restricted-el">';
                foreach ($data as $key => $ig) {
                    $caption = '';
                    if (isset($ig->caption->text)) {
                        $caption = $ig->caption->text;
                    }
                    ?>
                    <span>
                        <a href="<?= $ig->link; ?>" target="_blank" title="<?= $caption; ?>">
                            <img src="<?= $ig->images->{$size}->url;?>" alt="<?= $caption; ?>">
                        </a>
                    </span>
                    <?php
                    // echo '<pre>';
                    // print_r($ig);
                    // echo '</pre>';
                }
                echo '</div>';
            }
        }

    }
}