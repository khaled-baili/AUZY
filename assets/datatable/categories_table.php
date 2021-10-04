<?php

    require_once 'datatable.php';
    $datatable = new Data_management();

    if(isset($_POST['action']) && !empty($_POST['action'])) {
        $action = $_POST['action'];
        switch($action) {
            case 'fetch_categ' : $datatable->fetch_category_data();break;
            case 'insert_categ' : $datatable->insert_category_data();break;
            case 'update_categ' : $datatable->update_category_data();break;
            case 'delete_categ' : $datatable->delete_category();break;

        }
    }
