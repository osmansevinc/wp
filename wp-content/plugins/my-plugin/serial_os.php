<?php

# -*- coding: utf-8 -*-
/**
 * Plugin Name: Serial-OS
 * Description: Get the serials and save the db
 * Version:     2020.07.22
 * Author:      Osman Sevinc
 */

add_action( 'admin_menu', function(){
    add_menu_page( 'My Plugin', 'My Plugin', 'manage_options', 'my_plugin_slug', 'my_plugin_page', 'dashicons-cloud' );
});

function my_plugin_page() {
    ?>
    <form class="form" id="ajax-contact-form" action="#">
        <input type="text" name="name" id="name"  placeholder="Name" required="">
    </form>
    <?php
    $id   = 4321;
    $data = array(
        'data-nonce' => wp_create_nonce( MY_ACTION_NONCE . $id ),
        'data-id'    => $id,
    );
    echo get_submit_button( "Ajax Primary", 'primary large', 'my-action-button-1', FALSE, $data );

    $id   += 1234;
    $data = array(
        'data-nonce' => wp_create_nonce( MY_ACTION_NONCE . $id ),
        'data-id'    => $id,
    );
    echo get_submit_button( "Ajax Secundary", 'secondary', 'my-action-button-2', FALSE, $data );
}

add_action( 'admin_footer', 'my_action_javascript' ); // Write our JS below here

function my_action_javascript() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('#my-action-button-1,#my-action-button-2').click(function () {
                var name = $("#name").val();
                var $button = $(this);

                var data = {
                    'action': 'my_action',
                    'id': $button.data('id'),
                    'nonce': $button.data('nonce'),
                    'name': name
                };
                // Give user cue not to click again
                $button.addClass('disabled');
                // Invalidate the nonce
                $button.data('nonce', 'invalid');

                $.post(ajaxurl, data, function (response) {
                    alert('Got this from the server: ' + response);

                });
            });

            $('#ajax-contact-form').submit(function(e){
                var name = $("#name").val();
                $.ajax({
                    data: {action: 'contact_form', name:name},
                    type: 'post',
                    url: ajaxurl,
                    success: function(data) {
                        alert(data);
                    }
                });

            });
        });
    </script>
    <?php
}

add_action('wp_ajax_contact_form', 'contact_form');
add_action('wp_ajax_nopriv_contact_form', 'contact_form');

function contact_form()
{
    echo $_POST['name'];
    wp_die(); // this is required to terminate immediately and return a proper response
}

add_action( 'wp_ajax_my_action', 'my_action' );

function my_action() {
    global $wpdb; // this is how you get access to the database

    $tablename = $wpdb->prefix . 'deneme';
    $sql = $wpdb->prepare("INSERT INTO `$tablename` (`name`) values (%s)", $_POST['name']);
    if ($wpdb->query($sql) !== false) {
        echo $_POST['name'];
        wp_die();
    }

    $id    = $_POST['id'];
    $nonce = $_POST['nonce'];
    if ( wp_verify_nonce( $nonce, MY_ACTION_NONCE . $id ) ) {
        $response = intval( $id );
        $response += 10;
        echo $response;
    } else {
        echo - 1;
    }
    wp_die(); // this is required to terminate immediately and return a proper response
}