<?php


/**
 * @package AuzyTestPlugin
 */

 namespace inc\base;

 class BaseController {
     public $plugin_path;
     public $plugin_url;
     public $plugin;

     public function __construct()
     {
         $this->plugin_path = plugin_dir_path( dirname(__FILE__, 2) );
         $this->plugin_path = plugin_dir_url( dirname(__FILE__, 2) );
         $this->plugin_path = plugin_base( dirname(__FILE__, 3) ).'/init.php';

     }
 }