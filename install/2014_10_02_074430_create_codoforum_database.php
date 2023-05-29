<?php

class codoforumInstallDatabase
{

    public function down()
    {

        $tablesToDrop = array('codo_bans', 'codo_block_roles', 'codo_blocks',
            'codo_categories', 'codo_config', 'codo_crons', 'codo_logs',
            'codo_mail_queue', 'codo_notify', 'codo_notify_queue',
            'codo_notify_subscribers', 'codo_notify_text',
            'codo_permissions', 'codo_permission_list', 'codo_plugins',
            'codo_posts', 'codo_roles', 'codo_user_roles', 'codo_sessions',
            'codo_signups', 'codo_smileys', 'codo_tags', 'codo_tags_allowed',
            'codo_topics', 'codo_unread_categories', 'codo_unread_topics',
            'codo_user_preferences', 'codo_users', 'codo_views',
            'b8_wordlist', 'codo_pages', 'codo_page_roles',
            'codo_fields', 'codo_fields_roles', 'codo_fields_values',
            'codo_reports', 'codo_report_types',
            'codo_edits', 'codo_reputation', 'codo_daily_rep_log',
            'codo_promotion_rules', 'codo_poll_log', 'codo_poll_questions',
            'codo_poll_options', 'codo_attachments', 'codo_import_data',
            'codo_badges', 'codo_user_badges');

        foreach ($tablesToDrop as $table) {

            Schema::dropIfExists($table);
        }
    }

    public function up()
    {


        Schema::create('codo_bans', function ($table) {
            $table->increments('id');
            $table->string('uid', 100);
            $table->enum('ban_type', array('name', 'mail', 'ip'));
            $table->string('ban_by', 100);
            $table->integer('ban_on');
            $table->string('ban_reason', 300);
            $table->integer('ban_expires');
        });


        /**
         * Table: codo_block_roles
         */
        Schema::create('codo_block_roles', function ($table) {
            $table->integer('bid');
            $table->integer('rid');
            $table->index('rid');
            $table->index('bid');
        });


        /**
         * Table: codo_blocks
         */
        Schema::create('codo_blocks', function ($table) {
            $table->increments('id');
            $table->string('module', 64);
            $table->string('theme', 64);
            $table->integer('status');
            $table->integer('weight');
            $table->string('region', 64);
            $table->text('content');
            $table->integer('visibility');
            $table->text('pages');
            $table->string('title', 64);
            $table->integer('cache')->default("1");
        });


        /**
         * Table: codo_categories
         */
        Schema::create('codo_categories', function ($table) {
            $table->increments('cat_id');
            $table->integer('cat_pid');
            $table->string('cat_name', 255);
            $table->string('cat_alias', 300);
            $table->string('cat_description', 400)->nullable();
            $table->string('cat_img', 200);
            $table->integer('no_topics');
            $table->integer('no_posts');
            $table->integer('cat_order');
            $table->integer('is_label')->default(0);
            $table->integer('show_children')->default(1);
            $table->integer('default_subscription_type')->default(\CODOF\Forum\Notification\Subscriber::$DEFAULT);
        });


        /**
         * Table: codo_config
         */
        Schema::create('codo_config', function ($table) {
            $table->increments('id');
            $table->string('option_name', 50)->unique();
            $table->text('option_value');
        });


        /**
         * Table: codo_crons
         */
        Schema::create('codo_crons', function ($table) {
            $table->increments('id');
            $table->string('cron_name', 100);
            $table->enum('cron_type', array('once', 'recurrence'));
            $table->integer('cron_interval');
            $table->integer('cron_started');
            $table->integer('cron_last_run');
            $table->boolean('cron_status');
            $table->index('cron_name');
        });


        /**
         * Table: codo_logs
         */
        Schema::create('codo_logs', function ($table) {
            $table->increments('id');
            $table->integer('uid');
            $table->string('log_type', 64);
            $table->text('message');
            $table->integer('severity');
            $table->text('trace');
            $table->integer('log_time');
        });


        /**
         * Table: codo_mail_queue
         */
        Schema::create('codo_mail_queue', function ($table) {
            $table->increments('id');
            $table->integer('mail_type')->nullable()->default("1");
            $table->integer('mail_status')->default("0");
            $table->string('from_address', 200)->nullable();
            $table->string('to_address', 200);
            $table->string('mail_subject', 400);
            $table->longText('body');
        });


        /**
         * Table: codo_notify
         */
        Schema::create('codo_notify', function ($table) {
            $table->increments('id');
            $table->string('type', 100);
            $table->integer('uid');
            $table->integer('nid');
            $table->integer('created');
            $table->integer('is_read');
            $table->index('is_read');
            $table->index('nid');
        });


        /**
         * Table: codo_notify_queue
         */
        Schema::create('codo_notify_queue', function ($table) {
            $table->increments('id');
            $table->string('type', 200);
            $table->integer('nid');
        });


        /**
         * Table: codo_notify_subscribers
         */
        Schema::create('codo_notify_subscribers', function ($table) {
            $table->increments('id');
            $table->integer('cid')->nullable();
            $table->integer('tid')->nullable();
            $table->integer('uid');
            $table->string('type', 60);
            $table->index('cid');
            $table->index('tid');
            $table->index('uid');
            $table->index('type');
        });


        /**
         * Table: codo_notify_text
         */
        Schema::create('codo_notify_text', function ($table) {
            $table->increments('id');
            $table->string('data', 1024);
            $table->integer('status_link')->default(-1);
        });

        /**
         * Table: codo_permissions
         */
        Schema::create('codo_permissions', function ($table) {

            $table->increments('pid');
            $table->integer('rid')->index();
            $table->integer('cid');
            $table->integer('tid');
            $table->string('permission', 128);
            $table->integer('granted');
            $table->integer('inherited');
        });

        /**
         * Table: codo_permission_list
         */
        Schema::create('codo_permission_list', function ($table) {

            $table->increments('id');
            $table->string('permission', 128);
            $table->string('type', 50);
        });

        /**
         * Table: codo_plugins
         */
        Schema::create('codo_plugins', function ($table) {
            $table->string('plg_name', 255);
            $table->string('plg_type', 10);
            $table->integer('plg_status');
            $table->integer('plg_weight');
            $table->string('plg_schema_ver', 50);
            $table->unique('plg_name');
        });


        /**
         * Table: codo_posts
         */
        Schema::create('codo_posts', function ($table) {
            $table->increments('post_id');
            $table->integer('topic_id');
            $table->integer('cat_id');
            $table->integer('uid');
            $table->text('imessage');
            $table->text('omessage');
            $table->integer('post_created');
            $table->integer('post_modified')->nullable();
            $table->integer('post_status')->default("1");
            $table->integer('reputation')->default('0');
        });

        /**
         * Table: codo_edits
         */
        Schema::create('codo_edits', function ($table) {
            $table->increments('id');
            $table->integer('post_id');
            $table->integer('uid');
            $table->text('text');
            $table->integer('time');
        });


        /**
         * Table: codo_reputation
         */
        Schema::create(PREFIX . 'codo_reputation', function ($table) {
            $table->increments('id');
            $table->integer('from_id');
            $table->integer('to_id');
            $table->integer('post_id');
            $table->integer('points');
            $table->integer('rep_time');
        });

        /**
         * Table: codo_daily_rep_log
         */
        Schema::create(PREFIX . 'codo_daily_rep_log', function ($table) {
            $table->integer('uid');
            $table->integer('rep_count');
            $table->integer('start_rep_time');
        });

        /**
         * Table: codo_promotion_rules
         */
        Schema::create(PREFIX . 'codo_promotion_rules', function ($table) {
            $table->increments('id');
            $table->integer('reputation');
            $table->integer('posts');
            $table->integer('type');
            $table->integer('rid');
        });

        /**
         * Table: codo_roles
         */
        Schema::create('codo_roles', function ($table) {
            $table->increments('rid');
            $table->string('rname', 40);
            $table->text('color')->nullable();
        });


        /**
         * Table: codo_sessions
         */
        Schema::create('codo_sessions', function ($table) {
            $table->string('sid', 255);
            $table->integer('last_active');
            $table->text('session_data');
            $table->primary('sid');
        });


        /**
         * Table: codo_signups
         */
        Schema::create('codo_signups', function ($table) {
            $table->increments('id');
            $table->string('username', 128);
            $table->string('token', 128);
        });


        /**
         * Table: codo_smileys
         */
        Schema::create('codo_smileys', function ($table) {
            $table->increments('id');
            $table->string('symbol', 50);
            $table->string('image_name', 200);
            $table->integer('weight')->nullable();
        });


        /**
         * Table: codo_tags
         */
        Schema::create('codo_tags', function ($table) {
            $table->increments('tag_id');
            $table->string('tag_name', 50);
            $table->integer('topic_id');
        });


        /**
         * Table: codo_tags_allowed
         */
        Schema::create('codo_tags_allowed', function ($table) {
            $table->increments('tag_id');
            $table->text('tag_text');
            $table->integer('cat_id');
            $table->index('cat_id');
        });


        /**
         * Table: codo_topics
         */
        Schema::create('codo_topics', function ($table) {
            $table->increments('topic_id');
            $table->string('title', 1000);
            $table->smallInteger('cat_id');
            $table->integer('post_id')->nullable();
            $table->integer('uid');
            $table->integer('last_post_id')->default("0");
            $table->string('last_post_uid', 200)->nullable();
            $table->string('last_post_name', 200)->nullable();
            $table->integer('topic_created');
            $table->integer('topic_updated')->default("0");
            $table->integer('topic_close')->default('0');
            $table->integer('last_post_time');
            $table->integer('no_posts')->default("0");
            $table->integer('no_views')->default("0");
            $table->integer('topic_status')->default("1");
            $table->integer('redirect_to')->nullable();
            $table->index('last_post_time');
            $table->index(array('cat_id', 'uid', 'topic_created'));
        });


        /**
         * Table: codo_unread_categories
         */
        Schema::create('codo_unread_categories', function ($table) {
            $table->integer('cat_id');
            $table->integer('uid');
            $table->integer('read_time');
            $table->primary(array('cat_id', 'uid'));
        });

        /**
         * Table: codo_unread_topics
         */
        Schema::create('codo_unread_topics', function ($table) {
            $table->integer('cat_id');
            $table->integer('topic_id');
            $table->integer('uid');
            $table->integer('read_time');
            $table->primary(array('topic_id', 'uid'));
        });


        /**
         * Table: codo_user_preferences
         */
        Schema::create('codo_user_preferences', function ($table) {
            $table->increments('id');
            $table->integer('uid');
            $table->string('preference', 100);
            $table->string('value', 400);
            $table->index('uid');
            $table->index('preference');
        });


        /**
         * Table: codo_users
         */
        Schema::create('codo_users', function ($table) {
            $table->increments('id');
            $table->string('username', 60);
            $table->string('name', 100)->nullable();
            $table->string('pass', 128);
            $table->string('token', 64)->nullable();
            $table->string('mail', 200)->unique();
            $table->integer('created');
            $table->integer('last_access')->default("0");
            $table->integer('read_time')->default("0");
            $table->integer('user_status')->default("1");
            $table->string('avatar', 200)->nullable();
            $table->text('signature')->nullable();
            $table->integer('no_posts')->default("0");
            $table->integer('profile_views')->default("0");
            $table->string('oauth_id', 128);
            $table->integer('reputation')->default('0');
            $table->integer('last_notification_view_time')->default(0);
            $table->index('username');
            $table->index('mail');
        });

        /**
         * Table: codo_user_roles
         */
        Schema::create('codo_user_roles', function ($table) {

            $table->integer('uid');
            $table->integer('rid');
            $table->integer('is_primary');
            $table->integer('is_promoted')->default('0');
            $table->primary(array('uid', 'rid'));
        });

        /**
         * Table: codo_views
         */
        Schema::create('codo_views', function ($table) {
            $table->date('date');
            $table->integer('views');
            $table->primary('date');
        });

        Schema::create('b8_wordlist', function ($table) {

            $table->string('token', 255);
            $table->integer('count_ham')->nullable();
            $table->integer('count_spam')->nullable();
            $table->primary('token');
        });

        DB::table('b8_wordlist')->insert(array(
            array(
                'token' => 'b8*dbversion',
                'count_ham' => 3,
                'count_spam' => NULL
            ),
            array(
                'token' => 'b8*texts',
                'count_ham' => 0,
                'count_spam' => 0
            )
        ));


        Schema::create(PREFIX . 'codo_pages', function ($table) {

            $table->increments('id');
            $table->string('title', 300);
            $table->string('url', 300);
            $table->text('content');
        });

        Schema::create(PREFIX . 'codo_page_roles', function ($table) {

            $table->integer('pid')->index();
            $table->integer('rid')->index();
        });


        Schema::create(PREFIX . 'codo_fields', function ($table) {

            $table->increments('id');
            $table->string('name', 100);
            $table->string('title', 200);
            $table->string('type', 50);
            $table->integer('show_reg');
            $table->integer('is_mandatory');
            $table->integer('show_profile');
            $table->integer('weight');
            $table->string('output_format', 500);
            $table->integer('enabled');
            $table->string('input_type', 50)->nullable();
            $table->integer('input_length');
            $table->text('data');
            $table->integer('hide_not_set');
            $table->string('def_value', 500);
        });

        Schema::create(PREFIX . 'codo_fields_roles', function ($table) {

            $table->integer('fid');
            $table->integer('rid');
            $table->primary(array('fid', 'rid'));
        });

        Schema::create(PREFIX . 'codo_fields_values', function ($table) {

            $table->integer('uid');
            $table->integer('fid');
            $table->text('value');
            $table->primary(array('uid', 'fid'));
        });


        Schema::create(PREFIX . 'codo_reports', function ($table) {

            $table->increments('id');
            $table->integer('topic_id');
            $table->integer('post_id');
            $table->integer('report_type');
            $table->string('details', 400);
            $table->integer('status');
            $table->integer('uid');
            $table->integer('time');
        });

        Schema::create(PREFIX . 'codo_report_types', function ($table) {

            $table->increments('id');
            $table->string('name', 310);
            $table->integer('count')->default('10');
            $table->integer('action')->default('1');
        });


        Schema::create(PREFIX . 'codo_poll_log', function ($table) {

            $table->increments('id');
            $table->integer('poll_id');
            $table->integer('option_id');
            $table->integer('uid');
            $table->integer('active');
            $table->integer('voted_on');
        });


        Schema::create(PREFIX . 'codo_poll_options', function ($table) {

            $table->increments('id');
            $table->integer('poll_id');
            $table->string('option_name');
            $table->integer('num_votes');
            $table->integer('option_status');
            $table->integer('option_created');
        });

        Schema::create(PREFIX . 'codo_poll_questions', function ($table) {

            $table->increments('id');
            $table->integer('topic_id')->unique();
            $table->string('question');
            $table->integer('num_votable');
            $table->integer('can_recast');
            $table->integer('total_votes')->default(0);
            $table->integer('public_vote_result');
            $table->integer('view_result_without_vote');
            $table->integer('end_time');
            $table->integer('is_active')->default(1);
        });

        Schema::create(PREFIX . 'codo_attachments', function ($table) {

            $table->increments('id');
            $table->string('original_name');
            $table->string('visible_hash')->unique();
            $table->string('stored_hash');
            $table->string('location');
            $table->integer('uid');
            $table->integer('upload_time');
            $table->integer('file_size');
        });

        Schema::create(PREFIX. 'codo_import_data', function ($table) {
            $table->increments('id');
            $table->text('data');
        });

        Schema::create(PREFIX . 'codo_badges', function ($table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->text('description');
            $table->text('location');
            $table->integer('view_order')->default(0);
        });

        Schema::create(PREFIX . 'codo_user_badges', function ($table) {
            $table->increments('id');
            $table->integer('user_id')->index('user_id_index');
            $table->integer('badge_id');
            $table->integer('rewarded_date');
            $table->integer('rewarded_by');
        });
    }

