<section class="content-header" id="breadcrumb_forthistemplate_hack">
    <h1>&nbsp;</h1>
    <ol class="breadcrumb" style="float:left; left:10px;">
        <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="breadcrumb-item"><a href="index.php?page=categories"><i class="fa fa-table"></i> Categories</a></li>
        <li class="breadcrumb-item active"><i class="fa fa-edit"></i> Edit Category</li>

    </ol>

</section>


<div class="row" id="msg_cntnr">
    <div class="col-lg-6">
        {if $msg eq ""}

        {elseif $err==1}
            <div class="alert alert-danger alert-dismissable">
                <i class="fa fa-ban"></i>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {$msg}
            </div>
        {else}
            <div class="alert alert-info alert-dismissable">
                <i class="fa fa-info"></i>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {$msg}
            </div>
        {/if}

    </div>
</div>


<div class="row" id="add_cat" style="">
    <div class="col-lg-6">
        <div class="box box-info">
            <form class="box-body" action="?page=categories&action=edit&cat_id={$cat_id}" role="form" method="post"
                  enctype="multipart/form-data">
                <input type="hidden" value="edit" name="mode"/>
                <input type="hidden" value="{$cat_id}" name="id"/>

                <label>Title:</label><br/>
                <input type="text" name="cat_name" value="{$cat.cat_name}" class="form-control"
                       placeholder="Category name" required/>
                <br/>

                <label>Icon[420x420]:</label><br/>
                {$cat.cat_img}
                <input type="file" class="form-control" name="cat_img"/><br/>

                <div style="display: none">
                    <!-- It seems like this feature was removed probably when we redesigned the theme -->
                    <label>Is a label ?:</label><br/>
                    <select class="form-control" name="is_label">
                        <option value="yes" {if $cat.is_label eq 1}selected{/if}>Yes</option>
                        <option value="no" {if $cat.is_label eq 0}selected{/if}>No</option>
                    </select>
                    <br/>
                </div>

                <label>Email all users on new topic? (Users that have explicitly unsubscribed will not be
                    notified):</label><br/>
                <select class="form-control" name="default_subscription_type">
                    <option value="yes" {if $cat.default_subscription_type eq 4}selected{/if}>Yes</option>
                    <option value="no" {if $cat.default_subscription_type eq 2}selected{/if}>No (recommended)</option>
                </select>
                <br/>


                <label>Description:</label><br/>
                <textarea name="cat_description" placeholder="Category Description"
                          class="form-control">{$cat.cat_description}</textarea>
                <br/>

                <div class="form-group">
                    <label>Show sub-categories on load?</label>
                    <br/>
                    <input
                            class="simple form-control" name="show_children"
                            data-permission='yes'
                            {if $cat.show_children eq 1} checked="checked" {/if}
                            type="checkbox" data-toggle="toggle"
                            data-on="yes" data-off="no" data-size="small"
                            data-onstyle="success" data-offstyle="danger">

                </div>
                <br/>


                <input type="submit" value="Save" class="btn btn-success"/>
                <a href="index.php?page=categories" class="btn btn-default">Back</a>
                <input type="hidden" name="CSRF_token" value="{$token}"/>
            </form>
        </div>
    </div>

</div>
<br/>