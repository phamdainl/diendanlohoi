{*
    /*
    * @CODOLICENSE
    */
    *}
{* Smarty *}
{extends file='layout.tpl'}

{block name=body}


    <div class="container-fluid top-custom-container-profile">
        <div class="container">

            <div class="row">


                <div class="col-md-9">
                    {"block_breadcrumbs_before"|load_block}
                    <div id="breadcrumb" class="col-md-12">
                        {"block_breadcrumbs_before"|load_block}

                        {assign first "yes"}
                        <div class="codo_breadcrumb_list btn-breadcrumb d-none d-sm-block">
                            <a href="{$smarty.const.RURI}{$site_url}">
                                <div>{_t("Home")}</div>
                            </a>

                            {foreach from=$parents item=crumb}
                                {if $first eq "yes"}
                                    <a title="{$crumb.name}" data-toggle="tooltip"
                                       href="{$smarty.const.RURI}{$site_url}#{$crumb.alias}">
                                        <div>{$crumb.name}</div>
                                    </a>
                                {else}
                                    <a title="{$crumb.name}" data-toggle="tooltip"
                                       href="{$smarty.const.RURI}category/{$crumb.alias}">
                                        <div>{$crumb.name}</div>
                                    </a>
                                {/if}
                                {assign first "no"}
                            {/foreach}
                        </div>

                        <select id="codo_breadcrumb_select"
                                class="form-control d-none">
                            <option selected="selected" value="">{_t("Where am I ?")}</option>
                            {assign space "&nbsp;&nbsp;&nbsp;"}
                            {assign indent "{$space}"}
                            {assign first "yes"}
                            <option value="{$smarty.const.RURI}{$site_url}">{$indent}{$home_title}</option>

                            {foreach from=$parents item=crumb}
                                {assign indent "{$indent}{$space}"}
                                {if $first eq "yes"}
                                    <option value="{$smarty.const.RURI}{$site_url}#{$crumb.alias}">{$indent}{$crumb.name}</option>
                                {else}
                                    <option value="{$smarty.const.RURI}category/{$crumb.alias}">{$indent}{$crumb.name}</option>
                                {/if}
                                {assign first "no"}
                            {/foreach}

                        </select>
                        {"block_breadcrumbs_after"|load_block}
                    </div>

                    {"block_breadcrumbs_after"|load_block}

                    <div class="row codo_cat_top_title_area">
                        <div class="col-lg-2 codo_cat_top_img_box">
                            <img src='{$smarty.const.DURI}{$smarty.const.CAT_IMGS}{$cat_info.cat_img}'/>
                        </div>
                        <div class="col-lg-8 codo_cat_top_title_box">
                            <div class="codo_cat_top_title">{$cat_info.cat_name}</div>
                            <p>{$cat_info.cat_description}</p>
                            {if isset($new_topics) && count($new_topics)}
                                <div id='mark_all_read' class="mark_unread">
                                    <div>
                                        {_t("Mark all as read")}
                                    </div>
                                    <div class="codo_mark_unread_checkbox">
                                        <input type="checkbox" name="group0" id="codo_sidebar_title_switch"
                                               class="chk-box codo_switch toggle_switch_container codo_switch_off">
                                        <label for="codo_sidebar_title_switch"></label>
                                    </div>

                                </div>
                            {/if}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container codo_top_create_topic_btn">
        <div class="row">
            <div class="col-md-3" style="padding: 0">
                <div class="codo_icon_header d-none d-sm-block d-lg-none visible-xs d-block d-sm-none codo_create_topic_btn_container">
                    <button id="codo_create_topic_btn" type="submit"
                            class="codo_btn codo_btn_primary codo_create_topic_btn"
                    >{_t("Create new topic")}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container" id="codo_category_topics" style="padding:0px;">
        <div class="row">


            <div style="display:none" id="codo_no_topics_display"
                 class="codo_no_topics">{_t("No posts to display")}</div>
            <div class="codo_categories col-md-12" id="codo_categories">
                {"block_category_desc_before"|load_block}
                <div class="codo_categories_container">
                    {if $can_create_topic}
                        <div class="codo_categories_container codo_new_topic d-sm-none d-md-block  d-none d-sm-block">
                            <button id="codo_create_topic_btn" type="submit"
                                    class="codo_btn codo_btn_primary   codo_create_topic_btn"
                            >{_t("Create new topic")}</button>
                        </div>
                    {/if}
                </div>
            </div>

            {if !empty($sub_cats)}
                <div class="codo_sub_categories col-lg-12">
                    <ul id="codo_categories_ul">
                        {foreach from=$sub_cats item=$cat}
                        <li class="row">
                            <div class="codo_categories_category">
                                <div class="codo_categories_category_icon">
                                    <img src='{$smarty.const.DURI}{$smarty.const.CAT_IMGS}{$cat->cat_img}'/>
                                </div>

                                <div class="codo_categories_category_name">
                                    <a href="{$smarty.const.RURI}category/{$cat->cat_alias|escape:url}"> {$cat->cat_name}</a>
                                    {if property_exists($cat, 'numNewTopics')}<span class='codo_new_topic_badge'>{_t('New')}</span>{/if}
                                    <div>
                                        <span title="{_t('No. of topics')}" style="float: left"><i class="icon icon-books"></i> {$cat->no_topics}</span>
                                        <span title="{_t('No. of posts')}"><i class="icon icon-message"></i> {$cat->no_posts}</span>
                                    </div>
                                    {if isset($cat->children)}
                                        {foreach from=$cat->children item=$child}
                                            <a class="codo_sub_category_name" href="{$smarty.const.RURI}category/{$child->cat_alias|escape:url}"><i class="icon icon-chat"></i> {$child->cat_name}</a>
                                        {/foreach}
                                    {/if}
                                </div>
                                {if property_exists($cat, 'latestTopicData')}
                                    <div class="codo_categories_category_latest_topic">
                                        <div class="codo_categories_category_latest_topic_title">
                                            <span class="codo_latest_topic_text">{_t('Latest:')}</span> <a href="{$smarty.const.RURI}topic/{$cat->latestTopicData.topic_id}/{$cat->latestTopicData.title|URL_safe}">{$cat->latestTopicData.title}</a>
                                        </div>
                                        <div class="codo_categories_category_latest_topic_creator">
                                            <a href="{$smarty.const.RURI}user/profile/{$cat->latestTopicData.uid}"><span class="role_styled role_{$cat->latestTopicData.rname}">{$cat->latestTopicData.name}</span></a>, {$cat->latestTopicData.time|get_pretty_time}
                                        </div>
                                    </div>
                                {/if}
                            </div>
                        </li>
                        {/foreach}
                    </ul>

                </div>
            {/if}

            <div class="codo_topics col-lg-12">

                <div id="codo_topics_list">
                    {if $cat_info.no_topics > 0}
                        {$topics}
                    {else}
                        <div class="codo_zero_topics">
                            {_t("No topics created yet!")}<br/><br/>
                            {if $logged_in}
                                {_t("Be the first to")}
                                <a href="#" id="codo_zero_topics">{_t("create")}</a>
                                {_t("a topic")}
                            {/if}
                        </div>
                    {/if}
                </div>

                <span style="display: none">
                        {*Skeleton DIV to clone in jQuery*}
                        <div id="codo_topic_page_info">
                            <span id="codo_page_info_time_spent" data-toggle="tooltip"
                                  title="{_t("time spent reading previous page")}"></span>
                            <span id="codo_page_info_page_no" data-toggle="tooltip" title="{_t("page no.")}"></span>
                            <span id="codo_page_info_pages_to_go" data-toggle="tooltip"
                                  title="{_t("pages to go")}"></span>
                        </div>
                    </span>
            </div>


        </div>
    </div>
    <div class="container codo_bottom_create_topic_btn">
        <div class="row">
            <div class="col-sm-12 col-md-3" style="padding: 0">
                <div class="codo_icon_header codo_create_topic_btn_container">
                    <button id="codo_create_topic_btn" type="submit" style="margin-top: 15px;"
                            class="codo_btn codo_btn_primary codo_create_topic_btn"
                    >{_t("Create new topic")}</button>
                </div>
            </div>
        </div>
    </div>

    {if !$load_more_hidden}
        <div class="codo_topics_loadmore_div row" id="codo_topics_load_more">

            <div onclick="CODOF.changePage(this, {$curr_page}, 'prev')"
                 class="pagination_previous_page offset-md-1 col-md-2 col-sm-12{if $curr_page neq 1} active_page_controls{/if}">

                <i class="icon icon-arrow-left"></i>
                <div>{_t("Previous")}</div>
            </div>

            <div class="col-md-4 pagination_pages col-sm-12">
                {$pagination}
            </div>

            <div onclick="CODOF.changePage(this, {$curr_page}, 'next')"
                 class="pagination_next_page col-md-2 col-sm-12{if $curr_page neq $total_pages} active_page_controls{/if}">

                <div>{_t("Next")}</div>
                <i class="icon icon-arrow-right"></i>
            </div>

            {if $can_create_topic}
                <div class="pagination_new_topic col-md-2 col-sm-12">
                    <button id="codo_create_topic_btn" type="submit"
                            class="codo_btn codo_btn_primary codo_create_topic_btn">{_t("Create new topic")}</button>
                </div>
            {/if}
            <div class="offset-md-1"></div>

        </div>
    {/if}
    <div id='codo_delete_topic_confirm_html'>
        <div class='codo_posts_topic_delete'>
            <div class='codo_content'>
                {_t("All posts under this topic will be ")}<b>{_t("deleted")}</b> ?
                <br/>

                <div class="codo_consider_as_spam codo_spam_checkbox">
                    <input id="codo_spam_checkbox" name="spam" type="checkbox" checked="">
                    <label class="codo_spam_checkbox" for="spam">{_t('Mark as spam')}</label>
                </div>

            </div>
            <div class="codo_modal_footer">
                <div class="codo_btn codo_btn_def codo_modal_delete_topic_cancel">{_t("Cancel")}</div>
                <div class="codo_btn codo_btn_primary codo_modal_delete_topic_submit">{_t("Delete")}</div>
            </div>
            <div class="codo_spinner"></div>
        </div>
    </div>
    <div id="codo_topics_multiselect" class="codo_topics_multiselect">

        {{_t("With")}} <span id="codo_number_selected"></span> {{_t("selected")}}
        <span class="codo_multiselect_deselect codo_btn codo_btn_sm codo_btn_def"
              id="codo_multiselect_deselect">{{_t("deselect topics")}}</span>
        <select class="form-control" id="codo_topics_multiselect_select">
            <option value="nothing">{{_t("Select action")}}</option>
            <optgroup label="{{_t("Actions")}}">
                {if $can_delete}
                    <option value="delete">{{_t("Delete topics")}}</option>
                {/if}

                {if $can_merge}
                    <option disabled value="merge">{{_t("Merge topics")}}</option>
                {/if}

                {if $can_move}
                    <option value="move">{{_t("Move topics")}}</option>
                {/if}
            </optgroup>

        </select>

    </div>
    <div class="modal fade" id='codo_multiselect_delete'>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{_t("Delete topics")}</h4>
                    <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                </div>
                <div class="modal-body">
                    <p>{_t("Are you sure you want to delete the following topics including its replies ?")}</p>

                    <p>
                    <div id="codo_multiselect_delete_links"></div>
                    </p>

                </div>
                <div class="modal-footer">
                    <div class="codo_loading"></div>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{_t("Cancel")}</button>
                    <button onclick="multiselect.delete_topics()" type="button"
                            class="btn btn-primary">{_t("Delete")}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <div class="modal fade" id='codo_multiselect_merge'>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{_t("Merge topics")}</h4>
                    <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                </div>
                <div class="modal-body">
                    <p>{_t("Are you sure you want to merge the following topics ?")}</p>

                    <p>
                    <div id="codo_multiselect_merge_links"></div>
                    </p>

                    <p class="">{_t("Select the destination topic from above, where all topics will be merged")}</p>

                </div>
                <div class="modal-footer">
                    <div class="codo_loading"></div>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{_t("Cancel")}</button>
                    <button onclick="multiselect.merge_topics()" type="button"
                            class="btn btn-primary">{_t("Merge")}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <div class="modal fade" id='codo_multiselect_move'>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{_t("Move topics")}</h4>
                    <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                </div>
                <div class="modal-body">
                    <p>{_t("The selected topics will be moved to")}</p>

                    <p>

                        <select class="form-control" id="codo_multiselect_move_category_select">
                            {foreach from=$cats item=cat}
                                <option value="{$cat->cat_id}" role="presentation">{$cat->cat_name}</option>
                                {print_children cat=$cat el=option}
                            {/foreach}

                        </select>
                    </p>


                </div>
                <div class="modal-footer">
                    <div class="codo_loading"></div>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{_t("Cancel")}</button>
                    <button onclick="multiselect.move_topics()" type="button"
                            class="btn btn-primary">{_t("Move")}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    {include file='forum/editor.tpl'}
    <script type="text/javascript">

        CODOFVAR = {
            cid: '{$cat_info.cat_id}',
            cat_alias: '{$cat_alias}',
            curr_page: parseInt('{$curr_page}'),
            total: {$cat_info.no_topics},
            num_posts_per_page: {$num_posts_per_page},
            smileys: JSON.parse('{$forum_smileys}'),
            reply_min_chars: parseInt({$reply_min_chars}),
            dropzone: {
                dictDefaultMessage: '{_t("Drop files to upload &nbsp;&nbsp;(or click)")}',
                max_file_size: parseInt('{$max_file_size}'),
                allowed_file_mimetypes: '{$allowed_file_mimetypes}',
                forum_attachments_multiple: {$forum_attachments_multiple},
                forum_attachments_parallel: parseInt('{$forum_attachments_parallel}'),
                forum_attachments_max: parseInt('{$forum_attachments_max}')

            },
            trans: {
                continue_mesg: '{_t("Continue")}'
            },
            login_url: '{$login_url}',
            search_data: '{$search_data}',
            last_page: '{_t("last page")}',
            no_more_posts: '{_t("No more topics to display!")}',
            no_posts: '{_t("No topics found matching your criteria!")}'

        };

    </script>
    <link rel="stylesheet" type="text/css" href="{$smarty.const.DURI}assets/dropzone/css/basic.css"/>
    <link rel="stylesheet" type="text/css" href="{$smarty.const.DURI}assets/oembedget/oembed-get.css"/>
{/block}
