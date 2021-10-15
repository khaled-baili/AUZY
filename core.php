<?php

/**
 * @package AuzyTestPlugin
 */
if (!class_exists('Core')) {
    class Core
    {
        function create_table_question_category() {
            if(!get_option('table_category', false)) {
                global $wpdb;
                $question_category = $wpdb->prefix . 'question_category';
                if ($wpdb->get_var("SHOW TABLES LIKE '". $question_category ."'"  ) != $question_category ) {
        
                    $sql  = 'CREATE TABLE `wp_question_category` (
                        `idcateg` int(11) NOT NULL AUTO_INCREMENT,
                        `_name` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
                        `test_eval` varchar(30) CHARACTER SET utf8 NOT NULL,
                        PRIMARY KEY (`idcateg`)
                      ) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1';
                    if(!function_exists('dbDelta')) {
                        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                    }
                    dbDelta($sql);
                    update_option('table_category', true);
                }
            }
        }
        function create_table_question_domaine() {
            if(!get_option('table_domaine', false)) {
                global $wpdb;
                $question_domaine = $wpdb->prefix . 'question_domaine';
        
                if ($wpdb->get_var("SHOW TABLES LIKE '". $question_domaine ."'"  ) != $question_domaine ) {
        
                    $sql  = 'CREATE TABLE `wp_question_domaine` (
                        `_id_domaine` int(11) NOT NULL AUTO_INCREMENT,
                        `_name_domaine` varchar(150) CHARACTER SET utf8 NOT NULL,
                        PRIMARY KEY (`_id_domaine`)
                      ) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1';
                    if(!function_exists('dbDelta')) {
                        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                    }
                    dbDelta($sql);
                    $this->insert_domain("Compétences sociales");
                    $this->insert_domain("Souci du détail");
                    $this->insert_domain("Changement d attention");
                    $this->insert_domain("Communication");
                    $this->insert_domain("Imagination");
                    $this->insert_domain("unsigned");
                    update_option('table_domaine', true);
                }
            }
        }
        function create_table_test_info() {
            if(!get_option('table_test_info', false)) {
                global $wpdb;
                $test_info = $wpdb->prefix . 'test_info';
        
                if ($wpdb->get_var("SHOW TABLES LIKE '". $test_info ."'"  ) != $test_info ) {
        
                    $sql  = 'CREATE TABLE `wp_test_info` (
                        `id_test` int(11) NOT NULL,
                        `first_name` varchar(50) NOT NULL,
                        `last_name` varchar(50) NOT NULL,
                        `test_date` date NOT NULL,
                        `email` varchar(100) NOT NULL,
                        PRIMARY KEY (`id_test`)
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8';
                    if(!function_exists('dbDelta')) {
                        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                    }
                    dbDelta($sql);
                    update_option('table_test_info', true);
                }
            }
        }
        function create_table_test_questions() {
            if(!get_option('table_question', false)) {
                global $wpdb;
                $test_questions = $wpdb->prefix . 'test_questions';
        
                if ($wpdb->get_var("SHOW TABLES LIKE '". $test_questions ."'"  ) != $test_questions ) {
        
                    $sql  = 'CREATE TABLE `wp_test_questions` (
                        `id` int(8) NOT NULL AUTO_INCREMENT,
                        `question` varchar(250) CHARACTER SET utf8 NOT NULL,
                        `_id_domain` int(11) NOT NULL,
                        `_type` char(1) NOT NULL,
                        `id_question_categ` int(11) NOT NULL,
                        PRIMARY KEY (`id`),
                        KEY `id_question_categ` (`id_question_categ`),
                        CONSTRAINT `id_question_categ` 
                        FOREIGN KEY (`id_question_categ`) 
                        REFERENCES `wp_question_category` (`idcateg`)
                      ) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=latin1';
                    if(!function_exists('dbDelta')) {
                        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                    }
                    dbDelta($sql);
                    update_option('table_question', true);
                }
            }
        }
        function create_table_test_response() {
            if(!get_option('table_response', false)) {
                global $wpdb;
                $test_response = $wpdb->prefix . 'test_response';
        
                if ($wpdb->get_var("SHOW TABLES LIKE '". $test_response ."'"  ) != $test_response ) {
        
                    $sql  = 'CREATE TABLE `wp_test_response` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `id_question` int(11) NOT NULL,
                        `response` varchar(1) NOT NULL,
                        `id_test` int(11) NOT NULL,
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB AUTO_INCREMENT=215 DEFAULT CHARSET=latin1';
                    if(!function_exists('dbDelta')) {
                        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                    }
                    dbDelta($sql);
                    update_option('table_response', true);
                }
            }
        }
        function insert_question($question, $id_domain, $type, $categ_id) {
            try {
                global $wpdb;
                $table_question = $wpdb->prefix . 'test_questions';
                $wpdb->insert($table_question, array(
                    'question' => $question,
                    '_id_domain' => $id_domain,
                    '_type' => $type,
                    'id_question_categ' => $categ_id
                ));
                return true;
            } catch (Exception $e) {
                echo "Message : " . $e->getMessage();
            }
        }
        function insert_survey_category($categ_name, $test_eval) {
            try {
                global $wpdb;
                $table_category = $wpdb->prefix . 'question_category';
                $query = "SELECT * 
                        FROM " . $table_category .
                    " WHERE name = " . $categ_name . " ";
                $results = $wpdb->get_results($query);
                if ((count((array)$results)) == 0) {
                    $wpdb->insert($table_category, array(
                        '_name' => $categ_name,
                        'test_eval' => $test_eval
                    ));
                    return true;
                } else return false;
            } catch (Exception $e) {
                echo "Message : " . $e->getMessage();
            }
        }
        function update_survey_category($idcateg, $category_name, $test_evaluation) {
            try {
                global $wpdb;
                $wpdb->show_errors = TRUE;
                $wpdb->update('wp_question_category', array(
                        '_name' => $category_name,
                        'test_eval' => $test_evaluation
                    ),
                    array('idcateg' => $idcateg)
                );
                $wpdb->print_error();
                return true;
            } catch (Exception $e) {
                echo "Message : " . $e->getMessage();
            }
        }
        function insert_survey($id_question, $response, $test_id) {
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
        function insert_survey_meta($id_test, $first_name, $last_name,$child_age ,$email) {
            try {
                global $wpdb;
                $table_test_info = $wpdb->prefix . 'test_info';
                $wpdb->insert($table_test_info, array(
                    'id_test' => $id_test,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'child_age' => $child_age,
                    'test_date' => date("Y/m/d"),
                    'email' => $email
                ));
                return true;
            } catch (Exception $e) {
                echo "Message : " . $e->getMessage();
            }
        }

        function get_suervey_max_id() {
            global $wpdb;
            $table_test_info = $wpdb->prefix . 'test_info';
            $query = "SELECT MAX(id_test) AS test_id 
                    FROM " .  $table_test_info . " ";
            $test_id = $wpdb->get_row($query);
            return $test_id;
        }
        function fetch_survey_category() {
            global $wpdb;
            $table_category = $wpdb->prefix . 'question_category';
            $query = "SELECT * 
                    FROM " . $table_category . " ";
            $catgories = $wpdb->get_results($query);
            return (array) $catgories;
        }
        function fetch_survey_category_by_id($id_categ) {
            global $wpdb;
            $table_category = $wpdb->prefix . 'question_category';
            $query = "SELECT * 
                    FROM " . $table_category .
                " WHERE idcateg = " . $id_categ;
            $catgory = $wpdb->get_row($query);
            return $catgory;
        }
        function fetch_survey_result($test_id) {
            global $wpdb;
            $table_test_response = $wpdb->prefix . 'test_response';
            $query = "SELECT question,response,_type 
                    FROM `wp_test_response` 
                    JOIN wp_test_questions 
                    on wp_test_response.id_question=wp_test_questions.id 
                    where id_test = " . $test_id . " ";
            $test_response = $wpdb->get_results($query);
            return (array) $test_response;
        }
        function fetch_survey_type($id_test) {
            global $wpdb;
            $query = "SELECT test_eval AS test_eval
            FROM wp_test_info
            JOIN wp_test_response
            ON wp_test_response.id_test=wp_test_info.id_test
            JOIN wp_test_questions
            ON wp_test_questions.id = wp_test_response.id_question
            JOIN wp_question_category
            ON wp_question_category.idcateg=wp_test_questions.id_question_categ
            WHERE wp_test_info.id_test = $id_test
            LIMIT 1";
            $type = $wpdb->get_row($query);
            return $type;

        }
        function fetch_survey_meta() {
            global $wpdb;
            $table_test_info = $wpdb->prefix . 'test_info';
            $query = "SELECT * 
                    FROM " . $table_test_info . " ";
            $test_info = $wpdb->get_results($query);
            return (array) $test_info;
        }
        function fetch_all_questions() {
            global $wpdb;
            $table_question = $table_question = $wpdb->prefix . 'test_questions';
            $query = "SELECT * 
                    FROM " . $table_question . " ";
            $questions = $wpdb->get_results($query);
            return (array) $questions;
        }
        function fetch_question_by_id($id) {
            global $wpdb;
            $query = "SELECT * 
                FROM wp_test_questions 
                JOIN wp_question_category  
                ON wp_test_questions.id_question_categ = wp_question_category.idcateg 
                JOIN wp_question_domaine 
                ON wp_question_domaine._id_domaine = wp_test_questions._id_domain 
                WHERE id = " . $id;
            $questions = $wpdb->get_row($query);
            return  $questions;
        }
        function fetch_all_domain() {
            global $wpdb;
            $table_domain = $wpdb->prefix . 'question_domaine';
            $query = "SELECT * 
                    FROM " . $table_domain . " ";
            $domain = $wpdb->get_results($query);
            return (array) $domain;
        }
        function insert_domain($name_domain) {
            try {
                global $wpdb;
                $table_domain = $wpdb->prefix . 'question_domaine';
                $wpdb->insert($table_domain, array(
                    '_name_domaine' => $name_domain
                ));
                return true;
            } catch (Exception $e) {
                echo "Message : " . $e->getMessage();
            }
        }
        function fetch_test_questions($test_id_categ) {
            global $wpdb;
            $query = "SELECT id,question,_type,test_eval 
                    FROM wp_test_questions a 
                    JOIN wp_question_category b 
                    on a.id_question_categ=b.idcateg 
                    where b.idcateg = " . $test_id_categ;
            $questions = $wpdb->get_results($query);
            return (array) $questions;
        }
        function update_test_question($id, $question, $_id_domain, $_type, $id_question_categ) {
            try {
                global $wpdb;
                $wpdb->update(
                    'wp_test_questions',
                    array(
                        'question' => $question,
                        '_id_domain' => $_id_domain,
                        '_type' => $_type,
                        'id_question_categ' => $id_question_categ
                    ),
                    array('id' => $id)
                );
                return true;
            } catch (Exception $e) {
                echo "Message : " . $e->getMessage();
            }
        }
        function calculate_AQ_survey_score($test_id) {
            global $wpdb;
            $query = "SELECT response,_type 
                    FROM wp_test_response 
                    JOIN wp_test_questions 
                    ON wp_test_response.id_question=wp_test_questions.id 
                    WHERE id_test =" . $test_id . " ";
            $test_response = $wpdb->get_results($query);
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
        function calculate_Mchat_survey_score($test_id) {
            global $wpdb;
            $query = "SELECT response,_type 
                    FROM wp_test_response 
                    JOIN wp_test_questions 
                    ON wp_test_response.id_question=wp_test_questions.id 
                    WHERE id_test =" . $test_id . " ";
            $test_response = $wpdb->get_results($query);
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
                        default:
                            $score = $score + 0;
                    }
                } else {
                    switch ($key->response) {
                        case "A":
                            $score = $score + 1;
                            break;
                        case "B":
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
