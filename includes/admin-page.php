<div id="sig-wrap" class="wrap">
    <h2><?= $this->plugin_name; ?></h2>
    
    <?php if (!$token) { ?>
    <table class="form-table restricted-el">
        <tr valign="top">
            <th scope="row">
                <a class="button sig-login-button" href="<?php echo $instagram->getLoginUrl(array('basic','public_content'));?>"><span></span>Login with Instagram</a>
            </th>
        </tr>
    </table>
    <?php } else {
        $user_data =  unserialize( base64_decode( $sig_userdata ) );
        if ($user_data) {
            $website = $user_data->website;
            ?>
            <p class="restricted-el"><strong>Your Account</strong></p>
            <table class="ig-table restricted-el">
                <tr valign="top">
                    <th scope="row">
                        <div class="ig-pict-wrapper">
                            <div class="logout logout-ig">
                                <span class="dashicons dashicons-no-alt"></span>
                            </div>
                            <img src="<?= $user_data->profile_picture; ?>" alt="<?= $user_data->full_name;?>">
                        </div>
                    </th>
                    <td>
                        <p><?= $user_data->full_name;?></p>
                        <p><?= $user_data->username;?></p>
                        <p><?= $user_data->bio;?></p>
                        <p><a href="<?= $website; ?>" target="_blank"><?= $website; ?></a></p>
                        <p><button id="logout-ig" class="button">Log Out</button></p>
                    </td>
                </tr>
            </table>
            <?php
        }
    } ?>

    <form id="ig-form" method="post" action="options.php">
        <?php
        settings_fields( 'sig-opt' );
        do_settings_sections( 'sig-opt' );

        if (!$token) {
            if (isset($data->access_token)) {
                $token = $data->access_token;
            }
        }
        $ig_user_id = esc_attr( get_option('sig_id') );
        if (!$ig_user_id) {
            if (isset($data)) {
                $ig_user_id = $data->user->id;
            }
        }
        ?>
        <textarea name="sig_userdata" style="display: none;"><?php echo $sig_userdata; ?></textarea>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">API KEY</th>
                <td>
                    <input type="text" name="sig_key" value="<?php echo esc_attr( get_option('sig_key') ); ?>" />
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">API SECRET</th>
                <td>
                    <input type="text" name="sig_secret" value="<?php echo esc_attr( get_option('sig_secret') ); ?>" />
                </td>
            </tr>

            <tr class="restricted-el" valign="top">
                <th scope="row">Account Token</th>
                <td>
                    <input type="text" name="sig_token" value="<?php echo $token; ?>" readonly />
                </td>
            </tr>

            <tr class="restricted-el" valign="top">
                <th scope="row">Account ID</th>
                <td>
                    <input type="text" name="sig_id" value="<?php echo $ig_user_id; ?>" readonly />
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
    
    <?php

    if ($token) {
        $query = http_build_query(array(
            'access_token' => $token,
            'count' => 6
        ));
        $url = 'https://api.instagram.com/v1/users/'.$ig_user_id.'/media/recent/?'.$query;
        
        $request = wp_remote_get( $url );
        if ( isset($request['response']['code']) && $request['response']['code'] == 200 ) {
            $data = json_decode($request['body']);
            $data = $data->data;

            if ($data) {
                echo '<h2 class="restricted-el">Your latest post</h2>';
                
                echo '<div class="restricted-el">';
                foreach ($data as $key => $ig) {
                    $caption = '';
                    if (isset($ig->caption->text)) {
                        $caption = $ig->caption->text;
                    }
                    ?>
                    <span>
                        <a href="<?= $ig->link; ?>" target="_blank" title="<?= $caption; ?>">
                            <img src="<?= $ig->images->thumbnail->url;?>" alt="<?= $caption; ?>">
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
    ?>

</div>