    private function build_arr($columns, $values)
    {

        $multi_value = array();

        foreach ($values as $value) {

            $multi_value[] = $this->combine($columns, $value);
        }

        return $multi_value;
    }

    private function combine($col, $row)
    {

        $arr = array();

        $i = 0;
        foreach ($row as $val) {

            $arr[$col[$i]] = $val;

            $i++;
        }

        return $arr;
    }

    public function chunkInsert($table, $rows)
    {

        $chunks = array_chunk($rows, 100);

        foreach ($chunks as $chunk) {

            try {
                DB::table($table)->insert(
                    $chunk
                );
            } catch (Exception $e) {

                $queries = DB::getQueryLog();
                $last_query = end($queries);

                var_dump($last_query);
                throw $e;
            }
        }
    }

    public function fill()
    {

        $values = array(
            array(3, 0, 'General Discussions', 'general-discussions', 'For anything and everything that doesnt fit in other categories.', 'bubbles.png', 1, 1, 0),
            array(10, 0, 'News and Announcements', 'news-and-announcements', 'this is where all the latest news will be posted', 'bullhorn.png', 0, 0, 0),
            array(11, 0, 'Support Forums', 'support-forums', 'Have any problem? Report it here and we will be glad to help.', 'support.png', 0, 0, 2),
            array(12, 0, 'Let us know', 'let-us-know', 'We encourage new members to post a short description about themselves', 'envelope.png', 0, 0, 2),
            array(13, 0, 'Bug Reports', 'bug-reports', 'Found a bug? why not report it here?', 'bug.png', 0, 0, 2),
            array(14, 0, 'Feature Requests', 'feature-requests', 'You have a cool idea? post them here!', 'wand.png', 0, 0, 2)
        );

        $columns = array('cat_id', 'cat_pid', 'cat_name', 'cat_alias', 'cat_description', 'cat_img', 'no_topics', 'no_posts', 'cat_order');


        $cats = $this->build_arr($columns, $values);

        DB::table('codo_categories')->insert(
            $cats
        );


        $columns = array('id', 'option_name', 'option_value');

        $values = array(
            array(1, 'site_url', ''),
            array(2, 'site_title', 'CODOLOGIC'),
            array(3, 'site_description', 'codoforum - Enhancing your forum experience with next generation technology!'),
            array(4, 'admin_email', 'admin@example.com'),
            array(5, 'theme', '2k18'),
            array(6, 'captcha_public_key', ''),
            array(7, 'captcha_private_key', ''),
            array(8, 'register_pass_min', '8'),
            array(9, 'num_posts_all_topics', '30'),
            array(10, 'num_posts_cat_topics', '20'),
            array(11, 'num_posts_per_topic', '20'),
            array(12, 'forum_attachments_path', 'assets/img/attachments'),
            array(13, 'forum_attachments_exts', 'jpg,jpeg,png,gif,pjpeg,bmp,txt'),
            array(14, 'forum_attachments_size', '3'),
            array(15, 'forum_attachments_mimetypes', 'image/*,text/plain'),
            array(16, 'forum_attachments_multiple', 'true'),
            array(17, 'forum_attachments_parallel', '4'),
            array(18, 'forum_attachments_max', '10'),
            array(19, 'reply_min_chars', '10'),
            array(20, 'subcategory_dropdown', 'hidden'),
            array(21, 'captcha', 'disabled'),
            array(22, 'await_approval_message', "Dear [user:username],<br/><br/>Thank you for registering at [option:site_title]. Before we can activate your account one last step must be taken to complete your registration.<br/><br/>To complete your registration, please visit this URL: [this:confirm_url]<br/><br/>Your Username is: [user:username] <br/><br/>If you are still having problems signing up please contact a member of our support staff at [option:admin_email]<br/><br/>Regards,<br/>[option:site_title]"),
            array(23, 'await_approval_subject', 'Confirm your email for [user:username] at [option:site_title]'),
            array(24, 'mail_type', 'mail'),
            array(25, 'smtp_protocol', 'ssl'),
            array(26, 'smtp_server', 'smtp.gmail.com'),
            array(27, 'smtp_port', '465'),
            array(28, 'smtp_username', 'admin@example.com'),
            array(29, 'smtp_password', 'your_smtp_pass'),
            array(30, 'register_username_min', '3'),
            array(31, 'signature_char_lim', '255'),
            array(32, 'sso_client_id', 'codoforum'),
            array(33, 'sso_secret', 'Xe24!rf'),
            array(34, 'sso_get_user_path', 'http://localhost/page/codoforum_sso/user'),
            array(35, 'sso_login_user_path', 'http://localhost/page/user?codoforum=sso'),
            array(36, 'sso_logout_user_path', 'http://localhost/page/user/logout'),
            array(37, 'sso_register_user_path', 'http://localhost/page/user/lot'),
            array(38, 'sso_name', 'Codologic'),
            array(39, 'post_notify_message', "Hi, <br/><br/>[user:username] has replied to the topic: [post:title]<br/><br/>----<br/>[post:omessage]<br/>----<br/><br/>You can view the reply at the following url<br/>[post:url]<br/><br/>Regards,<br/>[option:site_title] team<br/>"),
            array(40, 'post_notify_subject', '[post:title] - new reply'),
            array(41, 'password_reset_message', "Hi,<br/><br/>A request has been made to reset your account password. <br/>\r\To reset your password, please follow the below link:<br/>[user:link]<br/>\rPassword reset token: [user:token]<br/><br/>Regards,<br/>[option:site_title] team<br/>"),
            array(42, 'password_reset_subject', 'Your [option:site_title] password reset request'),
            array(43, 'topic_notify_message', "Hi [post:username],<br/><br/>[user:username] has created a new topic: [post:title]<br/>in category [post:category]<br/><br/>You can view the topic by clicking [post:url]<br/><br/>Regards,<br/>[option:site_title] team"),
            array(44, 'topic_notify_subject', '[post:category] - new topic'),
            array(45, 'version', '5.2.1.12'),
            array(46, 'brand_img', 'https://codoforum.com/img/favicon-32x32.png'),
            array(47, 'reg_req_admin', 'no'),
            array(48, 'max_rep_per_day', 100),
            array(49, 'rep_req_to_inc', 0),
            array(50, 'posts_req_to_inc', 0),
            array(51, 'rep_req_to_dec', 0),
            array(52, 'posts_req_to_dec', 0),
            array(53, 'rep_times_same_user', 5),
            array(54, 'rep_hours_same_user', 24),
            array(55, 'enable_reputation', 'yes'),
            array(56, 'forum_tags_num', 5),
            array(57, 'forum_tags_len', 15),
            array(58, 'ml_spam_filter', 'no'),
            array(59, 'insert_oembed_videos', 'yes'),
            array(60, 'force_https', 'no')
        );

        $configs = $this->build_arr($columns, $values);

        DB::table('codo_config')->insert(
            $configs
        );


        DB::table(PREFIX . 'codo_config')->insert(array(

                array(
                    'option_name' => 'GOOGLE_ID',
                    'option_value' => 'NO_VAL',
                ),
                array(
                    'option_name' => 'GOOGLE_SECRET',
                    'option_value' => 'NO_VAL',
                ),
                array(
                    'option_name' => 'GOOGLE_REDIRECT',
                    'option_value' => 'NO_VAL',
                ),
                array(
                    'option_name' => 'FB_ID',
                    'option_value' => 'NO_VAL',
                ),
                array(
                    'option_name' => 'FB_SECRET',
                    'option_value' => 'NO_VAL',
                ),
                array(
                    'option_name' => 'FB_REDIRECT',
                    'option_value' => 'NO_VAL',
                ),
                array(
                    'option_name' => 'TW_ID',
                    'option_value' => 'NO_VAL',
                ),
                array(
                    'option_name' => 'TW_SECRET',
                    'option_value' => 'NO_VAL',
                ),
                array(
                    'option_name' => 'TW_REDIRECT',
                    'option_value' => 'NO_VAL',
                ),
                array(
                    'option_name' => 'GITHUB_ID',
                    'option_value' => 'NO_VAL',
                ),
                array(
                    'option_name' => 'GITHUB_SECRET',
                    'option_value' => 'NO_VAL',
                ),
                array(
                    'option_name' => 'GITHUB_URL',
                    'option_value' => 'NO_VAL',
                ),
                array(
                    'option_name' => 'default_language',
                    'option_value' => 'en_US',
                ),
                array(
                    'option_name' => 'forum_logo',
                    'option_value' => 'codoforum.png',
                ),
                array(
                    'option_name' => 'forum_header_menu',
                    'option_value' => 'site_title'
                ),
                array(
                    'option_name' => 'login_by',
                    'option_value' => 'USERNAME'
                ),
                array(
                    'option_name' => 'vote_up_notify_subject',
                    'option_value' => '[post:title] - vote up'
                ),
                array(
                    'option_name' => 'vote_up_notify_message',
                    'option_value' => "Hi, <br/><br/>[user:username] has up voted your post in the topic: [post:title]<br/><br/>----<br/>[post:omessage]<br/>----<br/><br/>You can view the post at the following url<br/>[post:url]<br/><br/>Regards,<br/>[option:site_title] team<br/>"
                ),
                array(
                    'option_name' => 'vote_down_notify_subject',
                    'option_value' => '[post:title] - vote down'
                ),
                array(
                    'option_name' => 'vote_down_notify_message',
                    'option_value' => "Hi, <br/><br/>[user:username] has down voted your post in the topic: [post:title]<br/><br/>----<br/>[post:omessage]<br/>----<br/><br/>You can view the post at the following url<br/>[post:url]<br/><br/>Regards,<br/>[option:site_title] team<br/>"
                ),
                array(
                    'option_name' => 'forum_type',
                    'option_value' => 'modern'
                ),
                array(
                    "option_name" => "mail_dequeue_batch_num",
                    "option_value" => "10"
                ),
                array(
                    "option_name" => "sidebar_hide_topic_messages",
                    "option_value" => "off"
                ),
                array(
                    "option_name" => "sidebar_infinite_scrolling",
                    "option_value" => "on"
                ),
                array(
                    "option_name" => "show_sticky_topics_without_permission",
                    "option_value" => "no"
                ),
                array(
                    "option_name" => "user_redirect_after_login",
                    "option_value" => "topics"
                ),
                array(
                    "option_name" => "approval_notify_mails",
                    "option_value" => ""
                ),
                array(
                    "option_name" => "new_registration_subject",
                    "option_value" => "A new user registration has been made"
                ),
                array(
                    "option_name" => "new_registration_message",
                    "option_value" => "Hi [this:name],<br/><br/>[user:username] has registered on the forum and is waiting for your approval.<br/> You can approve the user by logging to the backend and clicking <a href=\"[this:approveUrl]\">Moderation -> Approve users</a><br/><br/>Regards,<br/>[option:site_title] team"
                ),
                array(
                    "option_name" => "default_timezone",
                    "option_value" => "Europe/London"
                ),
                [
                    'option_name' => 'codoforum_server',                    
                    'option_value' => 'https://backend.codoforum.com',
                ]
            )
        );

        //------------- codo_permissions -------------------//

        $columns = array('pid', 'cid', 'tid', 'rid', 'permission', 'granted', 'inherited');
        $values = array(
            array(296, 0, 0, 1, 'view user profiles', 1, -1),
            array(297, 0, 0, 1, 'use search', 1, -1),
            array(298, 0, 0, 1, 'view all topics', 1, -1),
            array(299, 0, 0, 1, 'view my topics', 0, -1),
            array(300, 0, 0, 1, 'create new topic', 0, -1),
            array(301, 0, 0, 1, 'reply to all topics', 0, -1),
            array(302, 0, 0, 1, 'edit my topics', 0, -1),
            array(303, 0, 0, 1, 'edit all topics', 0, -1),
            array(304, 0, 0, 1, 'delete my topics', 0, -1),
            array(305, 0, 0, 1, 'delete all topics', 0, -1),
            array(306, 0, 0, 1, 'view forum', 1, -1),
            array(355, 0, 0, 1, 'edit my posts', 0, -1),
            array(356, 0, 0, 1, 'edit all posts', 0, -1),
            array(357, 0, 0, 1, 'delete my posts', 0, -1),
            array(358, 0, 0, 1, 'delete all posts', 0, -1),
            array(359, 0, 0, 2, 'view user profiles', 1, -1),
            array(360, 0, 0, 2, 'use search', 1, -1),
            array(361, 0, 0, 2, 'view all topics', 1, -1),
            array(362, 0, 0, 2, 'view my topics', 0, -1),
            array(363, 0, 0, 2, 'create new topic', 1, -1),
            array(364, 0, 0, 2, 'reply to all topics', 1, -1),
            array(365, 0, 0, 2, 'edit my topics', 1, -1),
            array(366, 0, 0, 2, 'edit all topics', 0, -1),
            array(367, 0, 0, 2, 'delete my topics', 1, -1),
            array(368, 0, 0, 2, 'delete all topics', 0, -1),
            array(369, 0, 0, 2, 'view forum', 1, -1),
            array(370, 0, 0, 2, 'edit my posts', 1, -1),
            array(371, 0, 0, 2, 'edit all posts', 0, -1),
            array(372, 0, 0, 2, 'delete my posts', 1, -1),
            array(373, 0, 0, 2, 'delete all posts', 0, -1),
            array(374, 0, 0, 3, 'view user profiles', 1, -1),
            array(375, 0, 0, 3, 'use search', 1, -1),
            array(376, 0, 0, 3, 'view all topics', 1, -1),
            array(377, 0, 0, 3, 'view my topics', 0, -1),
            array(378, 0, 0, 3, 'create new topic', 1, -1),
            array(379, 0, 0, 3, 'reply to all topics', 1, -1),
            array(380, 0, 0, 3, 'edit my topics', 1, -1),
            array(381, 0, 0, 3, 'edit all topics', 1, -1),
            array(382, 0, 0, 3, 'delete my topics', 1, -1),
            array(383, 0, 0, 3, 'delete all topics', 1, -1),
            array(384, 0, 0, 3, 'view forum', 1, -1),
            array(385, 0, 0, 3, 'edit my posts', 1, -1),
            array(386, 0, 0, 3, 'edit all posts', 1, -1),
            array(387, 0, 0, 3, 'delete my posts', 1, -1),
            array(388, 0, 0, 3, 'delete all posts', 1, -1),
            array(389, 0, 0, 4, 'view user profiles', 1, -1),
            array(390, 0, 0, 4, 'use search', 1, -1),
            array(391, 0, 0, 4, 'view all topics', 1, -1),
            array(392, 0, 0, 4, 'view my topics', 0, -1),
            array(393, 0, 0, 4, 'create new topic', 1, -1),
            array(394, 0, 0, 4, 'reply to all topics', 1, -1),
            array(395, 0, 0, 4, 'edit my topics', 1, -1),
            array(396, 0, 0, 4, 'edit all topics', 1, -1),
            array(397, 0, 0, 4, 'delete my topics', 1, -1),
            array(398, 0, 0, 4, 'delete all topics', 1, -1),
            array(399, 0, 0, 4, 'view forum', 1, -1),
            array(400, 0, 0, 4, 'edit my posts', 1, -1),
            array(401, 0, 0, 4, 'edit all posts', 1, -1),
            array(402, 0, 0, 4, 'delete my posts', 1, -1),
            array(403, 0, 0, 4, 'delete all posts', 1, -1),
            array(404, 0, 0, 6, 'view user profiles', 1, -1),
            array(405, 0, 0, 6, 'use search', 1, -1),
            array(406, 0, 0, 6, 'view all topics', 0, -1),
            array(407, 0, 0, 6, 'view my topics', 0, -1),
            array(408, 0, 0, 6, 'create new topic', 0, -1),
            array(409, 0, 0, 6, 'reply to all topics', 0, -1),
            array(410, 0, 0, 6, 'edit my topics', 0, -1),
            array(411, 0, 0, 6, 'edit all topics', 0, -1),
            array(412, 0, 0, 6, 'delete my topics', 0, -1),
            array(413, 0, 0, 6, 'delete all topics', 0, -1),
            array(414, 0, 0, 6, 'view forum', 1, -1),
            array(415, 0, 0, 6, 'edit my posts', 0, -1),
            array(416, 0, 0, 6, 'edit all posts', 0, -1),
            array(417, 0, 0, 6, 'delete my posts', 0, -1),
            array(418, 0, 0, 6, 'delete all posts', 0, -1),
            array(419, 0, 0, 5, 'view user profiles', 1, -1),
            array(420, 0, 0, 5, 'use search', 1, -1),
            array(421, 0, 0, 5, 'view all topics', 1, -1),
            array(422, 0, 0, 5, 'view my topics', 0, -1),
            array(423, 0, 0, 5, 'create new topic', 0, -1),
            array(424, 0, 0, 5, 'reply to all topics', 0, -1),
            array(425, 0, 0, 5, 'edit my topics', 0, -1),
            array(426, 0, 0, 5, 'edit all topics', 0, -1),
            array(427, 0, 0, 5, 'delete my topics', 0, -1),
            array(428, 0, 0, 5, 'delete all topics', 0, -1),
            array(429, 0, 0, 5, 'view forum', 1, -1),
            array(430, 0, 0, 5, 'edit my posts', 0, -1),
            array(431, 0, 0, 5, 'edit all posts', 0, -1),
            array(432, 0, 0, 5, 'delete my posts', 0, -1),
            array(433, 0, 0, 5, 'delete all posts', 0, -1),
            array(434, 3, 0, 6, 'view all topics', 0, 1),
            array(435, 3, 0, 6, 'view my topics', 0, 1),
            array(436, 3, 0, 6, 'create new topic', 0, 1),
            array(437, 3, 0, 6, 'reply to all topics', 0, 1),
            array(438, 3, 0, 6, 'edit my topics', 0, 1),
            array(439, 3, 0, 6, 'edit all topics', 0, 1),
            array(440, 3, 0, 6, 'delete my topics', 0, 1),
            array(441, 3, 0, 6, 'delete all topics', 0, 1),
            array(442, 3, 0, 6, 'edit my posts', 0, 1),
            array(443, 3, 0, 6, 'edit all posts', 0, 1),
            array(444, 3, 0, 6, 'delete my posts', 0, 1),
            array(445, 3, 0, 6, 'delete all posts', 0, 1),
            array(446, 3, 0, 5, 'view all topics', 1, 1),
            array(447, 3, 0, 5, 'view my topics', 0, 1),
            array(448, 3, 0, 5, 'create new topic', 0, 1),
            array(449, 3, 0, 5, 'reply to all topics', 0, 1),
            array(450, 3, 0, 5, 'edit my topics', 0, 1),
            array(451, 3, 0, 5, 'edit all topics', 0, 1),
            array(452, 3, 0, 5, 'delete my topics', 0, 1),
            array(453, 3, 0, 5, 'delete all topics', 0, 1),
            array(454, 3, 0, 5, 'edit my posts', 0, 1),
            array(455, 3, 0, 5, 'edit all posts', 0, 1),
            array(456, 3, 0, 5, 'delete my posts', 0, 1),
            array(457, 3, 0, 5, 'delete all posts', 0, 1),
            array(458, 3, 0, 4, 'view all topics', 1, 1),
            array(459, 3, 0, 4, 'view my topics', 0, 1),
            array(460, 3, 0, 4, 'create new topic', 1, 1),
            array(461, 3, 0, 4, 'reply to all topics', 1, 1),
            array(462, 3, 0, 4, 'edit my topics', 1, 1),
            array(463, 3, 0, 4, 'edit all topics', 1, 1),
            array(464, 3, 0, 4, 'delete my topics', 1, 1),
            array(465, 3, 0, 4, 'delete all topics', 1, 1),
            array(466, 3, 0, 4, 'edit my posts', 1, 1),
            array(467, 3, 0, 4, 'edit all posts', 1, 1),
            array(468, 3, 0, 4, 'delete my posts', 1, 1),
            array(469, 3, 0, 4, 'delete all posts', 1, 1),
            array(470, 3, 0, 3, 'view all topics', 1, 1),
            array(471, 3, 0, 3, 'view my topics', 0, 1),
            array(472, 3, 0, 3, 'create new topic', 1, 1),
            array(473, 3, 0, 3, 'reply to all topics', 1, 1),
            array(474, 3, 0, 3, 'edit my topics', 1, 1),
            array(475, 3, 0, 3, 'edit all topics', 1, 1),
            array(476, 3, 0, 3, 'delete my topics', 1, 1),
            array(477, 3, 0, 3, 'delete all topics', 1, 1),
            array(478, 3, 0, 3, 'edit my posts', 1, 1),
            array(479, 3, 0, 3, 'edit all posts', 1, 1),
            array(480, 3, 0, 3, 'delete my posts', 1, 1),
            array(481, 3, 0, 3, 'delete all posts', 1, 1),
            array(482, 3, 0, 2, 'view all topics', 1, 1),
            array(483, 3, 0, 2, 'view my topics', 0, 1),
            array(484, 3, 0, 2, 'create new topic', 1, 1),
            array(485, 3, 0, 2, 'reply to all topics', 1, 1),
            array(486, 3, 0, 2, 'edit my topics', 1, 1),
            array(487, 3, 0, 2, 'edit all topics', 0, 1),
            array(488, 3, 0, 2, 'delete my topics', 1, 1),
            array(489, 3, 0, 2, 'delete all topics', 0, 1),
            array(490, 3, 0, 2, 'edit my posts', 1, 1),
            array(491, 3, 0, 2, 'edit all posts', 0, 1),
            array(492, 3, 0, 2, 'delete my posts', 1, 1),
            array(493, 3, 0, 2, 'delete all posts', 0, 1),
            array(494, 3, 0, 1, 'view all topics', 1, 1),
            array(495, 3, 0, 1, 'view my topics', 0, 1),
            array(496, 3, 0, 1, 'create new topic', 0, 1),
            array(497, 3, 0, 1, 'reply to all topics', 0, 1),
            array(498, 3, 0, 1, 'edit my topics', 0, 1),
            array(499, 3, 0, 1, 'edit all topics', 0, 1),
            array(500, 3, 0, 1, 'delete my topics', 0, 1),
            array(501, 3, 0, 1, 'delete all topics', 0, 1),
            array(502, 3, 0, 1, 'edit my posts', 0, 1),
            array(503, 3, 0, 1, 'edit all posts', 0, 1),
            array(504, 3, 0, 1, 'delete my posts', 0, 1),
            array(505, 3, 0, 1, 'delete all posts', 0, 1),
            array(506, 10, 0, 6, 'view all topics', 0, 1),
            array(507, 10, 0, 6, 'view my topics', 0, 1),
            array(508, 10, 0, 6, 'create new topic', 0, 1),
            array(509, 10, 0, 6, 'reply to all topics', 0, 1),
            array(510, 10, 0, 6, 'edit my topics', 0, 1),
            array(511, 10, 0, 6, 'edit all topics', 0, 1),
            array(512, 10, 0, 6, 'delete my topics', 0, 1),
            array(513, 10, 0, 6, 'delete all topics', 0, 1),
            array(514, 10, 0, 6, 'edit my posts', 0, 1),
            array(515, 10, 0, 6, 'edit all posts', 0, 1),
            array(516, 10, 0, 6, 'delete my posts', 0, 1),
            array(517, 10, 0, 6, 'delete all posts', 0, 1),
            array(518, 10, 0, 5, 'view all topics', 1, 1),
            array(519, 10, 0, 5, 'view my topics', 0, 1),
            array(520, 10, 0, 5, 'create new topic', 0, 1),
            array(521, 10, 0, 5, 'reply to all topics', 0, 1),
            array(522, 10, 0, 5, 'edit my topics', 0, 1),
            array(523, 10, 0, 5, 'edit all topics', 0, 1),
            array(524, 10, 0, 5, 'delete my topics', 0, 1),
            array(525, 10, 0, 5, 'delete all topics', 0, 1),
            array(526, 10, 0, 5, 'edit my posts', 0, 1),
            array(527, 10, 0, 5, 'edit all posts', 0, 1),
            array(528, 10, 0, 5, 'delete my posts', 0, 1),
            array(529, 10, 0, 5, 'delete all posts', 0, 1),
            array(530, 10, 0, 4, 'view all topics', 1, 1),
            array(531, 10, 0, 4, 'view my topics', 0, 1),
            array(532, 10, 0, 4, 'create new topic', 1, 1),
            array(533, 10, 0, 4, 'reply to all topics', 1, 1),
            array(534, 10, 0, 4, 'edit my topics', 1, 1),
            array(535, 10, 0, 4, 'edit all topics', 1, 1),
            array(536, 10, 0, 4, 'delete my topics', 1, 1),
            array(537, 10, 0, 4, 'delete all topics', 1, 1),
            array(538, 10, 0, 4, 'edit my posts', 1, 1),
            array(539, 10, 0, 4, 'edit all posts', 1, 1),
            array(540, 10, 0, 4, 'delete my posts', 1, 1),
            array(541, 10, 0, 4, 'delete all posts', 1, 1),
            array(542, 10, 0, 3, 'view all topics', 1, 1),
            array(543, 10, 0, 3, 'view my topics', 0, 1),
            array(544, 10, 0, 3, 'create new topic', 1, 1),
            array(545, 10, 0, 3, 'reply to all topics', 1, 1),
            array(546, 10, 0, 3, 'edit my topics', 1, 1),
            array(547, 10, 0, 3, 'edit all topics', 1, 1),
            array(548, 10, 0, 3, 'delete my topics', 1, 1),
            array(549, 10, 0, 3, 'delete all topics', 1, 1),
            array(550, 10, 0, 3, 'edit my posts', 1, 1),
            array(551, 10, 0, 3, 'edit all posts', 1, 1),
            array(552, 10, 0, 3, 'delete my posts', 1, 1),
            array(553, 10, 0, 3, 'delete all posts', 1, 1),
            array(554, 10, 0, 2, 'view all topics', 1, 1),
            array(555, 10, 0, 2, 'view my topics', 0, 1),
            array(556, 10, 0, 2, 'create new topic', 1, 1),
            array(557, 10, 0, 2, 'reply to all topics', 1, 1),
            array(558, 10, 0, 2, 'edit my topics', 1, 1),
            array(559, 10, 0, 2, 'edit all topics', 0, 1),
            array(560, 10, 0, 2, 'delete my topics', 1, 1),
            array(561, 10, 0, 2, 'delete all topics', 0, 1),
            array(562, 10, 0, 2, 'edit my posts', 1, 1),
            array(563, 10, 0, 2, 'edit all posts', 0, 1),
            array(564, 10, 0, 2, 'delete my posts', 1, 1),
            array(565, 10, 0, 2, 'delete all posts', 0, 1),
            array(566, 10, 0, 1, 'view all topics', 1, 1),
            array(567, 10, 0, 1, 'view my topics', 0, 1),
            array(568, 10, 0, 1, 'create new topic', 0, 1),
            array(569, 10, 0, 1, 'reply to all topics', 0, 1),
            array(570, 10, 0, 1, 'edit my topics', 0, 1),
            array(571, 10, 0, 1, 'edit all topics', 0, 1),
            array(572, 10, 0, 1, 'delete my topics', 0, 1),
            array(573, 10, 0, 1, 'delete all topics', 0, 1),
            array(574, 10, 0, 1, 'edit my posts', 0, 1),
            array(575, 10, 0, 1, 'edit all posts', 0, 1),
            array(576, 10, 0, 1, 'delete my posts', 0, 1),
            array(577, 10, 0, 1, 'delete all posts', 0, 1),
            array(578, 11, 0, 6, 'view all topics', 0, 1),
            array(579, 11, 0, 6, 'view my topics', 0, 1),
            array(580, 11, 0, 6, 'create new topic', 0, 1),
            array(581, 11, 0, 6, 'reply to all topics', 0, 1),
            array(582, 11, 0, 6, 'edit my topics', 0, 1),
            array(583, 11, 0, 6, 'edit all topics', 0, 1),
            array(584, 11, 0, 6, 'delete my topics', 0, 1),
            array(585, 11, 0, 6, 'delete all topics', 0, 1),
            array(586, 11, 0, 6, 'edit my posts', 0, 1),
            array(587, 11, 0, 6, 'edit all posts', 0, 1),
            array(588, 11, 0, 6, 'delete my posts', 0, 1),
            array(589, 11, 0, 6, 'delete all posts', 0, 1),
            array(590, 11, 0, 5, 'view all topics', 1, 1),
            array(591, 11, 0, 5, 'view my topics', 0, 1),
            array(592, 11, 0, 5, 'create new topic', 0, 1),
            array(593, 11, 0, 5, 'reply to all topics', 0, 1),
            array(594, 11, 0, 5, 'edit my topics', 0, 1),
            array(595, 11, 0, 5, 'edit all topics', 0, 1),
            array(596, 11, 0, 5, 'delete my topics', 0, 1),
            array(597, 11, 0, 5, 'delete all topics', 0, 1),
            array(598, 11, 0, 5, 'edit my posts', 0, 1),
            array(599, 11, 0, 5, 'edit all posts', 0, 1),
            array(600, 11, 0, 5, 'delete my posts', 0, 1),
            array(601, 11, 0, 5, 'delete all posts', 0, 1),
            array(602, 11, 0, 4, 'view all topics', 1, 1),
            array(603, 11, 0, 4, 'view my topics', 0, 1),
            array(604, 11, 0, 4, 'create new topic', 1, 1),
            array(605, 11, 0, 4, 'reply to all topics', 1, 1),
            array(606, 11, 0, 4, 'edit my topics', 1, 1),
            array(607, 11, 0, 4, 'edit all topics', 1, 1),
            array(608, 11, 0, 4, 'delete my topics', 1, 1),
            array(609, 11, 0, 4, 'delete all topics', 1, 1),
            array(610, 11, 0, 4, 'edit my posts', 1, 1),
            array(611, 11, 0, 4, 'edit all posts', 1, 1),
            array(612, 11, 0, 4, 'delete my posts', 1, 1),
            array(613, 11, 0, 4, 'delete all posts', 1, 1),
            array(614, 11, 0, 3, 'view all topics', 1, 1),
            array(615, 11, 0, 3, 'view my topics', 0, 1),
            array(616, 11, 0, 3, 'create new topic', 1, 1),
            array(617, 11, 0, 3, 'reply to all topics', 1, 1),
            array(618, 11, 0, 3, 'edit my topics', 1, 1),
            array(619, 11, 0, 3, 'edit all topics', 1, 1),
            array(620, 11, 0, 3, 'delete my topics', 1, 1),
            array(621, 11, 0, 3, 'delete all topics', 1, 1),
            array(622, 11, 0, 3, 'edit my posts', 1, 1),
            array(623, 11, 0, 3, 'edit all posts', 1, 1),
            array(624, 11, 0, 3, 'delete my posts', 1, 1),
            array(625, 11, 0, 3, 'delete all posts', 1, 1),
            array(626, 11, 0, 2, 'view all topics', 1, 1),
            array(627, 11, 0, 2, 'view my topics', 0, 1),
            array(628, 11, 0, 2, 'create new topic', 1, 1),
            array(629, 11, 0, 2, 'reply to all topics', 1, 1),
            array(630, 11, 0, 2, 'edit my topics', 1, 1),
            array(631, 11, 0, 2, 'edit all topics', 0, 1),
            array(632, 11, 0, 2, 'delete my topics', 1, 1),
            array(633, 11, 0, 2, 'delete all topics', 0, 1),
            array(634, 11, 0, 2, 'edit my posts', 1, 1),
            array(635, 11, 0, 2, 'edit all posts', 0, 1),
            array(636, 11, 0, 2, 'delete my posts', 1, 1),
            array(637, 11, 0, 2, 'delete all posts', 0, 1),
            array(638, 11, 0, 1, 'view all topics', 1, 1),
            array(639, 11, 0, 1, 'view my topics', 0, 1),
            array(640, 11, 0, 1, 'create new topic', 0, 1),
            array(641, 11, 0, 1, 'reply to all topics', 0, 1),
            array(642, 11, 0, 1, 'edit my topics', 0, 1),
            array(643, 11, 0, 1, 'edit all topics', 0, 1),
            array(644, 11, 0, 1, 'delete my topics', 0, 1),
            array(645, 11, 0, 1, 'delete all topics', 0, 1),
            array(646, 11, 0, 1, 'edit my posts', 0, 1),
            array(647, 11, 0, 1, 'edit all posts', 0, 1),
            array(648, 11, 0, 1, 'delete my posts', 0, 1),
            array(649, 11, 0, 1, 'delete all posts', 0, 1),
            array(650, 12, 0, 6, 'view all topics', 0, 1),
            array(651, 12, 0, 6, 'view my topics', 0, 1),
            array(652, 12, 0, 6, 'create new topic', 0, 1),
            array(653, 12, 0, 6, 'reply to all topics', 0, 1),
            array(654, 12, 0, 6, 'edit my topics', 0, 1),
            array(655, 12, 0, 6, 'edit all topics', 0, 1),
            array(656, 12, 0, 6, 'delete my topics', 0, 1),
            array(657, 12, 0, 6, 'delete all topics', 0, 1),
            array(658, 12, 0, 6, 'edit my posts', 0, 1),
            array(659, 12, 0, 6, 'edit all posts', 0, 1),
            array(660, 12, 0, 6, 'delete my posts', 0, 1),
            array(661, 12, 0, 6, 'delete all posts', 0, 1),
            array(662, 12, 0, 5, 'view all topics', 1, 1),
            array(663, 12, 0, 5, 'view my topics', 0, 1),
            array(664, 12, 0, 5, 'create new topic', 0, 1),
            array(665, 12, 0, 5, 'reply to all topics', 0, 1),
            array(666, 12, 0, 5, 'edit my topics', 0, 1),
            array(667, 12, 0, 5, 'edit all topics', 0, 1),
            array(668, 12, 0, 5, 'delete my topics', 0, 1),
            array(669, 12, 0, 5, 'delete all topics', 0, 1),
            array(670, 12, 0, 5, 'edit my posts', 0, 1),
            array(671, 12, 0, 5, 'edit all posts', 0, 1),
            array(672, 12, 0, 5, 'delete my posts', 0, 1),
            array(673, 12, 0, 5, 'delete all posts', 0, 1),
            array(674, 12, 0, 4, 'view all topics', 1, 1),
            array(675, 12, 0, 4, 'view my topics', 0, 1),
            array(676, 12, 0, 4, 'create new topic', 1, 1),
            array(677, 12, 0, 4, 'reply to all topics', 1, 1),
            array(678, 12, 0, 4, 'edit my topics', 1, 1),
            array(679, 12, 0, 4, 'edit all topics', 1, 1),
            array(680, 12, 0, 4, 'delete my topics', 1, 1),
            array(681, 12, 0, 4, 'delete all topics', 1, 1),
            array(682, 12, 0, 4, 'edit my posts', 1, 1),
            array(683, 12, 0, 4, 'edit all posts', 1, 1),
            array(684, 12, 0, 4, 'delete my posts', 1, 1),
            array(685, 12, 0, 4, 'delete all posts', 1, 1),
            array(686, 12, 0, 3, 'view all topics', 1, 1),
            array(687, 12, 0, 3, 'view my topics', 0, 1),
            array(688, 12, 0, 3, 'create new topic', 1, 1),
            array(689, 12, 0, 3, 'reply to all topics', 1, 1),
            array(690, 12, 0, 3, 'edit my topics', 1, 1),
            array(691, 12, 0, 3, 'edit all topics', 1, 1),
            array(692, 12, 0, 3, 'delete my topics', 1, 1),
            array(693, 12, 0, 3, 'delete all topics', 1, 1),
            array(694, 12, 0, 3, 'edit my posts', 1, 1),
            array(695, 12, 0, 3, 'edit all posts', 1, 1),
            array(696, 12, 0, 3, 'delete my posts', 1, 1),
            array(697, 12, 0, 3, 'delete all posts', 1, 1),
            array(698, 12, 0, 2, 'view all topics', 1, 1),
            array(699, 12, 0, 2, 'view my topics', 0, 1),
            array(700, 12, 0, 2, 'create new topic', 1, 1),
            array(701, 12, 0, 2, 'reply to all topics', 1, 1),
            array(702, 12, 0, 2, 'edit my topics', 1, 1),
            array(703, 12, 0, 2, 'edit all topics', 0, 1),
            array(704, 12, 0, 2, 'delete my topics', 1, 1),
            array(705, 12, 0, 2, 'delete all topics', 0, 1),
            array(706, 12, 0, 2, 'edit my posts', 1, 1),
            array(707, 12, 0, 2, 'edit all posts', 0, 1),
            array(708, 12, 0, 2, 'delete my posts', 1, 1),
            array(709, 12, 0, 2, 'delete all posts', 0, 1),
            array(710, 12, 0, 1, 'view all topics', 1, 1),
            array(711, 12, 0, 1, 'view my topics', 0, 1),
            array(712, 12, 0, 1, 'create new topic', 0, 1),
            array(713, 12, 0, 1, 'reply to all topics', 0, 1),
            array(714, 12, 0, 1, 'edit my topics', 0, 1),
            array(715, 12, 0, 1, 'edit all topics', 0, 1),
            array(716, 12, 0, 1, 'delete my topics', 0, 1),
            array(717, 12, 0, 1, 'delete all topics', 0, 1),
            array(718, 12, 0, 1, 'edit my posts', 0, 1),
            array(719, 12, 0, 1, 'edit all posts', 0, 1),
            array(720, 12, 0, 1, 'delete my posts', 0, 1),
            array(721, 12, 0, 1, 'delete all posts', 0, 1),
            array(722, 13, 0, 6, 'view all topics', 0, 1),
            array(723, 13, 0, 6, 'view my topics', 0, 1),
            array(724, 13, 0, 6, 'create new topic', 0, 1),
            array(725, 13, 0, 6, 'reply to all topics', 0, 1),
            array(726, 13, 0, 6, 'edit my topics', 0, 1),
            array(727, 13, 0, 6, 'edit all topics', 0, 1),
            array(728, 13, 0, 6, 'delete my topics', 0, 1),
            array(729, 13, 0, 6, 'delete all topics', 0, 1),
            array(730, 13, 0, 6, 'edit my posts', 0, 1),
            array(731, 13, 0, 6, 'edit all posts', 0, 1),
            array(732, 13, 0, 6, 'delete my posts', 0, 1),
            array(733, 13, 0, 6, 'delete all posts', 0, 1),
            array(734, 13, 0, 5, 'view all topics', 1, 1),
            array(735, 13, 0, 5, 'view my topics', 0, 1),
            array(736, 13, 0, 5, 'create new topic', 0, 1),
            array(737, 13, 0, 5, 'reply to all topics', 0, 1),
            array(738, 13, 0, 5, 'edit my topics', 0, 1),
            array(739, 13, 0, 5, 'edit all topics', 0, 1),
            array(740, 13, 0, 5, 'delete my topics', 0, 1),
            array(741, 13, 0, 5, 'delete all topics', 0, 1),
            array(742, 13, 0, 5, 'edit my posts', 0, 1),
            array(743, 13, 0, 5, 'edit all posts', 0, 1),
            array(744, 13, 0, 5, 'delete my posts', 0, 1),
            array(745, 13, 0, 5, 'delete all posts', 0, 1),
            array(746, 13, 0, 4, 'view all topics', 1, 1),
            array(747, 13, 0, 4, 'view my topics', 0, 1),
            array(748, 13, 0, 4, 'create new topic', 1, 1),
            array(749, 13, 0, 4, 'reply to all topics', 1, 1),
            array(750, 13, 0, 4, 'edit my topics', 1, 1),
            array(751, 13, 0, 4, 'edit all topics', 1, 1),
            array(752, 13, 0, 4, 'delete my topics', 1, 1),
            array(753, 13, 0, 4, 'delete all topics', 1, 1),
            array(754, 13, 0, 4, 'edit my posts', 1, 1),
            array(755, 13, 0, 4, 'edit all posts', 1, 1),
            array(756, 13, 0, 4, 'delete my posts', 1, 1),
            array(757, 13, 0, 4, 'delete all posts', 1, 1),
            array(758, 13, 0, 3, 'view all topics', 1, 1),
            array(759, 13, 0, 3, 'view my topics', 0, 1),
            array(760, 13, 0, 3, 'create new topic', 1, 1),
            array(761, 13, 0, 3, 'reply to all topics', 1, 1),
            array(762, 13, 0, 3, 'edit my topics', 1, 1),
            array(763, 13, 0, 3, 'edit all topics', 1, 1),
            array(764, 13, 0, 3, 'delete my topics', 1, 1),
            array(765, 13, 0, 3, 'delete all topics', 1, 1),
            array(766, 13, 0, 3, 'edit my posts', 1, 1),
            array(767, 13, 0, 3, 'edit all posts', 1, 1),
            array(768, 13, 0, 3, 'delete my posts', 1, 1),
            array(769, 13, 0, 3, 'delete all posts', 1, 1),
            array(770, 13, 0, 2, 'view all topics', 1, 1),
            array(771, 13, 0, 2, 'view my topics', 0, 1),
            array(772, 13, 0, 2, 'create new topic', 1, 1),
            array(773, 13, 0, 2, 'reply to all topics', 1, 1),
            array(774, 13, 0, 2, 'edit my topics', 1, 1),
            array(775, 13, 0, 2, 'edit all topics', 0, 1),
            array(776, 13, 0, 2, 'delete my topics', 1, 1),
            array(777, 13, 0, 2, 'delete all topics', 0, 1),
            array(778, 13, 0, 2, 'edit my posts', 1, 1),
            array(779, 13, 0, 2, 'edit all posts', 0, 1),
            array(780, 13, 0, 2, 'delete my posts', 1, 1),
            array(781, 13, 0, 2, 'delete all posts', 0, 1),
            array(782, 13, 0, 1, 'view all topics', 1, 1),
            array(783, 13, 0, 1, 'view my topics', 0, 1),
            array(784, 13, 0, 1, 'create new topic', 0, 1),
            array(785, 13, 0, 1, 'reply to all topics', 0, 1),
            array(786, 13, 0, 1, 'edit my topics', 0, 1),
            array(787, 13, 0, 1, 'edit all topics', 0, 1),
            array(788, 13, 0, 1, 'delete my topics', 0, 1),
            array(789, 13, 0, 1, 'delete all topics', 0, 1),
            array(790, 13, 0, 1, 'edit my posts', 0, 1),
            array(791, 13, 0, 1, 'edit all posts', 0, 1),
            array(792, 13, 0, 1, 'delete my posts', 0, 1),
            array(793, 13, 0, 1, 'delete all posts', 0, 1),
            array(794, 14, 0, 6, 'view all topics', 0, 1),
            array(795, 14, 0, 6, 'view my topics', 0, 1),
            array(796, 14, 0, 6, 'create new topic', 0, 1),
            array(797, 14, 0, 6, 'reply to all topics', 0, 1),
            array(798, 14, 0, 6, 'edit my topics', 0, 1),
            array(799, 14, 0, 6, 'edit all topics', 0, 1),
            array(800, 14, 0, 6, 'delete my topics', 0, 1),
            array(801, 14, 0, 6, 'delete all topics', 0, 1),
            array(802, 14, 0, 6, 'edit my posts', 0, 1),
            array(803, 14, 0, 6, 'edit all posts', 0, 1),
            array(804, 14, 0, 6, 'delete my posts', 0, 1),
            array(805, 14, 0, 6, 'delete all posts', 0, 1),
            array(806, 14, 0, 5, 'view all topics', 1, 1),
            array(807, 14, 0, 5, 'view my topics', 0, 1),
            array(808, 14, 0, 5, 'create new topic', 0, 1),
            array(809, 14, 0, 5, 'reply to all topics', 0, 1),
            array(810, 14, 0, 5, 'edit my topics', 0, 1),
            array(811, 14, 0, 5, 'edit all topics', 0, 1),
            array(812, 14, 0, 5, 'delete my topics', 0, 1),
            array(813, 14, 0, 5, 'delete all topics', 0, 1),
            array(814, 14, 0, 5, 'edit my posts', 0, 1),
            array(815, 14, 0, 5, 'edit all posts', 0, 1),
            array(816, 14, 0, 5, 'delete my posts', 0, 1),
            array(817, 14, 0, 5, 'delete all posts', 0, 1),
            array(818, 14, 0, 4, 'view all topics', 1, 1),
            array(819, 14, 0, 4, 'view my topics', 0, 1),
            array(820, 14, 0, 4, 'create new topic', 1, 1),
            array(821, 14, 0, 4, 'reply to all topics', 1, 1),
            array(822, 14, 0, 4, 'edit my topics', 1, 1),
            array(823, 14, 0, 4, 'edit all topics', 1, 1),
            array(824, 14, 0, 4, 'delete my topics', 1, 1),
            array(825, 14, 0, 4, 'delete all topics', 1, 1),
            array(826, 14, 0, 4, 'edit my posts', 1, 1),
            array(827, 14, 0, 4, 'edit all posts', 1, 1),
            array(828, 14, 0, 4, 'delete my posts', 1, 1),
            array(829, 14, 0, 4, 'delete all posts', 1, 1),
            array(830, 14, 0, 3, 'view all topics', 1, 1),
            array(831, 14, 0, 3, 'view my topics', 0, 1),
            array(832, 14, 0, 3, 'create new topic', 1, 1),
            array(833, 14, 0, 3, 'reply to all topics', 1, 1),
            array(834, 14, 0, 3, 'edit my topics', 1, 1),
            array(835, 14, 0, 3, 'edit all topics', 1, 1),
            array(836, 14, 0, 3, 'delete my topics', 1, 1),
            array(837, 14, 0, 3, 'delete all topics', 1, 1),
            array(838, 14, 0, 3, 'edit my posts', 1, 1),
            array(839, 14, 0, 3, 'edit all posts', 1, 1),
            array(840, 14, 0, 3, 'delete my posts', 1, 1),
            array(841, 14, 0, 3, 'delete all posts', 1, 1),
            array(842, 14, 0, 2, 'view all topics', 1, 1),
            array(843, 14, 0, 2, 'view my topics', 0, 1),
            array(844, 14, 0, 2, 'create new topic', 1, 1),
            array(845, 14, 0, 2, 'reply to all topics', 1, 1),
            array(846, 14, 0, 2, 'edit my topics', 1, 1),
            array(847, 14, 0, 2, 'edit all topics', 0, 1),
            array(848, 14, 0, 2, 'delete my topics', 1, 1),
            array(849, 14, 0, 2, 'delete all topics', 0, 1),
            array(850, 14, 0, 2, 'edit my posts', 1, 1),
            array(851, 14, 0, 2, 'edit all posts', 0, 1),
            array(852, 14, 0, 2, 'delete my posts', 1, 1),
            array(853, 14, 0, 2, 'delete all posts', 0, 1),
            array(854, 14, 0, 1, 'view all topics', 1, 1),
            array(855, 14, 0, 1, 'view my topics', 0, 1),
            array(856, 14, 0, 1, 'create new topic', 0, 1),
            array(857, 14, 0, 1, 'reply to all topics', 0, 1),
            array(858, 14, 0, 1, 'edit my topics', 0, 1),
            array(859, 14, 0, 1, 'edit all topics', 0, 1),
            array(860, 14, 0, 1, 'delete my topics', 0, 1),
            array(861, 14, 0, 1, 'delete all topics', 0, 1),
            array(862, 14, 0, 1, 'edit my posts', 0, 1),
            array(863, 14, 0, 1, 'edit all posts', 0, 1),
            array(864, 14, 0, 1, 'delete my posts', 0, 1),
            array(865, 14, 0, 1, 'delete all posts', 0, 1)
        );

        $permissions = $this->build_arr($columns, $values);

        $this->chunkInsert('codo_permissions', $permissions);

        //------------- codo_permission_list -------------------//

        $columns = array('id', 'permission', 'type');
        $values = array(
            array(1, 'view category', 'forum'),
            array(2, 'view user profiles', 'general'),
            array(3, 'use search', 'general'),
            array(4, 'view all topics', 'forum'),
            array(5, 'view my topics', 'forum'),
            array(6, 'create new topic', 'forum'),
            array(7, 'reply to all topics', 'forum'),
            array(8, 'edit my topics', 'forum'),
            array(9, 'edit all topics', 'forum'),
            array(10, 'delete my topics', 'forum'),
            array(11, 'delete all topics', 'forum'),
            array(12, 'view forum', 'general'),
            array(13, 'move topics', 'forum'),
            array(14, 'moderate topics', 'forum'),
            array(15, 'moderate posts', 'forum')
        );

        $permission_list = $this->build_arr($columns, $values);

        DB::table('codo_permission_list')->insert(
            $permission_list
        );

        //------------- codo_plugins -------------------//


        $columns = array('plg_name', 'plg_type', 'plg_status', 'plg_weight', 'plg_schema_ver');
        $values = array(
            array('post_notify', 'plugin', 1, 0, 1),
            array('sso', 'plugin', 0, 0, 1),
            array('uni_login', 'plugin', 0, 0, 1)
        );

        $plugins = $this->build_arr($columns, $values);

        DB::table('codo_plugins')->insert(
            $plugins
        );

        $columns = array('post_id', 'topic_id', 'cat_id', 'uid', 'imessage', 'omessage', 'post_created', 'post_modified', 'post_status');
        $values = array(
            array(1, 1, 3, 1, "Hi,  \n  \nThis is an example post in your codoforum installation.   \nYou can create/modify/delete all forum categories from the forum backend.  \n  \nPlease edit the forum title and description from the backend.   \n  \nThe only user available to login in the front-end is admin with the password that you set during the installation.\n \nYou may delete this post . \n  \nRegards,   \nCodologic Team", "<p>Hi,  </p>\n<p>This is an example post in your codoforum installation.<br>You can create/modify/delete all forum categories from the forum backend.  </p>\n<p>Please edit the forum title and description from the backend.   </p>\n<p>The only user available to login in the front-end is admin with the password that you set during the installation.</p>\n<p>You may delete this post . </p>\n<p>Regards,<br>Codologic Team</p>", 1401549322, NULL, 1)
        );

        $posts = $this->build_arr($columns, $values);

        DB::table('codo_posts')->insert(
            $posts
        );

        $columns = array('rid', 'rname');
        $values = array(
            array(1, 'guest'),
            array(5, 'unverified user'),
            array(2, 'user'),
            array(3, 'moderator'),
            array(4, 'administrator'),
            array(6, 'banned')
        );

        $roles = $this->build_arr($columns, $values);

        DB::table('codo_roles')->insert(
            $roles
        );


        $columns = array('name');
        $values = array(
            array('Spam'),
            array('Offtopic'),
            array('Other')
        );

        $roles = $this->build_arr($columns, $values);

        DB::table('codo_report_types')->insert(
            $roles
        );


        $columns = array('id', 'symbol', 'image_name');
        $values = array(
            array(1, ':S', 'worried.gif'),
            array(2, '(wasntme)', 'itwasntme.gif'),
            array(3, 'x(', 'angry.gif'),
            array(4, '(doh)', 'doh.gif'),
            array(5, '|-()', 'yawn.gif'),
            array(6, ']:)', 'evilgrin.gif'),
            array(7, '|(', 'dull.gif'),
            array(8, '|-)', 'sleepy.gif'),
            array(9, '(blush)', 'blush.gif'),
            array(10, ':P', 'tongueout.gif'),
            array(11, '(:|', 'sweat.gif'),
            array(12, ';(', 'crying.gif'),
            array(13, ':)', 'smile.gif'),
            array(14, ':(', 'sad.gif'),
            array(15, ':D', 'bigsmile.gif'),
            array(16, '8)', 'cool.gif'),
            array(17, ';)', 'wink.gif'),
            array(18, '(mm)', 'mmm.gif'),
            array(19, ':x', 'lipssealed.gif')
        );

        $smileys = $this->build_arr($columns, $values);

        DB::table('codo_smileys')->insert(
            $smileys
        );


        DB::insert("INSERT INTO codo_topics (topic_id, title, cat_id, post_id, uid, last_post_id, last_post_uid, last_post_name, topic_created, topic_updated, last_post_time, no_posts, no_views, topic_status) VALUES
(1, 'Welcome to Codoforum', 3, 1, 1, 0, NULL, NULL, 1401549322, 0, 1401549322, 1, 0, 1);
");

        $columns = array('uid', 'preference', 'value');
        $values = array(
            array(0, 'notification_frequency', 'immediate'),
            array(0, 'real_time_notifications', 'yes'),
            array(0, 'desktop_notifications', 'yes'),
            array(0, 'send_emails_when_online', 'no'),
            array(0, 'notification_type_on_create_topic', '4'),
            array(0, 'notification_type_on_reply_topic', '3')
        );

        $preferences = $this->build_arr($columns, $values);

        DB::table('codo_user_preferences')->insert(
            $preferences
        );

        DB::insert("INSERT INTO codo_crons (id, cron_name, cron_type, cron_interval, cron_started, cron_last_run, cron_status) VALUES
(1, 'core', 'recurrence', 86400, 0, 0, 0);
");


        $str = <<<EOD
                INSERT INTO codo_blocks (id, module, theme, status, weight, region, content, visibility, pages, title, cache) VALUES
(1, 'html', '2k18', 0, 0, 'block_main_menu_start', "<li class='dropdown codo_dropdown'>\r\n    <span class='codo_menu_dropdown dropdown-toggle' data-toggle='dropdown'>Link</span>\r\n    <ul class='dropdown-menu' role='menu'>\r\n        <li class='dropdown-item'><a href='#'>Action</a></li>\r\n        <li class='dropdown-item'><a href='#'>Another action</a></li>\r\n        <li class='dropdown-item'><a href='#'>Something else here</a></li>\r\n        <li class='dropdown-item'><a href='#'>Separated link</a></li>\r\n        <li class='dropdown-item'><a href='#'>One more separated link</a></li>  </ul>\r\n</li>", 0, '', 'Main Menu', 1);
EOD;

        DB::insert($str);

        $str = <<<EOD
        INSERT INTO codo_blocks (id, module, theme, status, weight, region, content, visibility, pages, title, cache) VALUES
(2, 'html', '2k18', 0, 0, 'block_footer_right', '<small>\r\n   \r\n<a href="https://facebook.com/codologic"><i class="icon-facebook"> </i></a> \r\n <a href="https://twitter.com/codologic"><i class="icon-twitter"> </i></a>\r\n <a href="https://plus.google.com/+codologic"><i class="icon-google-plus-square"> </i></a>\r\n\r\n        <br>\r\n        <a href="index.php?u=page/6">Terms of Service</a> |\r\n        <a href="index.php?u=page/7">Privacy</a> |\r\n        <a href="#">About us</a> \r\n</small>', 0, '', 'Footer Right', 1);
EOD;

        DB::insert($str);

        $str = <<<EOD
INSERT INTO codo_pages (id, title, url, content) VALUES
(6, 'Terms Of Service', 'terms-of-service', '<h1>Terms and Conditions</h1>\r\n<hr>\r\n<p>By using and accessing this website, <a href="https://codoforum.com">codoforum.com</a> a part of <a href="https://codologic.com">Codologic</a> (collectively referred to as the "Site" or "Codoforum" in these Terms of Service), you ("you", "user" or, "end user") agree to these Terms of Service (collectively, the "Terms of Service" or "Agreement").</p>\r\n<p>IF YOU DO NOT AGREE TO THE TERMS OF THIS AGREEMENT, IMMEDIATELY STOP ACCESSING THIS SITE.</p>\r\n<p>You agree not to modify, copy, distribute, transmit, display, perform, reproduce, publish, license, transfer, create derivate work from, sell or re-sell any content or information obtained from or through the Site.\r\n<br><br><strong>Third-party Sites.</strong></p>\r\n<p>The Site may contain links to other websites maintained by third-parties. These links are provided solely as a convenience and does not imply endorsement of, or association with, the party by Codologic.\r\n<br><br><strong>Modifications to this Agreement.</strong></p>\r\n<p>Codologic reserves the right to change or modify any of the terms and conditions contained in this Agreement at any time. You acknowledge and agree that it is your responsibility to review the Site and these Terms of Service from time to time. Your continued use of the Site after such modifications to this Agreement will constitute acknowledgment of the modified Terms of Service and agreement to abide and be bound by the modified Terms of Service.\r\n<br><br><strong>Termination of Use.</strong></p>\r\n<p>Codologic shall have the right to immediately terminate or suspend, in its discretion, your access to all or part of the Site with or without notice for any reason.\r\n<br><br><strong>Limitation of Liability.</strong></p>\r\n<p>In no event shall Codologic or its affiliates be liable for any indirect, incidental, special, punitive damages or consequential damages of any kind, or any damages whatsoever arising out of or related to your use of the Site, the content and other information obtained therein.</p>\r\n<p>Certain jurisdictions prohibit the exclusion or limitation of liability for consequential or incidental damages, thus the above limitations may not apply to you.\r\n<br><br><strong>Indemnity</strong></p>\r\n<p>You agree to indemnify and hold us, and our subsidiaries, affiliates, directors, officers, agents, vendors or other partners and employees harmless from any claim or demand, including attorneys’ fees, made by any third party due to or arising out of any material or information posted, provided, transmitted or otherwise made available by you on this Site or through Codologic.com’s services, or by your violation of these Terms, or by your violation of the rights of another.\r\n<br><br><strong>Disclaimers and Limitation of Liability </strong></p>\r\n<p>You understand and agree that this Site is provided "AS-IS" and that we assume no responsibility for your ability to (or any costs or fees associated with your ability to) obtain access to Codologic.com. Nor do we assume any liability for the failure to store or maintain any user communications or personal settings.</p>\r\n<p>NO ADVICE OR INFORMATION, WHETHER ORAL OR WRITTEN, OBTAINED BY YOU FROM CODOLOGIC.COM OR THROUGH OR FROM ITS SERVICES SHALL CREATE ANY WARRANTY NOT EXPRESSLY STATED IN THESE TERMS AND CONDITIONS. IN NO EVENT SHALL CODOLOGIC.COM OR ITS OWNER BE LIABLE TO YOU OR ANY THIRD PARTY FOR ANY INDIRECT, CONSEQUENTIAL, EXEMPLARY, INCIDENTAL, SPECIAL OR PUNITIVE DAMAGES, INCLUDING LOST PROFIT DAMAGES ARISING FROM YOUR USE OF CODOLOGIC.COM OR ITS SERVICES EVEN IF WE HAVE BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.\r\n<br><br><strong>Jurisdiction</strong></p>\r\n<p>This agreement is governed and construed in accordance with the Laws of Union of India. You hereby irrevocably consent to the exclusive jurisdiction and venue of courts in Mumbai, Maharashtra, India, in all disputes arising out of or relating to the use of Codologic site/services. Use of the Codologic site/services is unauthorized in any jurisdiction that does not give effect to all provisions of these terms and conditions, including without limitation this paragraph. You agree to indemnify and hold Codologic.com, its subsidiaries, affiliates, officers, directors, employees, and representatives harmless from any claim, demand, or damage, including reasonable attorneys'' fees, asserted by any third party due to or arising out of your use of or conduct on the Codologic site/services.</p>\r\n<p>The section titles and other headings in these Terms are for convenience only and have no legal or contractual effect. Our failure to exercise or enforce any right or provision of these Terms will not operate as a waiver of such right or provision. If any provision of these Terms is unlawful, void or unenforceable, that provision is deemed severable and does not affect the validity and enforceability of any remaining provisions.</p>\r\n<p><br><br><strong>Date of Last Update.</strong></p>\r\n<p>This agreement was last updated on May 15, 2014.</p>\r\n'),
(7, 'Privacy Policy', 'privacy-policy', '\r\n        \r\n <h1>Privacy policy</h1>\r\n <hr>\r\n <br>       \r\n        \r\n<p><strong>Privacy policy for Codoforum:</strong></p>\r\n<p>Your use of any information or materials on this website is entirely at your own decision, for which we shall not be liable. </p>\r\n<p>It shall be your own responsibility to ensure that any products, services or information available through this website meet your specific requirements.</p>\r\n<p>This website contains material which is owned by or licensed to us. This material includes, but is not limited to, the design, layout, look, appearance and graphics. Reproduction is prohibited other than in accordance with the copyright notice, which forms part of these terms and conditions.</p>\r\n<p>All trademarks reproduced in this website which are not the property of, or licensed to, the operator are acknowledged on the website.\r\nUnauthorized use of this website may give rise to a claim for damages and/or be a criminal offence.</p>\r\n<p>From time to time this website may also include links to other websites. These links are provided for your convenience to provide further information. They do not signify that we endorse the website(s). </p>\r\n<p>We have no responsibility for the content of the linked website(s).\r\nYour use of this website and any dispute arising out of such use of the website is subject to the laws of India or other regulatory authority.</p>\r\n<br>\r\n<br>\r\n<p><strong>Log Files:</strong></p>\r\n<p>Codoforum makes use of log files (which includes IP addresses, type of browser, internet service providers, referrer, number of clicks etc) to understand user movements and demographics. Such information is not linked to any information that is personally identifiable.</p>\r\n\r\n<br>\r\n<br>\r\n<p><strong>Cookies:</strong></p>\r\n<p>Codoforum uses cookies to store information about visitors preferences, record user-specific information on which pages the user access or visit, customize Web page content based on visitors browser type or other information that the visitor sends via their browser.</p>\r\n<p>We will not sell, disseminate, disclose, trade, transmit, transfer, share, lease or rent any personally identifiable information to any third party not specifically authorized by you to receive your information except as we have disclosed to you in this Privacy Policy. However we may use such information to update you about promotional offers and updates from us.</p>\r\n<br>\r\n<br>\r\n<p><strong>Changes in Our Privacy Policy:</strong></p>\r\n<p>We reserve the right to change this Privacy Policy without providing you with advance notice of our intent to make the changes.</p>\r\n<p>If you believe that any information we are holding on you is incorrect or incomplete, please contact us as soon as possible.</p>\r\n\r\n');
EOD;
        DB::insert($str);

        DB::table(PREFIX . 'codo_badges')->insert(array(
            array(
                "name" => "Admin",
                "description" => "Sysadmin and site admin",
                "location" => "admin.svg"
            ),
            array(
                "name" => "Moderator",
                "description" => "Acts as a neutral participant in discussions and debate",
                "location" => "moderator.svg"
            ),
            array(
                "name" => "Verified",
                "description" => "Verified Identity",
                "location" => "verfied.svg"
            ),
            array(
                "name" => "Top Contributor",
                "description" => "Heart of the forum",
                "location" => "top_contributor.svg"
            ),
            array(
                "name" => "Gamer",
                "description" => "",
                "location" => "gamer.svg"
            ),
            array(
                "name" => "1st Prize",
                "description" => "",
                "location" => "cup1.svg"
            ),
            array(
                "name" => "2nd Prize",
                "description" => "",
                "location" => "cup2.svg"
            ),
            array(
                "name" => "3rd Prize",
                "description" => "",
                "location" => "cup3.svg"
            ),
        ));
    }

}
