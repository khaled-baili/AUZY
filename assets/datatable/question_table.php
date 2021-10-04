<?php

    require_once 'datatable.php';
    $datatable = new Data_management();

    if(isset($_POST['action']) && !empty($_POST['action'])) {
        $action = $_POST['action'];
        switch($action) {
            case 'fetch_question' : $datatable->fetch_question_data();break;
            case 'insert_question' : $datatable->insert_category_data();break;
            case 'update_question' : $datatable->update_category_data();break;
            case 'delete_question' : $datatable->delete_question();break;

        }
    }
