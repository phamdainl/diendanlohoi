<?php
/*
 * @CODOLICENSE
 */

 
require 'header.php';
?>

<style type="text/css">

    
    
    .right-arrow {

        font-size: 12px;
      
      color:#fff;
        top:1%;
        text-shadow: 4px 4px 7px #222;
        position: relative;
        transition: 1s all ease-in-out;
        -webkit-transition: 1s all ease-in-out;
        -moz-transition: 1s all ease-in-out;
        -o-transition: 1s all ease-in-out;

    }

    .shake-right{
        
          background: #00ca6d;
          border-radius: 1px 1px 1px 1px;
          width:25%;
          padding:4px;
    }
    
    .shake-right:hover  {
       
        box-shadow: 1px 1px 10px 1px #000;
   
        cursor: pointer;

    }

    @keyframes moveRight {
        0% {
            transform: translateX(0px);
        }

        50% {
            transform: translateX(20px);
        }

        100% {

            transform: translateX(0px);
        }
    }
    @-webkit-keyframes moveRight {
        0% {
            -webkit-transform: translateX(0px);
        }

        50% {
            -webkit-transform: translateX(20px);
        }

        100% {
            -webkit-transform: translateX(0px);
        }

    }
    @-moz-keyframes moveRight {
        0% {
            -moz-transform: translateX(0px);
        }

        50% {
            -moz-transform: translateX(20px);
        }

        100% {
            -moz-transform: translateX(0px);
        }

    }



</style>

<div class="container">

    <div class="row">

        <!--<div class="codo_steps">

            <div class="codo_steps_stripe"></div>
            <div class="codo_steps_stripe_progress codo_progress_step1"></div>

            <div class="step_body" style="margin-left: 14%">
                <div class="codo_step codo_step_active">1</div>
            </div>

            <div class="step_body" style="margin-left: 46%">
                <div class="codo_step" >2</div>
            </div>

            <div class="step_body" style="margin-left: 79%">
                <div class="codo_step" >3</div>
            </div>
        </div>

        <div class="codo_separator"></div>-->

        <div id="step1" class="col-md-8 col-md-offset-2" data-x="-1000" data-y="-1500" style="margin-top:50px">

            <?php if ($already_installed == 'yes') { ?>
            <div class="well" style="margin-top: 10%; margin-left: 10%">
                It looks like codoforum has been previously installed , if you want to reinstall it , please replace 
                <br/><br/><code>$installed=true;</code> <br/><br/>by<br/><br/> <code>$installed=false;</code><br/><br/> in sites/default/config.php
            </div>
            <?php } else { ?>


                <div class="bigText"></div>


                <div class="codo_license">
                    <pre id="codo_license"></pre>
                </div>
                
                        <div id="gotostep2" class="shake-right" style="text-align: center;margin-top: 1%">
            NEXT <i  class="right-arrow icon-arrow-right"></i>
        </div>
        <br>
        <br>

                <!--<div id="gotostep2" class="btn btn-dark">Accept and continue</div>-->
            <?php } ?>
        </div>

        <?php if ($already_installed == 'no') { ?>

        <?php } ?>

    </div>

</div>

<script type="text/javascript">

    jQuery('document').ready(function($) {
        
        $.get('license.txt', function(data) {

            //process text file line by line
            $('#codo_license').html(data);
        });

        $('#gotostep2').on('click', function() {

            window.location.href = "<?php echo RURI; ?>index.php&step=2";
        });

        $(".bigText").typed({
            strings: ["Welcome.","Read the license carefully.", "Click next to accept & proceed."],
            typeSpeed: 30, // typing speed
            backDelay: 500 // pause before backspacing
        });

        /*$('.highlightMe').each(function(el) {
            
        
            var $el = $(this);
            
            var old = $el.css('background');
            $el.css('background',  'rgba(170, 33, 33, 0.6)');
            
            setTimeout(function() {
               
               $el.css('background', old);
            }, 1000);
        });*/

    });


</script>


<?php
require 'footer.php';
