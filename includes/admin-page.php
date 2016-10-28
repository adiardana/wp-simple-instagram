<div id="sig-wrap" class="wrap">
    <h2><?= SIG_NAME; ?></h2>
    
    <?php if (!$login_url) {
    $user_data = unserialize( base64_decode( $sig_userdata ) );
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
                <p><a href="<?= $user_data->website; ?>" target="_blank"><?= $user_data->website; ?></a></p>
                <p><button id="logout-ig" class="button">Log Out</button></p>
            </td>
        </tr>
    </table>
    <?php } else { ?>
    <table class="form-table restricted-el">
        <tr valign="top">
            <th scope="row">
                <a class="button sig-login-button" href="<?= $login_url;?>"><span></span>Login with Instagram</a>
            </th>
        </tr>
    </table>
    <?php } ?>

    <form id="ig-form" method="post" action="options.php">
        <?php
        settings_fields( 'sig-opt' );
        do_settings_sections( 'sig-opt' );
        ?>
        <textarea name="sig_userdata" style="display: none;"><?php echo $sig_userdata; ?></textarea>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">API ID</th>
                <td>
                    <input type="text" name="sig_id" value="<?php echo esc_attr( get_option('sig_id') ); ?>" />
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
                    <input type="text" name="sig_token" value="<?php echo $this->token; ?>" readonly />
                </td>
            </tr>

            <tr class="restricted-el" valign="top">
                <th scope="row">Account ID</th>
                <td>
                    <input type="text" name="sig_user_id" value="<?php echo $sig_user_id; ?>" readonly />
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

</div>