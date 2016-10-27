<?php
class Simple_Instagram_Deactivator {

    public static function deactivate() {
        unregister_setting( 'sig-opt', 'sig_key' );
        unregister_setting( 'sig-opt', 'sig_secret' );

        unregister_setting( 'sig-opt', 'sig_token' );
        unregister_setting( 'sig-opt', 'sig_id' );
        unregister_setting( 'sig-opt', 'sig_userdata' );
    }

}
