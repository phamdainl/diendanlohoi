<style type="text/css">
    .lds-ellipsis {
        display: inline-block;
        position: relative;
        width: 80px;
        height: 80px;
    }

    .lds-ellipsis div {
        position: absolute;
        top: 33px;
        width: 13px;
        height: 13px;
        border-radius: 50%;
        background: linear-gradient(to right,#5a7fee 0%,#844aa6 100%);
        animation-timing-function: cubic-bezier(0, 1, 1, 0);
    }

    .lds-ellipsis div:nth-child(1) {
        left: 8px;
        animation: lds-ellipsis1 0.6s infinite;
    }

    .lds-ellipsis div:nth-child(2) {
        left: 8px;
        animation: lds-ellipsis2 0.6s infinite;
    }

    .lds-ellipsis div:nth-child(3) {
        left: 32px;
        animation: lds-ellipsis2 0.6s infinite;
    }

    .lds-ellipsis div:nth-child(4) {
        left: 56px;
        animation: lds-ellipsis3 0.6s infinite;
    }

    @keyframes lds-ellipsis1 {
        0% {
            transform: scale(0);
        }
        100% {
            transform: scale(1);
        }
    }

    @keyframes lds-ellipsis3 {
        0% {
            transform: scale(1);
        }
        100% {
            transform: scale(0);
        }
    }

    @keyframes lds-ellipsis2 {
        0% {
            transform: translate(0, 0);
        }
        100% {
            transform: translate(24px, 0);
        }
    }

</style>

<section class="content-header" id="breadcrumb_forthistemplate_hack">
    <h1>&nbsp;</h1>
    <ol class="breadcrumb" style="float:left; left:10px;">
        <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="breadcrumb-item active"><i class="fa fa-gear"></i> License</li>
    </ol>

</section>

{if isset($flash) && $flash['flash']==true}
    <div class="col-md-8">
        <div class="alert {if isset($flash['warning']) && $flash['warning']==true }alert-danger{else}alert-success{/if}">
            {$flash['message']}
        </div>
    </div>
{/if}

<div class="col-md-6">
    <div class="box box-info">
        <div class="box-body">

            {if $clientId != null}
                <div id="get_license" style="display: flex;align-items: center">
                    <div class="lds-ellipsis">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                    <div class="font-weight-bold">Fetching details from Codoforum servers...</div>
                </div>
            {/if}
            <div style="display: none" id="free_license">
                <div>You are using the free version of the forum.</div>
                <a href="{$baseUrl}/plans" class="btn btn-success"  target="_blank" style="margin-top: 10px"><i class="fa fa-cart-plus"></i> Buy license</a>

                <br><br>

                <div>
                    <h4>OR</h4> If you have already purchased a license, please enter the token you received in your email
                    <br>to link your purchase with codoforum
                </div>

            </div>
                
            <div style="margin-top: 10px" class="input-group mb-3">
                    <form method="post" action='index.php?page=license/index'>
                        <input type="text" class="form-control" name='clientIdInput' value="{$clientId}" placeholder="Enter Token"><br/>
                    <div class="input-group-append">
                        <button class="btn btn-success" type="submit" id="linkForum">Update License Token</button>
                    </div>
                    </form>
            </div>

            <div style="display: none" id="paid_license">
                <div><b>Current License:</b> <span id="plan_name">GET_FROM_CF_SERVER</span></div>
                <!--<hr>
                <a href="index.php?page=license/buy" class="btn btn-success"><i class="fa fa-cart-plus"></i> Change plan</a>-->
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">

{*    $('#linkForum').on('click',()=>{
        
       let clientId = $('#clientIdInput').val();
       window.location='index.php?page=license/index&action=link&clientId='+clientId;
    });*}

    {if $clientId eq null}
            $('#free_license').show();
    {else}
        $.ajax({
            type: "GET",
            url: `{$baseApiUrl}/subscription/client/{$clientId}`,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: response => {
                if (response.success) {
                    $('#get_license').hide();
                    if (response.data.machineName.indexOf("free_plan") > -1) {
                        $('#free_license').show();
                    } else {
                        $('#plan_name').html(response.data.productName);
                        $('#paid_license').show();
                    }
                } else {
                    alert("There was some problem contacting our servers. Please reload the page. If the issue persists, send us an email to admin@codologic.com. We apologize for any inconvenience.")
                }
            }
        });
    {/if}

</script>
