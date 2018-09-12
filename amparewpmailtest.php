<?php
/*
Plugin Name: Ampare WP Mail Test
Plugin URI: http://www.juthawong.com/
Description: Testing WP Mailing System
Author: Juthawong Naisanguansee
Version: 20180912
Author URI: http://www.juthawong.com/
 */

function ampare_wp_mail_menu()
{
    add_options_page(
        'Ampare WP Mail Test',
        'Test WP Mail',
        'manage_options',
        'ampare-wp-mail-test',
        'ampare_wp_mail_dashboard'
    );
}

function ampare_wp_mail_dashboard()
{
    if (current_user_can("manage_options")) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $error = true;
            if (
                isset($_POST['amparenonce'])
                && wp_verify_nonce($_POST['amparenonce'], 'sendtestmail')
            ) {
                if ($emailto = sanitize_email($_POST['emailto'])) {
                    $subject = sanitize_text_field($_POST['subject']);
                    if (wp_mail($emailto, $subject, $_POST['messagebody'], array('Content-Type: text/html; charset=UTF-8'))) {
                        $error = false;
                    }
                }
            }
            if ($error) {
                echo "<div class='error'><p>Something wrong while sending email</p></div>";

            } else {
                echo "<div class='updated'><p>Email Successfully Sent!</p></div>";

            }
        }
        ?>
        <style>
            .fullwidth{
                width:100%;
                margin-top:10px;
                margin-bottom:10px;
            }
        </style>
        <div class='wp wrap wrapper'>
        <form action='' method='post'>
        <input name='emailto' type='text' placeholder='Email To' class='fullwidth'>

        <input name='subject' type='text' placeholder='Subject' class='fullwidth'>

   <?php

        wp_editor("", "messagebody");
        wp_nonce_field('sendtestmail', 'amparenonce');

        ?>
   <input type='submit' value='Send Test Email' class='button-primary fullwidth'>
   </form>
   </div>
        <?php
}
}

function ampare_mail_add_action_links($links)
{
    $mylinks = array(
        '<a href="' . admin_url('options-general.php?page=ampare-wp-mail-test') . '">Test WP Email</a>',
    );
    return array_merge($links, $mylinks);
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'ampare_mail_add_action_links');
add_action('admin_menu', 'ampare_wp_mail_menu');