<?php

    require_once 'datatable.php';
    $datatable = new Data_management();

    if(isset($_POST['function']) && !empty($_POST['function'])) {
        $action = $_POST['function'];
        switch($action) {
            case 'fetch_question' : $datatable->fetch_question_data();break;
            case 'fetch_question_by_id' : $datatable->fetch_quest_by_id();break;
            case 'insert_question' : $datatable->insert_category_data();break;
            case 'update_question' : $datatable->update_category_data();break;
            case 'delete_question' : $datatable->delete_question();break;

        }
    }
