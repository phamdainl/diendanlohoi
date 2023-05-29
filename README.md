Changelog
--

 [5.2]
   - Allow user to select theme as part of user preference 
   - Add user activity logs in backend
   - Fix formatting in post content [DONE]
   - Fix media mp4 not playing in Safari browsers [DONE]
   - Fix login redirect success [DONE]
   - Add custom login page for sso [DONE]
   - Fix first link in post content [DONE]
   - Fix Report translation on second page [DONE]
   - Fix bug on tags page [DONE]
   - Fix view history -> profile link [DONE]
   - Fix switch for notification in edit profile [DONE]
   - Fix custom fields styles [DONE]
   - Fix save in reputation->settings, spam->recaptcha [DONE]

 [5.1]
  - Fix unnecessary breaks in editor
  - Fix new theme color
  - Add option to select user timezone from backend
  - Fix SSO bug not logging in
  - Fix FreiChat plugin for latest PHP

 [5.0]
  - Structure changes for splitting code and data
  - Upgrade all libraries [DONE]
  - Inherit theme [DONE]  
  - Cyrillic characters in topics, tags, emails, category posts [DONE]
  - Update preview excerpt [DONE]
  - Mentions not working in new topic [FIXED]
  - First mention disappear in the excerpt [FIXED]
  - Split title based on max chars changed [DONE]
  - Show popular posts first 
  - save button responsive fix
  - Mail notification bug fixes [DONE]
  - Infinite scrolling not working [FIXED]
  - Search feature [DONE]
  - Make create topic button more prominent (Fixed with scroll, bottom buttons, colors, etc)
  - i18n translations shown undefined in infinite scrolling in topic [FIXED]
  - BBcode multilines fix [DONE]
  - Redirect to wrong page when reply after deleting post [FIXED]
  - Editor UX fixes [DONE]
  - Edit post editing wrong post sometimes [FIXED]
  - User can reply to closed topics [FIXED]
  - Add missing translations [DONE] thanks @pupsik
  - Responsive videos for mobile [DONE]
  - Images shown twice in preview [FIXED]
  - Add options in backend to set default values for "hide topic messages" and "infinite scrolling" switch [DONE]
  - Sync sidebar settings with both all topics and category page [DONE]  
  - CSS fixes [DONE]
  - Template bug fixes [DONE]
  - Add setting in backend to allow users to see sticky topics without permission [DONE]
  - Add option to login as user in backend [DONE]
  - Update redirect to home page [DONE]
  - Fix manage topics not working [DONE]
  - Support non-ssl connections in mail [DONE] thanks @NFG
  - Add option to send email to multiple address on new registrations [DONE]
  - Fix ordering of topics for sticky and closed topics [DONE]
  - Fix html of mail templates [DONE]

Updated libraries:

 1. LightnCandy -> Latest 
 2. Illuminate/Database -> Latest 4.2.x branch
 3. JBBCode -> Latest
 4. Moved from leafo/lessphp to wikimedia/less.php -> Latest
 5. Migrated from Limonade to FastRoute
 6. PHPMailer
 7. Smarty

Moved from Ext to composer

 1. LigntnCandy
 2. Hybridauth
 3. JBBCode
 4. Less compiler
 5. PHPMailer
 6. Smarty

