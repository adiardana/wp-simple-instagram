jQuery(document).ready(function($) {

    if ( $('input[name="sig_id"]').val() == '' || $('input[name="sig_secret"]').val() == '' ) {
        $('#sig-wrap').addClass('hide-login-el');
    }

    $('#logout-ig,.logout-ig').on('click', function(event) {
        event.preventDefault();
        
        var r = confirm("Are you sure to revoke access to this account?");
        if (r == true) {
            $('textarea[name="sig_userdata"],input[name="sig_token"],input[name="sig_user_id"]').val('');
            $('#submit').click();
        }
    });
});