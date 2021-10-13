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
            global $wpdb;
            $quiz_data = array();
            $test_id =  Core::get_suervey_max_id();
            $id_test = $test_id->test_id + 1;
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $email = $_POST['email'];
            $test_insertion_meta = Core::insert_survey_meta($id_test, $first_name, $last_name, $email);
            if ($test_insertion_meta == false) {
                echo '<script>confirm("personnel data does not saved contact support")</script>';
            };
            foreach ($_POST['response'] as $question) {
                $object = new stdClass();
                $object->id_question = $question["name"];
                $object->response = $question["value"];
                $quiz_data[] = $object;
            };
            foreach ($quiz_data as $key) {
                Core::insert_survey($key->id_question, $key->response, $id_test);
            };

            // if ($test_evaluation_type == "AQ") $score = Core::calculate_AQ_survey_score($id_test);
            // else $score=Core::calculate_Mchat_survey_score($id_test);
            // echo '<script>alert("test passed successfully")</script>';
            // echo '<center><h1>Your test score is : ' . $score . '</h1></center>';
        }
    }
}


$survey = new Survey();
try {
    $survey->insert_survey_resp();
} catch (Exception $e) {
    echo $e->getMessage();
}