Removed/Commented b8 library temporarily
Removed limonade routing framework

 [4.9.3]
 - Bug fix for plugins using global add_js/add_css methods

 [4.9.2]
 - Update desktop/in-browser notifications (api, plugin, style, and bug fixes)
 - Update PHPMailer library
 - Digest bugs fixed (not sending to all users, template errors, incorrect data and notifications)
 - Update HybridAuth OAuth library
 - Show tables in preview/excerpt for posts using table markdown
 - Embed bugs fixed (Non-video links and js errors)
 - Draft bugs fixed (Unable to reply, editor not auto-opening)
 - Theme css loader fixed
 - Update preferences notification fixed
 - Add an option in admin panel to force HTTPS paths

 [4.9.1]
 - Fixed issues with PHP 7.4
 - Notifications tab fix in edit profile
 - Preferences style fix 
 - Smiley fix for forum posts
 - Update  jquery nestable plugin
 - Vintage theme template and css fixes
 - Security fix in admin category
 - Fixed draggable not working for nested categories 
 - Edit avatar fix for firefox
 
 [4.8.9]
 - Security fixes

 [4.8.8]
 - Fix email notifications user data
 - Security fixes

 [4.8.5]
 - Fix forum not working on urls with ports
 - Bell notification bug fixes
 - Email notification fixes
 - Add notifications for reputation up/down 
 - Add email templates in backend
 - Add option in category (admin) to send emails to all users on new topic
 - Fix security vulnerability in polls
 - Add icon upload input in categories (admin)
 
 [4.8.4]
 - Fix bug showing duplicate users when users have multiple roles [DONE]
 - Fix bans not applying for uni login and sso plugins [DONE]
 - Fix mentions style and 404 avatars
 - Fix for custom user fields
 - Security fixes
 - Add manage freichat link
 - Fix instant notifications
 - Updated less compiler to 0.5.0
 
 [4.8.3]
 - Update hybridauth library to 3.0 stable release
 
 [4.8.2]
 - Login fix
 - Embed forum route added

 [4.8]
 - Notification mail / Alerts (partial done)
 - User sync for FreiChat plugin 
 - Forum docker image
 - Role color/styles config
 - Floating chat feature
 - Bug fixes
 - Update dependencies (partial done)
 - Load cache based on protocol and domain to avoid 404s
 - Add feature to configure login by username or email or both
 - Add switch for site logo and site title

 [4.7]
 - Make send pm button more visible
 - Post & cancel buttons: layout fix in new topic
 - Hide "edit profile" for users that dont have permission
 - Give warning if installing in database with codoforum tables 
 - Improved anti spam rules plugin 
 - Toggle to make forum private 
 - Security fixes for routes 
 - Oauth admin shows old settings on save & debug logging permissions fixed
 - Fixed password check for edit user in backend
 - Fixed role based search in backend  

 [4.6]
  - Fixed SSO plugin bug.
  - Fixed importer bugs
  - Fixed infinite scrolling issues
  - Added feature to save infinite scrolling option in local storage.
  - Better avatar image quality
  - Added anti spam plugin
  - Replaced simple_html_dom with plugin blocks making pages load faster.
  - All new private messenger (No more FreiChat folder inside codoforum)
     
 [4.5]
  - Added option to load embedded content(only first) as part of excerpt in All topics and category page
  - Fixed pmx link getting appended to notifications div
  - Fixed pmx styles
  - Fixed dropdown popover bug and other css fixes to menu and responsive styles
  - Replaced custom tabs in profile view/edit with bootstrap tabs
  - Profile edit signature bug fixed
  - Fixed tick image path when subscription is changed
  - Added documentation to customize theme easily (Includes css fixes to standardize colors and removal of unused css)
  - Reply button not working for mobile bug fixed
  

 [4.4.1]
 - Regions not shown in UI Elements -> Blocks fixed
 - Fixed embed of videos and audios from external websites on https
 - Fixed theme background image bug
 - Fix facebook id showing in exponential bug
 - Fixed delete button for topics
 

 [4.4]
 - Codopm redesign
 - Fixed upgrade bug
 - Hybridauth oauth login rewrite
 - Mobile theme fixes
 - Upgraded bootstrap from 3 to 4  
 - Add dedicated page support for plugins
 - Rewrite theme loading. Moved everything common to default theme
 - read more link bug fix
 - Updated LighnCandy and Smarty 
 - Added Ignis theme
  
 [4.3] 
 - Profile avatar button [FIXED]
 - Search bar in mobile [DONE] 
 - Edit profile with custom fields error [FIXED]
 - Handle request URI too long
 - Add descriptions for the new added permissions
 - Edit profile permissions incorrect
 - codopm add option send a copy via mail
 - codopm add more visible send pm button
 - Add sort by
 - FB sharing
 - Add option to precheck remember me
 - Search doesn't work well with pagination
 - Mark as read button not working
 - Enable Infinite scroll -> disable -> Active page shown wrong
 - Category labels feature
 

