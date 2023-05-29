CODOF.hook.add('added_li', function () {

    $('a[href=#' + CODOFVAR.tab + ']').tab('show')
});

jQuery(document).ready(function ($) {

    $(window).scroll(function () {

        $('.codo_profile').css('top', $(window).scrollTop());

    });

    $('#overview').on('click', function () {

        $('.nav-box-profile-active').removeClass('nav-box-profile-active');
        $('#recent_posts').show();
        $('#pms').hide('slow');
        $(this).addClass('nav-box-profile-active');
    });


    $('#codo_mail_resent').hide();
    $('#codo_email_sending_img').hide();
    $('#edit-tab').on('click', function () {
        window.location.href = codo_defs.url + 'user/profile/' + CODOFVAR.userid + '/edit';
    });
    $('#codo_resend_mail').on('click', function () {

        $('#codo_email_sending_img').show();
        $.get(
            codo_defs.url + 'Ajax/user/register/resend_mail',
            {
                token: codo_defs.token
            },
            function (response) {

                if (response === "success") {

                    $('#codo_mail_resent').fadeIn('slow');
                } else {

                    $('#codo_resend_mail_failed').html(response).show('slow');
                }

                $('#codo_email_sending_img').hide();
            }
        );
    });

    CODOF.req.data = {
        token: codo_defs.token
    };

    CODOF.template = Handlebars.compile($("#codo_template").html());

    CODOF.hook.call('before_req_fetch_recent_posts', {}, function () {

        $.getJSON(
            codo_defs.url + 'Ajax/user/profile/' + CODOFVAR.userid + '/get_recent_posts',
            CODOF.req.data,
            function (response) {

                CODOF.context = response;
                var topics = CODOF.template(CODOF.context);
                $('.codo_load_more_gif').remove();

                //console.log(topics);
                $('#recent_posts').append(topics);

                var widths = $('#recent_posts  .codo_topics_last_post').map(function () {
                    return $(this).outerWidth();
                }).get();

                var max_width = Math.max.apply(null, widths);

                $('.codo_topics_last_post').css('width', max_width + "px");
            }
        );
    });

    CODOF.badges = {
        rewardedBadgeIds: [],
        badges: [],
        onBadgesFetched: function () {
            // We use this because user can click on 'reward badges' button even before loadBadges completes
            // and in that case since we do not make a request there, badges will be empty.
            // so we instead define this function there and let it get called on complete.
        },
        getBadgeHtml: function (badge, addIsRewarded) {
            const src = `${codo_defs.duri}${codo_defs.badges_path}${badge.badgeLocation}`;
            let rewardedCls = "";
            let badgeHtml = `<div id="codo_badge_${badge.id}" data-trigger="hover" data-placement="bottom" data-toggle="popover"`;
            if (badge.description.length > 0) {
                badgeHtml += ` title="${badge.name}" data-content="${badge.description}" `
            } else {
                badgeHtml += ` data-content="${badge.name}" `
            }
            if (badge.isRewarded && addIsRewarded) {
                CODOF.badges.rewardedBadgeIds.push(badge.id);
                rewardedCls = " codo_badge_rewarded";
            }
            badgeHtml += ` class="codo_badge${rewardedCls}"><i class="fa fa-check"></i> <img src="${src}" alt="badge"/></div>`;
            return badgeHtml;
        },
        loadBadges: function () {
            CODOF.request.get({
                url: `${codo_defs.url}badge/user/${CODOFVAR.userid}`,
                done: function (response) {
                    if (response.success) {
                        CODOF.badges.badges = response.data;
                        CODOF.badges.onBadgesFetched();
                        let badgesHtml = "";
                        CODOF.badges.badges.filter(badge => badge.isRewarded).forEach(badge => {
                            badgesHtml += CODOF.badges.getBadgeHtml(badge, false);
                        });
                        if (badgesHtml !== "") {
                            $('.codo_user_badges').html(badgesHtml).css('display', 'flex');
                            $('.codo_user_badges [data-toggle="popover"]').popover();
                        } else {
                            $('.codo_user_badges').hide();
                        }
                    } else {
                        console.error("Error while fetching badges for user " + CODOFVAR.userid);
                    }
                }
            });
        }
    };

    CODOF.badges.loadBadges();

    $('#codo_reward_badges').on('shown.bs.modal', function () {
        const $badgesListDiv = $('.codo_badges-all-list');
        CODOF.badges.onBadgesFetched = function () {
            $('#codo_reward_badges .codo_load_more_gif').remove();
            const badges = CODOF.badges.badges;
            let badgesHtml = "";
            let numDeletedBadges = 0;
            CODOF.badges.rewardedBadgeIds = [];
            badges.forEach(badge => {
                badgesHtml += CODOF.badges.getBadgeHtml(badge, true);
            });
            $badgesListDiv.html(badgesHtml);
            $('.codo_badges-all-list [data-toggle="popover"]').popover();

            $('.codo_badge').on('click', function () {
                const $badge = $(this);
                const id = parseInt($badge.attr('id').replace('codo_badge_', ''));
                $badge.toggleClass('codo_badge_rewarded');

                if (CODOF.badges.rewardedBadgeIds.indexOf(id) > -1) {
                    if ($badge.hasClass('codo_badge_rewarded')) {
                        numDeletedBadges--;
                    } else {
                        numDeletedBadges++;
                    }
                }
                if (numDeletedBadges > 0) {
                    $('#codo_badges_removed').show().find('b').html(numDeletedBadges);
                } else {
                    $('#codo_badges_removed').hide();
                }
            });
        };

        // Data already there
        if (CODOF.badges.badges.length > 0) {
            CODOF.badges.onBadgesFetched();
        } else {
            // Will be called automatically after request completes
            $badgesListDiv.html('<div class="codo_load_more_gif"></div>');
        }
    });

    $('#codo_save_badges').on('click', function (e) {
        e.preventDefault();
        $('#codo_reward_badges .codo_load_more_bar_black_gif').show();
        let addBadgeIds = [];
        let removeBadgeIds = [];
        $('#codo_reward_badges .codo_badge').each(function () {
            const id = parseInt(this.id.replace("codo_badge_", ""));
            if (CODOF.badges.rewardedBadgeIds.indexOf(id) > -1) {
                if (!this.classList.contains('codo_badge_rewarded')) {
                    removeBadgeIds.push(id);
                }
            } else {
                if (this.classList.contains('codo_badge_rewarded')) {
                    addBadgeIds.push(id);
                }
            }
        });

        CODOF.badges.onBadgesFetched = () => {
            $('#codo_reward_badges').modal('hide');
            $('#codo_reward_badges .codo_load_more_bar_black_gif').hide();
            $('#codo_badges_removed').hide();
        };

        if (addBadgeIds.length > 0 || removeBadgeIds.length > 0) {
            CODOF.request.post({
                url: `${codo_defs.url}badge/user/${CODOFVAR.userid}`,
                data: {
                    addBadgeIds: addBadgeIds,
                    removeBadgeIds: removeBadgeIds
                },
                done: function (response) {
                    //replace badges for user
                    CODOF.badges.loadBadges();
                }
            });
        } else {
            CODOF.badges.onBadgesFetched();
        }
    });

    $('#codo_reward_badges .codo_load_more_bar_black_gif').hide();

});
