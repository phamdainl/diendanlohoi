<section class="content-header" id="breadcrumb_forthistemplate_hack">
    <h1>&nbsp;</h1>
    <ol class="breadcrumb" style="float:left; left:10px;">
        <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="breadcrumb-item"><i class="fa fa-envelope"></i> Mail Settings</li>
        <li class="breadcrumb-item active"><i class="fa fa-file"></i> Templates</li>
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
    <form class="" action="?page=mail/templates" role="form" method="post" enctype="multipart/form-data">

        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Await approval template</h3>
            </div>
            <div class="box-body">
                <label>Subject</label>
                <input type="text" class="form-control" name="await_approval_subject"
                       value="{"await_approval_subject"|get_opt}"/><br/>
                <label>Message</label>
                <textarea class="form-control" style="height:200px"
                          name="await_approval_message">{"await_approval_message"|get_opt}</textarea><br/>
            </div>
        </div>

        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Topic notify template</h3>
            </div>
            <div class="box-body">
                <label>Subject</label>
                <input type="text" class="form-control" name="topic_notify_subject"
                       value="{"topic_notify_subject"|get_opt}"/><br/>
                <label>Message</label>
                <textarea class="form-control" style="height:200px"
                          name="topic_notify_message">{"topic_notify_message"|get_opt}</textarea><br/>
            </div>
        </div>

        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Post notify template</h3>
            </div>
            <div class="box-body">
                <label>Subject</label>
                <input type="text" class="form-control" name="post_notify_subject"
                       value="{"post_notify_subject"|get_opt}"/><br/>
                <label>Message</label>
                <textarea class="form-control" style="height:200px"
                          name="post_notify_message">{"post_notify_message"|get_opt}</textarea><br/>
            </div>
        </div>

        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Vote up notify template</h3>
            </div>
            <div class="box-body">
                <label>Subject</label>
                <input type="text" class="form-control" name="vote_up_notify_subject"
                       value="{"vote_up_notify_subject"|get_opt}"/><br/>
                <label>Message</label>
                <textarea class="form-control" style="height:200px"
                          name="vote_up_notify_message">{"vote_up_notify_message"|get_opt}</textarea><br/>
            </div>
        </div>

        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Vote down notify template</h3>
            </div>
            <div class="box-body">
                <label>Subject</label>
                <input type="text" class="form-control" name="vote_down_notify_subject"
                       value="{"vote_down_notify_subject"|get_opt}"/><br/>
                <label>Message</label>
                <textarea class="form-control" style="height:200px"
                          name="vote_down_notify_message">{"vote_down_notify_message"|get_opt}</textarea><br/>
            </div>
        </div>

        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Password reset template</h3>
            </div>
            <div class="box-body">
                <label>Subject</label>
                <input type="text" class="form-control" name="password_reset_subject"
                       value="{"password_reset_subject"|get_opt}"/><br/>
                <label>Message</label>
                <textarea class="form-control" style="height:200px"
                          name="password_reset_message">{"password_reset_message"|get_opt}</textarea><br/>
            </div>
        </div>


        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">New registration notification template</h3>
            </div>
            <div class="box-body">
                <label>Subject</label>
                <input type="text" class="form-control" name="new_registration_subject"
                       value="{"new_registration_subject"|get_opt}"/><br/>
                <label>Message</label>
                <textarea class="form-control" style="height:200px"
                          name="new_registration_message">{"new_registration_message"|get_opt}</textarea><br/>
            </div>
        </div>

        <input type="hidden" name="CSRF_token" value="{$token}"/>
        <input type="submit" value="Save" class="btn btn-primary"/>
    </form>
</div>

<script type="text/javascript">

    $("textarea").each(function() {
        this.value = this.value.replace(/(\r\n|\n|\r)/gm, "").replace(/<br\s*\/?>/mg,"\n");
    })

</script>