<?php

/**
 * @package AuzyTestPlugin
 */
 require_once '../../core.php';
 require_once('../../../../../wp-config.php');
if (!class_exists('Survey')) {
    class Survey extends Core
    {
        function insert_survey_resp() {
            $quiz_data = array();
            $test_id =  Core::get_suervey_max_id();
            $id_test = $test_id->test_id + 1;
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $email = $_POST['email'];
            $child_age = $_POST['child_age'];
            $test_evaluation_type = $_POST['test_evaluation'];
            $test_insertion_meta = Core::insert_survey_meta(
                $id_test, $first_name, $last_name,$child_age,date("Y/m/d"),$email,$test_evaluation_type);
            if ($test_insertion_meta == false) {
                echo '<script>confirm("personnel data does not saved contact support")</script>';
                die();
            };
            foreach ($_POST['response'] as $question) {
                $object = new stdClass();
                $question_data = Core::fetch_question_txt_by_id( $question["name"]);
                $object->question = $question_data->question;
                $object->question_type = $question_data->_type;
                $object->response = $question["value"];
                $quiz_data[] = $object;
            };
            foreach ($quiz_data as $key) {
                Core::insert_survey($key->question, $key->response, $key->question_type , $id_test);
            };
            if ($test_evaluation_type == "AQ") $score = Core::calculate_AQ_survey_score($id_test);
            else $score=Core::calculate_Mchat_survey_score($id_test);
            echo $score;
        }
    }
}

$survey = new Survey();
try {
    if ($_POST["action"]=="export_data") {
        $survey->export_data();
    } else {
        $survey->insert_survey_resp();
    }
} catch (Exception $e) {
    echo $e->getMessage();
}


