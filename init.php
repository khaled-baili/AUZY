<?php

/**
 * @package AuzyTestPlugin
 */
/*
Plugin Name: AUZY TESTS
Plugin URI : 
Description :custom plugin for managing auzy tests
Author: auzyTeam
Author URI
Version: 1.0
*/

defined('ABSPATH') or die('you are in the wrong place');
if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}
if (!class_exists('Init')) {
    require_once 'core.php';
    require_once 'frontend.php';
    class Init  extends Frontend
    {

        function __construct()
        {
            add_shortcode('survey', array($this, 'generate_shortcode'));
            add_shortcode('question', function () { require_once 'templates/manage-test-questions.php';});
        }


        function  generate_shortcode($atts)
        {
            Frontend::survey_shortcode($atts['test_id']);
        }

        function actions()
        {

            add_action('wp_enqueue_scripts', array($this, 'enqueue'));
            add_action('admin_enqueue_scripts', array($this, 'enqueue'));
            add_action('admin_menu', array($this, 'add_admin_pages'));
        }


        public function add_admin_pages()
        {
            add_menu_page('Auzy Tests', 'Auzy Tests', 'manage_tests', 'tests-slug', array($this, ''), 'dashicons-menu', 110);
            add_submenu_page('tests-slug', 'Manage Question', 'Manage Question', 'manage_options', 'manage-question-slug', function () {
                require_once 'templates/manage-test-questions.php';
            });
            add_submenu_page('tests-slug', 'Manage Category', 'Manage Category', 'manage_options', 'manage-category-slug', function () {
                require_once 'templates/manage-categories.php';
            });
            // add_submenu_page('tests-slug', 'Question Form', 'Question Form', 'manage_options', 'question-form-slug', function () {
            //     require_once 'templates/aq-form.php';
            // });
            add_submenu_page('tests-slug', 'Consult result', 'Consult result', 'manage_options', 'consult-result-slug', function () {
                require_once 'templates/all-surveys.php';
            });
        }

        function enqueue()
        {
            wp_enqueue_style('myPluginstyle', plugins_url('/assets/style.css', __FILE__));
            wp_enqueue_style('myPlugin_Bootstrap_Style', plugins_url('/assets/bootstrap/css/bootstrap.css', __FILE__));
            wp_enqueue_style('datatable_Bootstrap_Style', plugins_url('/assets/datatable/datatable.min.css', __FILE__));
            wp_enqueue_style('myPlugin_fontAwesome_Style', plugins_url('/assets/fonts/fontawesome/css/all.css', __FILE__));
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-ui-tooltip');
            wp_enqueue_script('myPlugin_Bootstrap_Script', plugins_url('/assets/bootstrap/js/bootstrap.min.js', __FILE__));
            wp_enqueue_script('datatable_js', plugins_url('/assets/datatable/datatable.min.js', __FILE__));
            wp_enqueue_script('myPlugin_Script', plugins_url('/assets/js/script.js', __FILE__));
        }
    }
}
if (class_exists('Init')) {
    $init = new init();
    $init->actions();
}
