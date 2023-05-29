{* @CODOLICENSE *}
{* Smarty *}
{extends file='layout.tpl'}

{block name=body}
    <input type="hidden" id="page_sort_option" value="newest" xmlns:float="http://www.w3.org/1999/xhtml"/>
    <div class="container" id="codo_topics_row">

        <div class="row">

            <div class="col-lg-12 codo_mobile_top_container">

                <div class="codo_icon_header d-block d-sm-none  d-none d-sm-block d-lg-none container"
                     style="padding: 0">

                    <div onclick="CODOF.toggleTopicsAndCategories()" class="col-md-12 col-12"><i class="icon-books"
                                                                                                 title="{_t("Categories")}"></i>
                        <span id="codo_sm_categories_text">{_t("Show Categories")}</span>
                        <span id="codo_sm_topics_text" style="display: none">{_t("Show topics")}</span>
                    </div>

                    <span style="display: none"
                          id="icon-books-click-trans">{_t("Click the icon again to toggle between categories and topics")}</span>
                </div>

                {"block_catgory_list_before"|load_block}
                {if $can_search}
                    <div id="codo_mobile_top_search" class="col-sm-12">
                        <input type="text" placeholder="{_t('Search')}"
                               class="form-control codo_global_search_input"/>
                        <i class="glyphicon glyphicon-search codo_topics_search_icon"
                           title="Advanced search"></i>
                    </div>
                {/if}
            </div>
            <!--all topics -->

            <!--end all topics -->
            <div class="codo_categories col-lg-12" id="codo_categories_sidebar">


                <ul id="codo_categories_ul">
                    {foreach from=$cats item=label}
                        <li>
                            <div class="row">
                                <div class="codo_category_label">
                                    <div class="codo_category_title">
                                        <a href="#{$label->cat_alias}"> {$label->cat_name}</a>
                                        <span>{$label->cat_description}</span>
                                    </div>

                                    {*<span data-toggle="tooltip" data-placement="bottom"
                                          "
                                          class="codo_category_num_topics codo_bs_tooltip">
                                        {if $label->granted eq 1}
                                            {$label->no_topics|abbrev_no}
                                        {else} -
                                        {/if}
                                    </span>*}
                                </div>
                            </div>
                            {if isset($label->children)}
                            {foreach from=$label->children item=$cat}
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
                                            {_t('Latest:')} <a href="{$smarty.const.RURI}topic/{$cat->latestTopicData.topic_id}/{$cat->latestTopicData.title|URL_safe}">{$cat->latestTopicData.title}</a>
                                        </div>
                                        <div class="codo_categories_category_latest_topic_creator">
                                            <a href="{$smarty.const.RURI}user/profile/{$cat->latestTopicData.uid}"><span class="role_styled role_{$cat->latestTopicData.rname}">{$cat->latestTopicData.name}</span></a>, {$cat->latestTopicData.time|get_pretty_time}
                                        </div>
                                    </div>
                                    {/if}
                                </div>
                            {/foreach}
                            {/if}
                        </li>
                    {/foreach}
                </ul>
                {"block_catgory_list_after"|load_block}
            </div>

        </div>
    </div>
    <script type="text/javascript">

        CODOFVAR = {
            no_more_posts: '{_t("No more topics to display!")}',
            no_posts: '{_t("No topics found matching your criteria!")}',
            subcategory_dropdown: '{$subcategory_dropdown}',
            num_posts_per_page: 0,
            total: 0,
            last_page: '{_t("last page")}'
        };

    </script>
    <link rel="stylesheet" type="text/css" href="{$smarty.const.DURI}assets/oembedget/oembed-get.css"/>
{/block}