[4.2] - [Released]

 - checkbox design 
 - poll alerts to be hidden on esc or enter key with a primary button for 'ok'
 - Order by topics rework [Sticky topics -> Normal topics -> Closed sticky topics -> Closed normal topics] each ordered by time [DONE]
 - Clear cache option in backend [DONE]
 - Changing preview translation caused preview to not work [FIXED]
 - Add an icon to left of internal attachments [DONE]
 - Download file with orginal name [DONE]
 - Fix repeated failed requests for non-image uploads [DONE]
 - Click to view full size image with lightbox [DONE]
 - Many mobile design fixes [DONE]

[4.1.2] - [Released]

 - Topic title special characters in category/topic/profile/urls [done]
 - Smiley and headers button in editor [done]
 - Improved profile view css
 - Pre select category when creating topic from category page
 - Added breadcrumbs in new topic based on source
 - Mass mails bug fixed
 - Added missing translations
 - rep button design fixed 
 - footer pagination to be fixed positioned [done]
 - Sub category shows old style num. of unread topics [fixed]
 - No. of views doesn't fit in the fixed sidebar when you scroll [fixed]

[4.1.1] - [Released]

 - Create new topic not fixed in sidebar in categories page
 - Category name and topic title wrong in topic page [fixed]
 - Automatically check for new version in admin dashboard
 - Fixed css in user profile view/edit for tablet and mobile

