
{if $flash['flash']==true}
    <div class="col-md-8">
            <div class="alert alert-success">
                {$flash['message']}
            </div>
    </div>
{/if}

<style type="text/css">

    legend {

        padding-top: 10px;
    }
</style>
<div class="col-md-6">
    <div>

        <div class="box box-icon">
            <fieldset class="box-body">
                <label>Callback URL:</label> <input class="form-control" disabled value="{$callback_url}"/>
            </fieldset>
        </div>

        <form action="index.php?page=ploader&plugin=azure_ad" role="form" method="post" enctype="multipart/form-data">

            <div class="box box-info">
                <fieldset class="box-body">
                    <legend>Azure AD</legend>
                    <label>Client ID</label>
                    <input type="text" class="form-control" name="AZURE_CLIENT_ID" value="{"AZURE_CLIENT_ID"|get_opt}" /><br/>

                    <label>Client secret</label>
                    <input type="text" class="form-control" name="AZURE_CLIENT_SECRET" value="{"AZURE_CLIENT_SECRET"|get_opt}" /><br/>

                    <label>Tenant Id</label>
                    <input type="text" class="form-control" name="AZURE_TENANT_ID" value="{"AZURE_TENANT_ID"|get_opt}" /><br/>

                    <label>Use custom login page (Only applies when forum is private)</label>
                    <select name="AZURE_CUSTOM_LOGIN" class="form-control">
                        <option {if {"AZURE_CUSTOM_LOGIN"|get_opt} eq "yes"} selected="selected" {/if} value="yes">Yes</option>
                        <option {if {"AZURE_CUSTOM_LOGIN"|get_opt} eq "no"} selected="selected" {/if} value="no">No</option>
                    </select>

                </fieldset>
            </div>


            <input type="hidden" name="CSRF_token" value="{$token}" />
            <input type="submit" value="Save" class="btn btn-primary"/>
        </form>
        <br/>
        <br/>
    </div>
</div>