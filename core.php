<?php

/**
 * @package AuzyTestPlugin
 */

if (!class_exists('Core')) {
    class Core
    {

        function insert_question($question, $id_domain, $type, $categ_id)
        {
            try {
                global $wpdb;
                $table_question = $wpdb->prefix . 'test_questions';
                $wpdb->insert($table_question, array(
                    'question' => $question, '_id_domain' => $id_domain , '_type' => $type, 'id_question_categ' => $categ_id
                ));
                return true;
            } catch (Exception $e) {
                echo "Message : " . $e->getMessage();
            }
        }


        function insert_survey_category($categ_name, $test_eval)
        {
            try {
                global $wpdb;
                $table_category = $wpdb->prefix . 'question_category';
                $results = $wpdb->get_results("SELECT * FROM " . $table_category . " WHERE name = " . $categ_name . " ");
                if ((count((array)$results)) == 0) {
                    $wpdb->insert($table_category, array(
                        '_name' => $categ_name,
                        'test_eval' => $$test_eval
                    ));
                    return true;
                } else {
                    return false;
                }
            } catch (Exception $e) {
                echo "Message : " . $e->getMessage();
            }
        }

        function update_survey_category($idcateg, $category_name, $test_evaluation)
        {
            try {
                global $wpdb;
                $wpdb->show_errors = TRUE;
                $wpdb->update('wp_question_category', array('_name' => $category_name, 'test_eval' => $test_evaluation), array('idcateg' => $idcateg));
                $wpdb->print_error();
                return true;
            } catch (Exception $e) {
                echo "Message : " . $e->getMessage();
            }
        }


        function insert_survey($id_question, $response, $test_id)
        {
            try {
                global $wpdb;
                $table_test_response = $wpdb->prefix . 'test_response';
                $wpdb->insert($table_test_response, array(
                    'id_question' => $id_question, 'response' => $response, 'id_test' => $test_id
                ));
                return true;
            } catch (Exception $e) {
                echo "Message : " . $e->getMessage();
            }
        }


        function insert_survey_meta($id_test, $first_name, $last_name, $email)
        {
            try {
                global $wpdb;
                $table_test_info = $wpdb->prefix . 'test_info';
                $wpdb->insert($table_test_info, array(
                    'id_test' => $id_test, 'first_name' => $first_name, 'last_name' => $last_name, 'test_date' => date("Y/m/d"), 'email' => $email
                ));
                return true;
            } catch (Exception $e) {
                echo "Message : " . $e->getMessage();
            }
        }

        function get_suervey_max_id()
        {
            global $wpdb;
            $table_test_info = $wpdb->prefix . 'test_info';
            $test_id = $wpdb->get_row("SELECT MAX(id_test) AS test_id FROM " .  $table_test_info . " ");
            return $test_id;
        }

        function fetch_survey_category()
        {
            global $wpdb;
            $table_category = $wpdb->prefix . 'question_category';
            $catgories = $wpdb->get_results("SELECT * FROM " . $table_category . " ");
            return (array) $catgories;
        }

        function fetch_survey_category_by_id($id_categ)
        {
            global $wpdb;
            $table_category = $wpdb->prefix . 'question_category';
            $catgory = $wpdb->get_row("SELECT * FROM " . $table_category . " WHERE idcateg = " . $id_categ);
            return $catgory;
        }


        function fetch_survey_result($test_id)
        {
            global $wpdb;
            $table_test_response = $wpdb->prefix . 'test_response';
            $test_response = $wpdb->get_results("SELECT question,response,_type FROM `wp_test_response` JOIN wp_test_questions on wp_test_response.id_question=wp_test_questions.id where id_test = " . $test_id . " ");
            return (array) $test_response;
        }

        function fetch_survey_meta()
        {
            global $wpdb;
            $table_test_info = $wpdb->prefix . 'test_info';
            $test_info = $wpdb->get_results("SELECT * FROM " . $table_test_info . " ");
            return (array) $test_info;
        }


        function fetch_all_questions()
        {
            global $wpdb;
            $table_question = $table_question = $wpdb->prefix . 'test_questions';
            $questions = $wpdb->get_results("SELECT * FROM " . $table_question . " ");
            return (array) $questions;
        }

        function fetch_question_by_id($id) {
            global $wpdb;
            $questions = $wpdb->get_row("SELECT * FROM wp_test_questions join wp_question_category  on wp_test_questions.id_question_categ = wp_question_category.idcateg join wp_question_domaine on wp_question_domaine._id_domaine = wp_test_questions._id_domain where id = ". $id);
            return  $questions;
        
        }

        function fetch_all_domain()
        {
            global $wpdb;
            $table_domain = $table_question = $wpdb->prefix . 'question_domaine';
            $domain = $wpdb->get_results("SELECT * FROM " . $table_domain . " ");
            return (array) $domain;
        }

        function fetch_test_questions($test_id_categ)
        {
            global $wpdb;
            $table_question = $wpdb->prefix . ' test_questions ';
            $table_test_category = $wpdb->prefix . 'test_question_category';
            $questions = $wpdb->get_results("SELECT id,question,_type,test_eval FROM wp_test_questions a JOIN wp_question_category b on a.id_question_categ=b.idcateg where b.idcateg = " . $test_id_categ);
            return (array) $questions;
        }

        function update_test_question($id, $question, $_id_domain, $_type, $id_question_categ)
        {
            try {
                global $wpdb;
                // $wpdb->show_errors = TRUE;
                $wpdb->update('wp_test_questions', array('question' => $question, '_id_domain' => $_id_domain, '_type' => $_type, 'id_question_categ' => $id_question_categ), array('id' => $id));
                // $wpdb->print_error();
                return true;
            } catch (Exception $e) {
                echo "Message : " . $e->getMessage();
            }
        }


        function calculate_survey_score($test_id)
        {
            global $wpdb;
            $table_question = $wpdb->prefix . 'test_questions';
            $table_test_response = $wpdb->prefix . 'test_response';
            $test_response = $wpdb->get_results("SELECT response,_type FROM `wp_test_response` JOIN wp_test_questions on wp_test_response.id_question=wp_test_questions.id where id_test =" . $test_id . " ");
            $score = 0;
            foreach ($test_response as $key) {
                if ($key->_type == "A") {
                    switch ($key->response) {
                        case "A":
                            $score = $score + 0;
                            break;
                        case "B":
                            $score = $score + 1;
                            break;
                        case "C":
                            $score = $score + 2;
                            break;
                        case "D":
                            $score = $score + 3;
                            break;
                        default:
                            $score = $score + 0;
                    }
                } else {
                    switch ($key->response) {
                        case "A":
                            $score = $score + 3;
                            break;
                        case "B":
                            $score = $score + 2;
                            break;
                        case "C":
                            $score = $score + 1;
                            break;
                        case "D":
                            $score = $score + 0;
                            break;
                        default:
                            $score = $score + 0;
                    }
                }
            }

            return $score;
        }
    }
}
