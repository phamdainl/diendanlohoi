CODOF.authenticator = {
    login: function (response) {

        $('.codo_login_loading').show();

        if (response.name) {
            jQuery.post(codo_defs.url + 'sso/authorize', {
                token: codo_defs.token,
                sso: response,
                timestamp: codo_defs.get('sso_timestamp')

            }, function (response) {
                if (response.trim() === "") {

                    const url = window.location.href;
                    if (url.indexOf("sso/authorize") > -1) {
                        window.location = codo_defs.get('sso_login_success_url');
                    } else {
                        window.location.reload();
                    }
                }
            });
        }
    }
};

jQuery(document).ready(function ($) {

    //check if user is logged in codoforum
    if (codo_defs.logged_in === 'no') {
        //check if user is logged in master site
        // Using JSONP
        $.ajax({
            url: codo_defs.get('sso_get_user_path'),
            jsonp: "callback",
            // tell jQuery we're expecting JSONP
            dataType: "jsonp",
            data: {
                format: "json",
                client_id: codo_defs.get('sso_client_id'),
                timestamp: codo_defs.get('sso_timestamp'),
                token: codo_defs.get('sso_token')
            },
            // work with the response
            success: function (response) {
                const can_view_forum = codo_defs.get('can_view_forum');
                if (response.name) {
                    CODOF.authenticator.login(response);
                    return false;
                } else if (can_view_forum === 'no'){
                    window.location = codo_defs.get('sso_login_user_path');
                }
            }
        });
    }

    $('#codo_login_with_sso').on('click', function () {

        window.location.href = codo_defs.get('sso_login_user_path');
    });

});

