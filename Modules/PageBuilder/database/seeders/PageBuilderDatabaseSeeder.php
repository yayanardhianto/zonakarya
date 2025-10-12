<?php

namespace Modules\PageBuilder\database\seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\PageBuilder\app\Models\CustomizeablePage;
use Modules\PageBuilder\app\Models\CustomizablePageTranslation;

class PageBuilderDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Model::unguard();

        $page = new CustomizeablePage();
        $page->slug = 'terms-conditions';
        if ($page->save()) {
            foreach (allLanguages() as $language) {
                CustomizablePageTranslation::create([
                    'customizeable_page_id' => $page->id,
                    'lang_code' => $language->code,
                    'title' => 'Terms & Conditions',
                    'description' => '<h2 class="title">Who we are</h2>
                    <p><b>Suggested text:</b> Our website address is: https://yourwebsite.com</p>
                    <h6 class="title">Comments</h6>
                    <p><b>Suggested text:</b> When visitors leave comments on the site we collect the data shown
                        in the comments form, and also the visitor’s IP address and browser user agent string to
                        help spam detection.</p>
                    <p>An anonymized string created from your email address (also called a hash) may be provided
                        to the Gravatar service to see if you are using it. The Gravatar service privacy policy
                        is available here: https://automattic.com/privacy/. After approval of your comment, your
                        profile picture is visible to the public in the context of your comment.</p>
                    <h6 class="title">Media</h6>
                    <p><b>Suggested text:</b> If you upload images to the website, you should avoid uploading
                        images with embedded location data (EXIF GPS) included. Visitors to the website can
                        download and extract any location data from images on the website.</p>
                    <h6 class="title">Cookies</h6>
                    <p><b>Suggested text:</b> If you leave a comment on our site you may opt-in to saving your
                        name, email address and website in
                        cookies. These are for your convenience so that you do not have to fill in your details
                        again when you leave another
                        comment. These cookies will last for one year.</p>
                    <p>If you visit our login page, we will set a temporary cookie to determine if your browser
                        accepts cookies. This cookie
                        contains no personal data and is discarded when you close your browser.</p>
                    <p>When you log in, we will also set up several cookies to save your login information and
                        your screen display choices.
                        Login cookies last for two days, and screen options cookies last for a year. If you
                        select "Remember Me", your login
                        will persist for two weeks. If you log out of your account, the login cookies will be
                        removed.</p>
                    <p>If you edit or publish an article, an additional cookie will be saved in your browser.
                        This cookie includes no personal
                        data and simply indicates the post ID of the article you just edited. It expires after 1
                        day.</p>
                    <h6 class="title">Embedded content from other websites</h6>
                    <p><b>Suggested text:</b> Articles on this site may include embedded content (e.g. videos,
                        images, articles, etc.). Embedded
                        content from other websites behaves in the exact same way as if the visitor has visited
                        the other website.</p>
                    <p>These websites may collect data about you, use cookies, embed additional third-party
                        tracking, and monitor your
                        interaction with that embedded content, including tracking your interaction with the
                        embedded content if you have an
                        account and are logged in to that website.</p>
                    <p>For users that register on our website (if any), we also store the personal information
                        they provide in their user
                        profile. All users can see, edit, or delete their personal information at any time
                        (except they cannot change their
                        username). Website administrators can also see and edit that information. browser user
                        agent string to help spam detection.</p>',
                ]);
            }
        }

        $page = new CustomizeablePage();
        $page->slug = 'privacy-policy';
        if ($page->save()) {
            foreach (allLanguages() as $language) {
                CustomizablePageTranslation::create([
                    'customizeable_page_id' => $page->id,
                    'lang_code' => $language->code,
                    'title' => 'Privacy Policy',
                    'description' => '<h2 class="title">Who we are</h2>
                    <p><b>Suggested text:</b> Our website address is: https://yourwebsite.com</p>
                    <h6 class="title">Comments</h6>
                    <p><b>Suggested text:</b> When visitors leave comments on the site we collect the data shown
                        in the comments form, and also the visitor’s IP address and browser user agent string to
                        help spam detection.</p>
                    <p>An anonymized string created from your email address (also called a hash) may be provided
                        to the Gravatar service to see if you are using it. The Gravatar service privacy policy
                        is available here: https://automattic.com/privacy/. After approval of your comment, your
                        profile picture is visible to the public in the context of your comment.</p>
                    <h6 class="title">Media</h6>
                    <p><b>Suggested text:</b> If you upload images to the website, you should avoid uploading
                        images with embedded location data (EXIF GPS) included. Visitors to the website can
                        download and extract any location data from images on the website.</p>
                    <h6 class="title">Cookies</h6>
                    <p><b>Suggested text:</b> If you leave a comment on our site you may opt-in to saving your
                        name, email address and website in
                        cookies. These are for your convenience so that you do not have to fill in your details
                        again when you leave another
                        comment. These cookies will last for one year.</p>
                    <p>If you visit our login page, we will set a temporary cookie to determine if your browser
                        accepts cookies. This cookie
                        contains no personal data and is discarded when you close your browser.</p>
                    <p>When you log in, we will also set up several cookies to save your login information and
                        your screen display choices.
                        Login cookies last for two days, and screen options cookies last for a year. If you
                        select "Remember Me", your login
                        will persist for two weeks. If you log out of your account, the login cookies will be
                        removed.</p>
                    <p>If you edit or publish an article, an additional cookie will be saved in your browser.
                        This cookie includes no personal
                        data and simply indicates the post ID of the article you just edited. It expires after 1
                        day.</p>
                    <h6 class="title">Embedded content from other websites</h6>
                    <p><b>Suggested text:</b> Articles on this site may include embedded content (e.g. videos,
                        images, articles, etc.). Embedded
                        content from other websites behaves in the exact same way as if the visitor has visited
                        the other website.</p>
                    <p>These websites may collect data about you, use cookies, embed additional third-party
                        tracking, and monitor your
                        interaction with that embedded content, including tracking your interaction with the
                        embedded content if you have an
                        account and are logged in to that website.</p>
                    <p>For users that register on our website (if any), we also store the personal information
                        they provide in their user
                        profile. All users can see, edit, or delete their personal information at any time
                        (except they cannot change their
                        username). Website administrators can also see and edit that information. browser user
                        agent string to help spam detection.</p>',
                ]);
            }
        }

        $page = new CustomizeablePage();
        $page->slug = 'custom';
        if ($page->save()) {
            foreach (allLanguages() as $language) {
                CustomizablePageTranslation::create([
                    'customizeable_page_id' => $page->id,
                    'lang_code' => $language->code,
                    'title' => 'Custom Page',
                    'description' => '<h2 class="title">Who we are</h2>
                    <p><b>Suggested text:</b> Our website address is: https://yourwebsite.com</p>
                    <h6 class="title">Comments</h6>
                    <p><b>Suggested text:</b> When visitors leave comments on the site we collect the data shown
                        in the comments form, and also the visitor’s IP address and browser user agent string to
                        help spam detection.</p>
                    <p>An anonymized string created from your email address (also called a hash) may be provided
                        to the Gravatar service to see if you are using it. The Gravatar service privacy policy
                        is available here: https://automattic.com/privacy/. After approval of your comment, your
                        profile picture is visible to the public in the context of your comment.</p>
                    <h6 class="title">Media</h6>
                    <p><b>Suggested text:</b> If you upload images to the website, you should avoid uploading
                        images with embedded location data (EXIF GPS) included. Visitors to the website can
                        download and extract any location data from images on the website.</p>
                    <h6 class="title">Cookies</h6>
                    <p><b>Suggested text:</b> If you leave a comment on our site you may opt-in to saving your
                        name, email address and website in
                        cookies. These are for your convenience so that you do not have to fill in your details
                        again when you leave another
                        comment. These cookies will last for one year.</p>
                    <p>If you visit our login page, we will set a temporary cookie to determine if your browser
                        accepts cookies. This cookie
                        contains no personal data and is discarded when you close your browser.</p>
                    <p>When you log in, we will also set up several cookies to save your login information and
                        your screen display choices.
                        Login cookies last for two days, and screen options cookies last for a year. If you
                        select "Remember Me", your login
                        will persist for two weeks. If you log out of your account, the login cookies will be
                        removed.</p>
                    <p>If you edit or publish an article, an additional cookie will be saved in your browser.
                        This cookie includes no personal
                        data and simply indicates the post ID of the article you just edited. It expires after 1
                        day.</p>
                    <h6 class="title">Embedded content from other websites</h6>
                    <p><b>Suggested text:</b> Articles on this site may include embedded content (e.g. videos,
                        images, articles, etc.). Embedded
                        content from other websites behaves in the exact same way as if the visitor has visited
                        the other website.</p>
                    <p>These websites may collect data about you, use cookies, embed additional third-party
                        tracking, and monitor your
                        interaction with that embedded content, including tracking your interaction with the
                        embedded content if you have an
                        account and are logged in to that website.</p>
                    <p>For users that register on our website (if any), we also store the personal information
                        they provide in their user
                        profile. All users can see, edit, or delete their personal information at any time
                        (except they cannot change their
                        username). Website administrators can also see and edit that information. browser user
                        agent string to help spam detection.</p>',
                ]);
            }
        }
    }
}
