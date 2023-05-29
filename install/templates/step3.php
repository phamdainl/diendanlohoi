<?php
/*
 * @CODOLICENSE
 */

require 'header.php';
?>

<div class="container">

    <div class="row">
        <br><br>
        <div class="bigText"></div>
        <br><br>

        <div class="codo_separator"></div>

        <div class="well well-lg">


            <br/>Please rename/remove the <em>install/</em>  folder for security reasons    
            <br/><br/>
            <div>
                <a href="<?php echo HOME; ?>" class="btn btn-primary" target="_blank">View site</a>
                <a href="<?php echo HOME; ?>admin/" class="btn btn-primary" target="_blank">View backend</a>
            </div>                
        </div>

    </div>

</div>

<script type="text/javascript">

    jQuery('document').ready(function ($) {

        $(".bigText").typed({
            strings: ["Installation successfull!"],
            typeSpeed: 30 // typing speed
                    // backDelay: 500 // pause before backspacing
        });

        //create the avatar for admin
        $.get("<?php echo HOME; ?>index.php?u=/user/avatar/1");

        $('#gotostep3').on('click', function () {

            window.location.href = "<?php echo RURI; ?>index.php&step=2";
        });
    })
</script>


<?php
require 'footer.php';
