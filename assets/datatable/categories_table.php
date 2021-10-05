<?php

    require_once 'datatable.php';
    $datatable = new Data_management();
    if(isset($_POST['function']) && !empty($_POST['function'])) {
        $action = $_POST['function'];
        switch($action) {
            case 'fetch_categ' : $datatable->fetch_category_data();break;
            case 'fetch_categ_by_id' : $datatable->fetch_category_by_id();break;
            case 'insert_categ' : $datatable->insert_category_data();break;
            case 'update_categ' : $datatable->update_category_data();break;
            case 'delete_categ' : $datatable->delete_category();break;

        }
    }
