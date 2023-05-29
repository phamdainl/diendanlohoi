<?php
/*
 * @CODOLICENSE
 */

require 'header.php';

?>

    <style type="text/css">

        #refresh_perm {

            background: #00ca6d;
            border-radius: 1px 1px 1px 1px;
            width: 15%;
            padding: 4px;
        }

        #refresh_perm:hover {

            box-shadow: 1px 1px 10px 1px #000;
            color: #fff;
            cursor: pointer;
        }

        .engraved-dark-text {

            color: #0b2d43;
            text-shadow: 0 1px 1px #fff;
        }

    </style>

    <div class="container">

        <div class="row">
            <br>
            <br>
            <div class="bigText"></div>

            <br><br>

            <div class="codo_separator"></div>

            <div class="well well-lg">

                <?php if ($permission_error == true) { ?>
                    <div class="codo_premission_errors">

                        <b>Please make following files/folders writable</b>
                        <div style="margin-top:10px"></div>

                        <ol>
                            <?php foreach ($permits as $permit) {
                                if ($permit['perm'] == false) {
                                    ?>
                                    <li>
                                        <div><?php echo $permit['name']; ?></div>
                                    </li>
                                <?php }
                            }
                            ?>
                        </ol>
                        <!---->
                    </div>

                <?php } else { ?>

                    <div id="codo_db_not_connect" class="codo_notification codo_notification_error">
                        Could not connect to the database with the given details!
                    </div>
                    <form id="codo_form_step2" role="form" class="form-horizontal"
                          action="<?php echo RURI; ?>index.php&step=3" method="POST">
                        <fieldset>
                            <legend>Database details</legend>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="name">Database driver</label>
                                <div class="col-sm-8">
                                    <select name="db_driver" class="form-control" id="db_driver">
                                        <option value="mysql" selected="">MySQL</option>
                                        <option value="sqlite">SQLite</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="name" id="database_name_label">Database
                                    name</label>
                                <div class="col-sm-8">
                                    <input id="focus_here" type="text" class="codo_input" name="db_name"
                                           value="codoforum" placeholder="Enter database name" required>
                                </div>
                            </div>
                            <div class="form-group sqlite_hidden">
                                <label class="col-sm-2 control-label" for="name">Database username</label>
                                <div class="col-sm-8">
                                    <input type="text" class="codo_input" name="db_user" value="root"
                                           placeholder="Enter username" required>
                                </div>
                            </div>
                            <div class="form-group sqlite_hidden">
                                <label class="col-sm-2 control-label" for="name">Database password</label>
                                <div class="col-sm-8">
                                    <input type="text" class="codo_input" name="db_pass" placeholder="Enter password">
                                </div>
                            </div>
                            <div class="form-group sqlite_hidden">
                                <label class="col-sm-2 control-label" for="name">Database host</label>
                                <div class="col-sm-8">
                                    <input type="text" class="codo_input" name="db_host" value="127.0.0.1"
                                           placeholder="Enter database host" required>
                                </div>
                            </div>
                            <!--{*<div class="form-group">
                            <label  class="col-sm-2 control-label" for="name">Table prefix</label>
                            <div class="col-sm-8">
                            <input type="text" class="codo_input" name="db_host" placeholder="Enter database host" required>
                            </div>
                            </div>*}-->

                            <div style="margin-top:30px"></div>

                            <legend>Administrator details</legend>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="name">Admin username</label>
                                <div class="col-sm-8">
                                    <input id="admin_user" type="text" class="codo_input" value="admin"
                                           name="admin_user" placeholder="Enter administrator username" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="name">Admin password</label>
                                <div class="col-sm-8">
                                    <input id="admin_pass" type="text" class="codo_input" name="admin_pass"
                                           placeholder="Enter administrator password" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="name">Admin mail</label>
                                <div class="col-sm-8">
                                    <input id="admin_mail" type="text" class="codo_input" name="admin_mail"
                                           placeholder="Enter administrator email address" required>
                                </div>
                            </div>
                        </fieldset>
                        <div style="margin-top:5px"></div>

                        <input type="hidden" name="db_dsn" id="db_dsn"/>
                        <div id="submit" class="btn btn-success">Submit</div>
                    </form>
                <?php } ?>

            </div>

            <?php if ($permission_error == true) { ?>
                <div id="refresh_perm" class="btn">Refresh</div><br><br>
                <!--<div class="engraved-dark-text">click on refresh, after you set permissions</div>-->
            <?php } ?>
        </div>

    </div>

    <script type="text/javascript">

        jQuery('document').ready(function ($) {

            <?php if ($permission_error == true) { ?>
            $(".bigText").typed({
                strings: ["Uh oh, ^500 a problem occured!"],
                typeSpeed: 30 // typing speed
                // backDelay: 500 // pause before backspacing
            });
            <?php } else { ?>

            $(".bigText").typed({
                strings: ["Lets fill in some details"],
                typeSpeed: 30 // typing speed
                // backDelay: 500 // pause before backspacing
            });

            <?php } ?>

            $('#codo_db_not_connect').hide();
            $('#focus_here').focus();

            $('body').keypress(function (event) {


                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {

                    return false;
                }
            });
//

            $('#refresh_perm').on('click', function () {

                window.location.reload();
            });

            $('#db_driver').on('change', function () {

                if ($(this).val() === 'sqlite') {

                    $('#database_name_label').html('Database file');
                    $('#focus_here').attr('placeholder', 'Enter absolute path to database filename')//.val(/sites/default/codoforum.sqlite');
                    $('.sqlite_hidden').fadeOut();
                } else {

                    $('#database_name_label').html('Database name');
                    $('.sqlite_hidden').fadeIn();
                    $('#focus_here').attr('placeholder', 'Enter database name');

                }
            });

            $('#submit').on('click', function () {

                var name = $('#admin_user').val(),
                    pass = $('#admin_pass').val(),
                    mail = $('#admin_mail').val(),
                    dbname = $('#focus_here').val().trim();

                if (dbname === '') {

                    $('#codo_db_not_connect').fadeIn().html("Please enter valid database name");
                    return false;
                }

                if (name === '' || pass === '' || mail === '') {

                    $('#codo_db_not_connect').fadeIn().html("Please enter admin username/password/email");
                    return false;
                }

                var data = $('#codo_form_step2').serialize();
                data += "&post_req=" + encodeURIComponent('yes');

                $('#submit').html('Installing...');


                $.post('<?php echo RURI; ?>index.php&step=3',
                    data
                    , function (data) {

                        if (data !== 'success') {

                            $('#submit').html('Try Again');
                            $('#codo_db_not_connect').fadeIn().html(data);
                        } else {

                            setTimeout(()=>{
                                $('#codo_form_step2').submit();
                            },3000);
                            
                        }
                    });

                //return false;
            });
        });
    </script>

<?php
require 'footer.php';
