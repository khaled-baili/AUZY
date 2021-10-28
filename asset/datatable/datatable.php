<?php

/**
 * @package AuzyTestPlugin
 */
require_once '../../core.php';
require_once('../../../../../wp-config.php');
if (!class_exists('Data_management')) {
    class Data_management extends Core {
        function fetch_category_data() {
            global $wpdb;
            $columns = array('idcateg', 'name', 'test_eval');
            $query = "SELECT * FROM wp_question_category ";

            if (isset($_POST["search"]["value"])) {
                $query .= 'WHERE _name LIKE "%' . $_POST["search"]["value"] . '%" ';
            }
            if (isset($_POST["order"])) {
                $query .='ORDER BY '.$columns[$_POST['order']['0']['column']].' '
                .$_POST['order']['0']['dir'].' ';
            } else {
                $query .= 'ORDER BY idcateg DESC ';
            }
            $query1 = '';

            if ($_POST["length"] != -1) {
                $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
            }
            $results = $wpdb->get_results($query);
            $number_filter_row = $wpdb->num_rows;
            $categories = $wpdb->get_results($query . $query1);;
            $data = array();
            foreach ($categories as $row) {
                $sub_array = array();
                $sub_array[]='<div class="update_categ" data-id="'.$row->idcateg.'
                            " data-column="_name">'.$row->_name.'</div>';
                $sub_array[]='<div class="update_categ" data-id="'.$row->idcateg .'
                            " data-column="_name">'.$row->test_eval . '</div>';
                $sub_array[]='<button type="button" name="update" id="'.$row->idcateg.'
                            " class="btn btn-warning btn-xs update">
                            <i class="fas fa-pencil-alt"></i></button>
                            <button type="button" name="delete" class="btn btn-danger btn-xs delete_categ" 
                            id="'.$row->idcateg.'">
                            <i class="fas fa-trash-alt"></i></button>';
                $sub_array[] = '[survey test_id="'.$row->idcateg.'"]';
                $data[] = $sub_array;
            }
            function get_all_data()
            {
                $result = Core::fetch_survey_category();
                return count((array)$result);
            }
            $output = array(
                "draw"    => intval($_POST["draw"]),
                "recordsTotal"  =>  get_all_data(),
                "recordsFiltered" => $number_filter_row,
                "data"    => $data
            );
            echo json_encode($output, JSON_UNESCAPED_UNICODE);
        }
        function fetch_category_by_id() {
            if (isset($_POST['idcateg'])) {
                $data = Core::fetch_survey_category_by_id($_POST['idcateg']);
                echo json_encode($data);
            }else {
                return false ;
            }
        }
        function insert_category_data()
        {
            if (isset($_POST['categ_name'], $_POST['test_eval'])) 
            Core::insert_survey_category($_POST['categ_name'],$_POST['test_eval']);
            else return false;
        }
        function update_category_data()
        {
            // if(isset($_POST["id"]))
            // {
            //     Core::update_survey_category($idcateg, $category_name, $test_evaluation);
            // }
        }
        function delete_category()
        {
            global $wpdb;
            if (isset($_POST["id"])) {
                $table = $wpdb->prefix . 'question_category';
                $wpdb->delete( $table, array( 'idcateg' => $_POST["id"] ) );
            }
        }



        function fetch_question_data()
        {
            global $wpdb;
            $columns = array('question', 'type','category');
            $query = "SELECT * 
                    FROM wp_test_questions 
                    JOIN wp_question_category  
                    ON wp_test_questions.id_question_categ = wp_question_category.idcateg 
                    JOIN wp_question_domaine 
                    ON wp_question_domaine._id_domaine = wp_test_questions._id_domain ";
            if(isset($_POST["search"]["value"]))
            {
             $query .= ' 
             WHERE question LIKE "%'.$_POST["search"]["value"].'%" 
             OR _type LIKE "%'.$_POST["search"]["value"].'%" 
             OR _name like "%'.$_POST["search"]["value"].'%"
             OR _name_domaine like "%'.$_POST["search"]["value"].'%"
            
             ';
            }
            
            if(isset($_POST["order"]))
            {
             $query .= 'ORDER BY '.$columns[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' 
             ';
            }
            else
            {
             $query .= 'ORDER BY id DESC ';
            }
            
            $query1 = '';
            
            if($_POST["length"] != -1)
            {
             $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
            }
            
            
            $results = $wpdb->get_results($query);
            $number_filter_row = $wpdb->num_rows;
            
            $data = array();
            $questions = $wpdb->get_results($query . $query1);
            
            foreach ($questions as $row) {
                $sub_array = array();
                $quest_type = '';
                if ($row->_type=='A') {
                    $quest_type="ASC" ;
                } else {
                    $quest_type="DESC" ;
                }
                $sub_array[]='<div contenteditable class="update" data-id="'.$row->id.'" 
                             data-column="question">' .$row->question . '</div>';
                $sub_array[]='<div class="update" data-id="'.$row->id.
                               '" data-column="type">' .$quest_type. '</div>';
                $sub_array[]='<div class="update" data-id="'.$row->idcateg.
                             '" data-column="category">' .$row->_name. '</div>';
                $sub_array[]='<div class="update" data-id="'.$row->_id_domain.
                             '" data-column="domaine">' .$row->_name_domaine. '</div>';
                $sub_array[]='<div><button type="button" name="update" id="'.$row->id.
                             '"class="btn btn-warning btn-xs update"><i class="fas fa-pencil-alt"></i>
                             </button>
                             <button type="button" name="delete" class="btn btn-danger btn-xs delete" 
                             id="'.$row->id.'"><i class="fas fa-trash-alt"></i></button>';
                $data[] = $sub_array;
                
             }
             function get_all_data() {
                $result = Core::fetch_all_questions();
                return count((array)$result);
             }
            
             $output = array(
                "draw"    => intval($_POST["draw"]),
                "recordsTotal"  =>  get_all_data(),
                "recordsFiltered" =>$number_filter_row,
                "data"    => $data
               );
               echo json_encode($output,JSON_UNESCAPED_UNICODE);
        }
        function fetch_quest_by_id() {
            if (isset($_POST['id'])) {
                $data = Core::fetch_question_by_id($_POST['id']);
                echo json_encode($data);
            }else {
                return false;
            }
        }
        function insert_question_data()
        {
            
        }
        function delete_question()
        {
            global $wpdb;
            if (isset($_POST["id"])) {
                $table=$wpdb->prefix . 'test_questions';
                $wpdb->delete( $table, array( 'id' => $_POST["id"] ) );
            }
        }
    }
}