[4.1] - Beta
 - Handle special ` characters &acute;
 - Better pagination design
 - Fixed theme switch from backend
 - Reputation not visible [fixed]
 - Blocks not visible fixed
 - better reputation design
 - Fixed polls not working by default
 - Fixed PM not loading and added notifications

[4.0] - Alpha
 - Added Moderator only access in the admin panel. 
 - Poll plugin
 - New theme
 - Sort by Most popular, newest and most commented
 - facebook share

[3.7.2]

 - Auto close deleting topics [FIXED]
 - Notifications in All Notifications not linked to topic/post [FIXED]
 - Manual upgrade 
 
[3.7.1]

 - Search with code bug [DONE]
 - mentions not working [DONE]
 - 1 security fix [DONE]
 - Template fix [DONE]
 - Avatar not created for users created from backend [DONE]

[3.7]

 - Cateogry hide on load and drop down to show sub categories [done]
 - Spam filter enable/disable [fixed]
 - Import bugs [fixed]
 - Notification shown for deleted topics [fixed]
 - Move topics across categories bug [fixed]
 - Move multiple posts from topic page [done]
 - Prevent multiple clicks when replying [done]
 - Incorrect Notifications sent due to cron [fixed]
 - Improved notification count to only include unique counts

[3.6]


 - user profile link bug fixed
 - codopm mobile compatibility
 - user profile mobile view improved
 - spam filter option added in backend
 - moderation icon fixed
 - xss security issues fixed
 - user token security issue fixed
 - user group based css feature 

-------------------------------------------------------------------------------- 



- title answersed/unanswered
 - create link for guests in category page not working
 - news page
 - permissions not added for the new features
 - moderation link check
 - cyrillic chars in username [done]
 - topic tracker mark read bug fixed
 - 

 - close topics [done]
 - auto close topics [done]
 - disable reply for closed topics [done]
 - show lock icon for closed topics [done]
 - close in edit topics [done]
 - permission factors [done]
 - all changes to category page [done]
 - Replace : with spaces at the beginning for indentation [done]
 - made reply button visible to not logged in users [done]
 - improved user search in backend with all roles and search by mail [done]
 - history added for topics [done]
 - topic page search [done]
 - option to change name of admin folder [done]
 - remember me  [done]
 - notification read/unread , unread until clicked [done]
 - notification duplicates fixed [done]
 - category counts fixed [done]
 - hidden category load more fixed [done]
 - center text not working. [fixed]
 - text file not getting uploaded [fixed]
 - fixed recaptcha [done]
 - page url ui fixed [done]
 - close reports [LEFT]
 - custom profile fields

http://codologic.com/forum/index.php?u=/topic/6019/sso-more-websites#post-14376
http://codologic.com/forum/index.php?u=/topic/5946/search-bar-disappears#post-13820
http://codologic.com/forum/index.php?u=/topic/5898/ideas-for-an-art-oriented-community
http://codologic.com/forum/index.php?u=/topic/5817/post-shows-on-front-page#post-13675
http://codologic.com/forum/index.php?u=/topic/5818/logout-problem-with-sso-login#post-13679
http://codologic.com/forum/index.php?u=/topic/5324/big-post#post-13668
http://codologic.com/forum/index.php?u=/topic/5817/post-shows-on-front-page#post-13526
http://codologic.com/forum/index.php?u=/topic/5017/social-missing
http://codologic.com/forum/index.php?u=/topic/4596/help-need-help-with-sso-plugin-with-wordpress#post-11686
http://codologic.com/forum/index.php?u=/topic/5436/links-and-smileys-active-on-front-page-post-previews#post-12937




http://codologic.com/forum/index.php?u=/topic/3365/freichat-integration-with-codoforum

-----------------------------------------------------------------


table bug fixed
search bug fixed
topics load fixed
plugin templates inside plugin folder allow
permission for create topics fixed

DONE
====

 - Improved spam filter
 - Fixed warnings
 - Fixed all search bugs
 - Fixed breadcrumb problem at near 800px width
 - Fixed mobile menu
 - Fixed menu links
 - Fixed custom pages scrollbar
 - Fixed custom pages permissions
 - Fixed page link bug for posts
 - Mention edit problem fixed
 - Added mass mail feature
 - User approve before registration feature
update docs to min version PHP 5.4

http://codologic.com/forum/index.php?u=/topic/3260/bugs-3-2-1#post-9292
CODOFORUM

 - Providing the best forum experience

Installation: 

1. Extract zip file
2. Go to http://path_to_codoforum/install

last tested: 5.3.28

//canonical url wrong i

3.2.1

breadcrumb
spam filter bugs fixed
new topic focus bug fixed


hide side of admin panel [done]
forum draft security

[color=red]Trying the red color[/color] --> BUG fixed
plugin api backward compatibility
Allow dashes in username
Fixed last login time for new users
Added option to create new topic when no posts are present in recent posts
Improved hook system
Avatar bug fixed
show category permission added
go to topic page on creation instead of category
mark all as read click bug fixed

/**

-- Moderation --

 - Edit
 - Delete
 - Lock
 - Split
 - Merge


permission issues fixed

TODO 

Add permission 'view category' 
Add html_entity_decode to all titles and check for utf8 too
            $info['title'] = html_entity_decode($info['title']); 282 Topic.php



 Ordered by priority but implementation order may differ
    

 - Remove CSRF from GET. Make proper use of GET/POST 
 - Include nonce for any sensitive requests
 - Member Groups
 - Board level permissions [includes visibility]
 - Allow Member Titles/rank , Reputation System
 - Allow Sticky Topics
 - Report Post To Moderator I.e Flag [Undecided to make it a plugin or not]
 - Lock/close Topic
 - Split Topic
 - Move Topics Between Categories
 - Merge Topics
 - Trust Level For Users
 - Reply-Via-Email Support
 - Board types http://codologic.com/forum/index.php?u=/forum/topic/1222/boardtypes
 - Digest emails
 - Auto-closing topics
 - RSS feeds
 - Add docs for optimized js inclusion in head/body and to use defer when possible

Below have no priority or order in which they will be added 

 - Allow To Change Logo Link
 - Synchronized Scrolling
 - Allow To Create New Topic From "post" In Same/different Category
 - More Templates 
 - Alert When Content In Editor
 - Show Latest Post While Showing Topics
 - Cut Message For Notifications For Long Messages
 - Login Via Create New Topic Should Redirect Back After Authentication
 - admin email not updated in global settings 
 - https://wiki.phpbb.com/Event_List

Plugin based
------------

Not in any order

 - similar topics, online members plugin
 - Add polling feature
 - Vote up and down feature
 - provide option to enable captcha
 - share post/ topic
 - related topics list 


codoforum v.3.1

TODO
====

 - notify when level 3 saying "You will receive email notifications"
 - remove /forum from url
 - when u click on "reply button" comes under the "notifications control panel
 - Inclusion of gallery upload
 - Sitemaps and RSS 
 - Allow image alignment in editor
 - check count inconsistencies -> due to sqlite support
 - mobile CF should use full screen
 - add categories as a slide in menu
 - search bar is ugly
 - change logo
 - o-embed add to tags page
 - oembed support opengraph with whitelist
 - topic title minimum length [10]
 - http://www.evanmiller.org/how-not-to-sort-by-average-rating.html
   http://www.evanmiller.org/bayesian-average-ratings.html
 - when reply clicked , the text highlighted for that post is quoted
 - backend option for default notification level
 - numeric list onclick should print numbers sequentially
 - cache dir and attachments dir not created in buildsrcipt
 - move created from codo_notify_queue to codo_notify

codoforum v.3.0

DONE
====

 - Fixed many PHP Notices/Warnings
 - Slide in menu
 - Profile edit bug fixed
 - when post is deleted the recent by is not updated [BUG] ** [fixed]
 - avatar upload preview ratio not correct [fixed]
 - openssl_random_pseudo_bytes error when openssl extension is disabled [fixed]
 - quote button conflict with auto save [fixed]
 - quote button sometimes does not take you to next line [fixed]
 - remove .htaccess
 - last login never updated fixed.
 - import bug fixed
 - compression reenabled
 - XSS vulnerabilities fixed
 - attachment bug fixed
 - user avatar bug fixed
 - tags bug fixed

codoforum v.2.6

DONE
====

 - Added Cron log
 - Made notification avatar paths relative
 - SSO bug fixed
 - mail_notify bugs
 - Converted smiley urls to relative
 - Excerpt issue fixed
 - Add user bug fixed
 - codoPM messenger
 - Editor content hide bug fixed
 - "Mark all as read" width overflow bug fixed. 
 - Arbitrary file download fixed
 - User register bug fixed
 - Image infinite bug fixed
 - User change password bug fixed
 - SESSION fixation fixed
 - CSRF improved
 - notification avatar paths fixed

codoforum v.2.5

DONE
====

 - One click update
 - server-side rendering for initial view HTML
 - Deferred loading of JS files
 - Added defer option to Asset Manager 
 - Calculate reading time of topics
 - noindex, follow for users, tags page
 - Improved excerpt generator
 - changed urls from forum/* to $1
 - editor resize zIndex fixed
 - Decreased no. of requests
 - Improved fonts for better reading by dividing into title, desc & numeric
 - Added Open Sans woff file for firefox, IE support
 - Added fallback for fonts & added all fonts to colors.less
 - user edit bug fixed
 - fixed server errors due to changes in Apache 2.4
 - resized all category & avatar images to 36x36 for optimized delivery
 - disabled cron & notification requests for guests
 - corrected tree structure of nested categories
 - changed category routes from /topics/name to /category/name
 - hierarchical breadcrumbs
 - image with links markdown conflicts with oembed
 - Major UI rewrite
 - SEO improvements
 - mobile full-width layout
 - Handlebars templating server-side
 - Removed duplicate templates & methods & API
 - nested sub-categories bug fixed
 - Responsive breadcrumbs using dropdown
 - pagination bug fixed
 - Show display name instead of username
 - topic title entities bug fixed
 - create sqlite db if not exists
 - followers are not removed when notification level is decreased
 - sqlite query compatibility
 - PHP warnings fixed
 - fixed XSS issues
 - Made SQL_CALC_ROWS compatible with non-mysql drivers
 - mail bug fixed

codoforum v.2.4

DONE
====

 - notification level only for logged in
 - topic user avatar not shown in subscriptions
 - og and schema.org meta added
 - canonical rel added
 - widget content bg css fixed
 - link rel and prev added for pagination
 - article:published_time, article:modified_time added to meta
 - 3rd topic creation in category bug fixed
 - auto subscribe only on first reply to topic
 - make \<code> fixed height to prevent long codes to take unnecessary space
 - notification user preferences fixed
 - removed unnecessary css classes
 - printable pages with @media print
 - editor textarea fast load
 - reply/new topic autosave drafts
 - oembed img .jpg?x=y do not work since they do not end with ext
 - oembed thumbnails
 - improved oembed caching
 - sidebar statistics responsive
 - generate first letter image from name for default profile pic
 - FB, Twitter, G+ login issues
 - fixed width containers
 - tags layout issues fixed
 - topic page route fixed
 - prevent duplicate notification when multiple tabs are open
 - meta description added
 - Improved fonts
 - Profile view bug fixed

codoforum v.2.3

DONE
====

 - topic on search bugs [duplicate edit, delete icons & 404 image] fixed
 - User class & import bugs 
 - updated post_notify plugin -> mail_notify plugin V2.0 [optimized queries]
 - updated code highlighter in editor 
 - Added support for tables
 - Updated Bootstrap to V3.2 
 - menu bar with user profile improved
 - parse problem due to multiple code tags
 - Updated markdown parser
 - tags div empty bug fixed
 - fixed lags in mobile
 - pseudo real time notifications
 - desktop notifications
 - subscriptions and notifications
 - menu link block
 - digest mail
 - @mentions 
 - Allow SVG
 - optimum w & h for avatar
 - on load highlight bug after reply/edit
 - installation improved
 - user api - preferences
 - edit other's topic not reloaded fixed

codoforum v.2.2

DONE
====

 - rename head.php to header.php in install to fix usr/lib/php conflict
 - sso token security fix
 - User class rewrite
 - on load last post bg color bug fixed
 - enhanced bbcode , support added for 
    strike, move, sup, sub, pre, center, left, right, list(ul & ol)
 - Oembed one boxing
 - bbcode parser added to importer
 - image uploaded without text not added to character count
 - topic title link should take you to last read post
 - added interface and improved importer
 - option to add smiley in backend
 - added option to add inline css/js using Stream & Collection classes
 - css styles improvements
 - made category images full size 

codoforum v.2.1

DONE
====

 - forum tags
 - child theme support
 - image resize for profile pics
 - admin theme revamped
 - block system

codoforum v.2.0

DONE
====

 - Improved Hook system
 - User edits not saved bug fixed
 - Added Block system for plugins
 - Fixed update of read_time in unread message tracking
 - Private messaging system plugin - codoPM
 - XSS injections fixed
 - user edit backend
 - Recent posts made by user
 - Rewrite cache relative to absolute urls
 - Globalized cache directory
 - Minify js & css 
 - Compile less in server lessphp
 - Back to top button
 - post notification message post url fixed
 - improved mobile compatibility
 - backend installer & upgrader
 - importer default values bug fixed
 - message formatting bug fixed
 - Improved backend
 - Improved installer
 - Added Laravel framework components - Schema & Eloquent
 - Removed triggers
 - Count consistency updater added
 - New battledark theme
 - Added new blocks in templates

codoforum v.1.5

DONE
====

 - Importer inconsistencies fixed
 - Queries optimized to give 5x performance 
 - New topic button style improved
 - unread message system
 - post notification message format fixed
 - plugin list system
 - plugin admin api
 - Added self importer i.e to import from codoforum itself allowing
   you to import from older versions of codoforum
 - Added support for importing no. of posts, profile views and details
   of last post of every topic
 - Added permission check for writable files/folders
 - fixed recent post link to profile url

codoforum v.1.4

DONE
====

 - Add unverified role for unverified users [no permission to post by default]
 - Added option in backend to confirm users
 - Fixed edit user bug
 - user banning by name/ip/email
 - poor man's cron
 - improved admin menu
 - admin login security fix

codoforum v.1.3

DONE
====

 - Recursive count total topics from sub categories
 - Added importer for SMF 2 and Kunena 3
 - Improved importer performance by 100 times


codoforum v.1.2

DONE
====
 - search feature
 - titles change with page 
 - added notification error class
 - added notification for user login error 
 - added notification for mail error
 - SSO for joomla
 - add super powerful favicon[frontend and backend]
 - increment profile views bug fixed
 - make the editor responsive 
 - change password 
 - forgot password feature
 - added meta generator

codoforum v.1.1

DONE
====

 - SSO
 - Social sign in feature
 - breadcrumbs
 - builder
 - importer for drupal
 - Removed base tag -> solution
    - During editing replace with absolute urls . 
    - When saving make it relative then during output prepend again to make 
      absolute depending on domain path.


codoforum v.1.0

DONE
====

 - freichat framework
 - all basic features


Requirements
====

 - Apache/nginx or any web server that supports PHP
 - PHP 5.4+
 - MySQL or equivalent
 - php mbstring, php xml, php gd, php curl

MySQL specific query used in Topic.php, talk with developers if wanting to port to different database.