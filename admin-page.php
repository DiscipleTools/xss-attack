<?php
/**
 * Plugin Name: D.T Injection Attack
 * Plugin URI: https://github.com/DiscipleTools/xss-attack
 * Description: Inject All the things
 * Version:  0.1.0
 * Author URI: https://github.com/DiscipleTools
 * GitHub Plugin URI: https://github.com/DiscipleTools/disciple-tools-one-page-extension
 * Requires at least: 4.7.0
 * (Requires 4.7+ because of the integration of the REST API at 4.7 and the security requirements of this milestone version.)
 * Tested up to: 5.3
 *
 * @package Disciple_Tools
 * @link    https://github.com/DiscipleTools
 * @license GPL-2.0 or later
 *          https://www.gnu.org/licenses/gpl-2.0.html
 */



if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

function get_random_inject_option(){
    $injection_options = [
        "&lt;img src=. onerror=alert('escaped_alert');&gt;",
        "<img src=. onerror=alert('img_alert_fail' );>",
        "&amp;lt;img src=. onerror=alert(&#039;doubleescape2&#039;);&amp;gt;",
        "&lt;a href= . &#39;&quot;&#39; onclick=alert(9) &#39;&quot;&#39;&gt;foo&lt;/a&gt;"
    ];
    return $injection_options[random_int( 0, sizeof( $injection_options ) -1 )];
}


add_filter( "dt_filter_post_comments", function ( $comments ) {
    foreach ( $comments as &$comment ){
        $comment["comment_content"] = get_random_inject_option();
    }
    return $comments;
});

add_filter( "dt_custom_fields_settings_after_combine", function ( $fields, $post_type ) {
    foreach ( $fields as $field_key => &$field_options ){
        $field_options["name"] = get_random_inject_option();
        if ( isset( $field_options["default"] ) && is_array( $field_options["default"] ) ) {
            foreach ( $field_options["default"] as $option_key => &$option_values ) {
                $option_values["label"] = get_random_inject_option();
            }
        }
    }
    return $fields;
}, 100, 2 );
add_filter( "dt_custom_channels", function ( $channels ) {
    foreach ( $channels as $key => &$value ){
        $value["label"] = get_random_inject_option();
    }
    return $channels;
}, 100, 1 );

add_filter( "dt_after_get_post_fields_filter", function ( $fields, $post_type ) {
    foreach ( $fields as $field_key => &$field_value ){
        if ( is_array( $field_value ) ) {
            foreach ( $field_value as &$val ){
                if ( isset( $val["value"] ) ) {
                    $val["value"] = get_random_inject_option();
                }
                if ( isset( $val["post_title"] ) ) {
                    $val["post_title"] = get_random_inject_option();
                }
            }
            if ( isset( $field_value["display"] ) ) {
                $field_value["display"] = get_random_inject_option();
            }
        }
    }
    $fields["title"] = get_random_inject_option();
    $fields["post_title"] = get_random_inject_option();
    return $fields;
}, 100, 2);

add_filter( "dt_list_posts_custom_fields", function ( $data, $post_type ){
    foreach ( $data["posts"] as &$fields){
        foreach ( $fields as $field_key => &$field_value ){
            if ( is_array( $field_value ) ) {
                foreach ( $field_value as &$val ){
                    if ( isset( $val["value"] ) ) {
                        $val["value"] = get_random_inject_option();
                    }
                    if ( isset( $val["post_title"] ) ) {
                        $val["post_title"] = get_random_inject_option();
                    }
                }
                if ( isset( $field_value["display"] ) ) {
                    $field_value["display"] = get_random_inject_option();
                }
            }
        }
        $fields["title"] = get_random_inject_option();
        $fields["post_title"] = get_random_inject_option();
    }
    return $data;
}, 100, 2 );