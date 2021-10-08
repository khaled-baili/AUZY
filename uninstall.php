<?php
if (! defined('WP_UNINSTALL_PLUGIN')) die;
global $wpdb;
$wpdb->query( "DROP TABLE 
            IF EXISTS 
            wp_test_response,wp_test_questions,wp_question_domaine,wp_question_category,wp_test_info;" );
delete_option('table_category');
delete_option('table_domaine');
delete_option('table_test_info');
delete_option('table_question');
delete_option('table_response');
