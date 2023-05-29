<?php

DB::table(PREFIX . 'codo_config')
        ->where('option_name', 'version')
        ->update(array('option_value' => '3.2'));

DB::statement('ALTER TABLE ' . PREFIX . 'codo_plugins MODIFY COLUMN plg_schema_ver VARCHAR(50)');

if (!Schema::hasTable('b8_wordlist')) {

    Schema::dropIfExists('b8_wordlist');
    Schema::create('b8_wordlist', function($table) {

        $table->string('token', 255);
        $table->integer('count_ham')->nullable();
        $table->integer('count_spam')->nullable();
        $table->primary('token');
    });

    DB::table('b8_wordlist')->insert(array(
        array(
            'token' => 'b8*dbversion',
            'count_ham' => 3,
            'count_spam' => null
        ),
        array(
            'token' => 'b8*texts',
            'count_ham' => 0,
            'count_spam' => 0
        )
    ));
}

if (!Schema::hasTable('codo_pages')) {

    Schema::dropIfExists('codo_pages');    
    Schema::create(PREFIX . 'codo_pages', function($table) {

        $table->increments('id');
        $table->string('title', 300);
        $table->string('url', 300);
        $table->text('content');
    });

    Schema::dropIfExists('codo_page_roles');        
    Schema::create(PREFIX . 'codo_page_roles', function($table) {

        $table->integer('pid')->index();
        $table->integer('rid')->index();
    });

    $permission = new \CODOF\Permission\Permission();
    $permission->add('view category', 'forum', 1);
    $permission->add('moderate topics', 'forum', array(
        ROLE_MODERATOR, ROLE_ADMIN
    ));
    $permission->add('moderate posts', 'forum', array(
        ROLE_MODERATOR, ROLE_ADMIN
    ));


    $str = <<<EOD
        INSERT IGNORE INTO codo_blocks (id, module, theme, status, weight, region, content, visibility, pages, title, cache) VALUES
(2, 'html', 'blue', 0, 0, 'block_footer_right', '<small>\r\n   \r\n<a href="https://facebook.com/codologic"><i class="icon-facebook"> </i></a> \r\n <a href="https://twitter.com/codologic"><i class="icon-twitter"> </i></a>\r\n <a href="https://plus.google.com/+codologic"><i class="icon-google-plus-square"> </i></a>\r\n\r\n        <br>\r\n        <a href="index.php?u=page/6">Terms of Service</a> |\r\n        <a href="index.php?u=page/7">Privacy</a> |\r\n        <a href="#">About us</a> \r\n</small>', 0, '', 'Footer Right', 1);
EOD;

    DB::insert($str);

    $str = <<<EOD
INSERT IGNORE INTO codo_pages (id, title, url, content) VALUES
(6, 'Terms Of Service', 'terms-of-service', '<h1>Terms and Conditions</h1>\r\n<hr>\r\n<p>By using and accessing this website, <a href="http://codoforum.com">codoforum.com</a> a part of <a href="http://codologic.com">Codologic</a> (collectively referred to as the "Site" or "Codoforum" in these Terms of Service), you ("you", "user" or, "end user") agree to these Terms of Service (collectively, the "Terms of Service" or "Agreement").</p>\r\n<p>IF YOU DO NOT AGREE TO THE TERMS OF THIS AGREEMENT, IMMEDIATELY STOP ACCESSING THIS SITE.</p>\r\n<p>You agree not to modify, copy, distribute, transmit, display, perform, reproduce, publish, license, transfer, create derivate work from, sell or re-sell any content or information obtained from or through the Site.\r\n<br><br><strong>Third-party Sites.</strong></p>\r\n<p>The Site may contain links to other websites maintained by third-parties. These links are provided solely as a convenience and does not imply endorsement of, or association with, the party by Codologic.\r\n<br><br><strong>Modifications to this Agreement.</strong></p>\r\n<p>Codologic reserves the right to change or modify any of the terms and conditions contained in this Agreement at any time. You acknowledge and agree that it is your responsibility to review the Site and these Terms of Service from time to time. Your continued use of the Site after such modifications to this Agreement will constitute acknowledgment of the modified Terms of Service and agreement to abide and be bound by the modified Terms of Service.\r\n<br><br><strong>Termination of Use.</strong></p>\r\n<p>Codologic shall have the right to immediately terminate or suspend, in its discretion, your access to all or part of the Site with or without notice for any reason.\r\n<br><br><strong>Limitation of Liability.</strong></p>\r\n<p>In no event shall Codologic or its affiliates be liable for any indirect, incidental, special, punitive damages or consequential damages of any kind, or any damages whatsoever arising out of or related to your use of the Site, the content and other information obtained therein.</p>\r\n<p>Certain jurisdictions prohibit the exclusion or limitation of liability for consequential or incidental damages, thus the above limitations may not apply to you.\r\n<br><br><strong>Indemnity</strong></p>\r\n<p>You agree to indemnify and hold us, and our subsidiaries, affiliates, directors, officers, agents, vendors or other partners and employees harmless from any claim or demand, including attorneys’ fees, made by any third party due to or arising out of any material or information posted, provided, transmitted or otherwise made available by you on this Site or through Codologic.com’s services, or by your violation of these Terms, or by your violation of the rights of another.\r\n<br><br><strong>Disclaimers and Limitation of Liability </strong></p>\r\n<p>You understand and agree that this Site is provided "AS-IS" and that we assume no responsibility for your ability to (or any costs or fees associated with your ability to) obtain access to Codologic.com. Nor do we assume any liability for the failure to store or maintain any user communications or personal settings.</p>\r\n<p>NO ADVICE OR INFORMATION, WHETHER ORAL OR WRITTEN, OBTAINED BY YOU FROM CODOLOGIC.COM OR THROUGH OR FROM ITS SERVICES SHALL CREATE ANY WARRANTY NOT EXPRESSLY STATED IN THESE TERMS AND CONDITIONS. IN NO EVENT SHALL CODOLOGIC.COM OR ITS OWNER BE LIABLE TO YOU OR ANY THIRD PARTY FOR ANY INDIRECT, CONSEQUENTIAL, EXEMPLARY, INCIDENTAL, SPECIAL OR PUNITIVE DAMAGES, INCLUDING LOST PROFIT DAMAGES ARISING FROM YOUR USE OF CODOLOGIC.COM OR ITS SERVICES EVEN IF WE HAVE BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.\r\n<br><br><strong>Jurisdiction</strong></p>\r\n<p>This agreement is governed and construed in accordance with the Laws of Union of India. You hereby irrevocably consent to the exclusive jurisdiction and venue of courts in Mumbai, Maharashtra, India, in all disputes arising out of or relating to the use of Codologic site/services. Use of the Codologic site/services is unauthorized in any jurisdiction that does not give effect to all provisions of these terms and conditions, including without limitation this paragraph. You agree to indemnify and hold Codologic.com, its subsidiaries, affiliates, officers, directors, employees, and representatives harmless from any claim, demand, or damage, including reasonable attorneys'' fees, asserted by any third party due to or arising out of your use of or conduct on the Codologic site/services.</p>\r\n<p>The section titles and other headings in these Terms are for convenience only and have no legal or contractual effect. Our failure to exercise or enforce any right or provision of these Terms will not operate as a waiver of such right or provision. If any provision of these Terms is unlawful, void or unenforceable, that provision is deemed severable and does not affect the validity and enforceability of any remaining provisions.</p>\r\n<p><br><br><strong>Date of Last Update.</strong></p>\r\n<p>This agreement was last updated on May 15, 2014.</p>\r\n'),
(7, 'Privacy Policy', 'privacy-policy', '\r\n        \r\n <h1>Privacy policy</h1>\r\n <hr>\r\n <br>       \r\n        \r\n<p><strong>Privacy policy for Codoforum:</strong></p>\r\n<p>Your use of any information or materials on this website is entirely at your own decision, for which we shall not be liable. </p>\r\n<p>It shall be your own responsibility to ensure that any products, services or information available through this website meet your specific requirements.</p>\r\n<p>This website contains material which is owned by or licensed to us. This material includes, but is not limited to, the design, layout, look, appearance and graphics. Reproduction is prohibited other than in accordance with the copyright notice, which forms part of these terms and conditions.</p>\r\n<p>All trademarks reproduced in this website which are not the property of, or licensed to, the operator are acknowledged on the website.\r\nUnauthorized use of this website may give rise to a claim for damages and/or be a criminal offence.</p>\r\n<p>From time to time this website may also include links to other websites. These links are provided for your convenience to provide further information. They do not signify that we endorse the website(s). </p>\r\n<p>We have no responsibility for the content of the linked website(s).\r\nYour use of this website and any dispute arising out of such use of the website is subject to the laws of India or other regulatory authority.</p>\r\n<br>\r\n<br>\r\n<p><strong>Log Files:</strong></p>\r\n<p>Codoforum makes use of log files (which includes IP addresses, type of browser, internet service providers, referrer, number of clicks etc) to understand user movements and demographics. Such information is not linked to any information that is personally identifiable.</p>\r\n\r\n<br>\r\n<br>\r\n<p><strong>Cookies:</strong></p>\r\n<p>Codoforum uses cookies to store information about visitors preferences, record user-specific information on which pages the user access or visit, customize Web page content based on visitors browser type or other information that the visitor sends via their browser.</p>\r\n<p>We will not sell, disseminate, disclose, trade, transmit, transfer, share, lease or rent any personally identifiable information to any third party not specifically authorized by you to receive your information except as we have disclosed to you in this Privacy Policy. However we may use such information to update you about promotional offers and updates from us.</p>\r\n<br>\r\n<br>\r\n<p><strong>Changes in Our Privacy Policy:</strong></p>\r\n<p>We reserve the right to change this Privacy Policy without providing you with advance notice of our intent to make the changes.</p>\r\n<p>If you believe that any information we are holding on you is incorrect or incomplete, please contact us as soon as possible.</p>\r\n\r\n');
EOD;
    DB::insert($str);
}
