<?php

/**
 * @package AuzyTestPlugin
 */
if (!class_exists('Core')) {
    class Core
    {
        function create_table_question_category()
        {
            if (!get_option('table_category', false)) {
                global $wpdb;
                $question_category = $wpdb->prefix . 'question_category';
                if ($wpdb->get_var("SHOW TABLES LIKE '" . $question_category . "'") != $question_category) {

                    $sql  = 'CREATE TABLE `wp_question_category` (
                        `idcateg` int(11) NOT NULL AUTO_INCREMENT,
                        `_name` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
                        `test_eval` varchar(30) CHARACTER SET utf8 NOT NULL,
                        PRIMARY KEY (`idcateg`)
                      ) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1';
                    if (!function_exists('dbDelta')) {
                        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                    }
                    dbDelta($sql);
                    update_option('table_category', true);
                }
            }
        }
        function create_table_question_domaine()
        {
            if (!get_option('table_domaine', false)) {
                global $wpdb;
                $question_domaine = $wpdb->prefix . 'question_domaine';

                if ($wpdb->get_var("SHOW TABLES LIKE '" . $question_domaine . "'") != $question_domaine) {

                    $sql  = 'CREATE TABLE `wp_question_domaine` (
                        `_id_domaine` int(11) NOT NULL AUTO_INCREMENT,
                        `_name_domaine` varchar(150) CHARACTER SET utf8 NOT NULL,
                        PRIMARY KEY (`_id_domaine`)
                      ) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1';
                    if (!function_exists('dbDelta')) {
                        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                    }
                    dbDelta($sql);
                    $this->insert_domain("Comp??tences sociales");
                    $this->insert_domain("Souci du d??tail");
                    $this->insert_domain("Changement d attention");
                    $this->insert_domain("Communication");
                    $this->insert_domain("Imagination");
                    $this->insert_domain("unsigned");
                    update_option('table_domaine', true);
                }
            }
        }
        function create_table_test_info()
        {
            if (!get_option('table_test_info', false)) {
                global $wpdb;
                $test_info = $wpdb->prefix . 'test_info';

                if ($wpdb->get_var("SHOW TABLES LIKE '" . $test_info . "'") != $test_info) {

                    $sql  = 'CREATE TABLE `wp_test_info` (
                            `id_test` int(11) NOT NULL,
                            `first_name` varchar(50) NOT NULL,
                            `last_name` varchar(50) NOT NULL,
                            `child_age` int(11) NOT NULL,
                            `test_date` date NOT NULL,
                            `email` varchar(100) NOT NULL,
                            `test_type` varchar(15) NOT NULL,
                            PRIMARY KEY (`id_test`)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8';
                    if (!function_exists('dbDelta')) {
                        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                    }
                    dbDelta($sql);
                    update_option('table_test_info', true);
                }
            }
        }
        function create_table_test_questions()
        {
            if (!get_option('table_question', false)) {
                global $wpdb;
                $test_questions = $wpdb->prefix . 'test_questions';

                if ($wpdb->get_var("SHOW TABLES LIKE '" . $test_questions . "'") != $test_questions) {

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
                            ON DELETE CASCADE
                            ) ENGINE=InnoDB AUTO_INCREMENT=209 DEFAULT CHARSET=latin1';
                    if (!function_exists('dbDelta')) {
                        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                    }
                    dbDelta($sql);
                    update_option('table_question', true);
                }
            }
        }
        function create_table_test_response()
        {
            if (!get_option('table_response', false)) {
                global $wpdb;
                $test_response = $wpdb->prefix . 'test_response';

                if ($wpdb->get_var("SHOW TABLES LIKE '" . $test_response . "'") != $test_response) {

                    $sql  = 'CREATE TABLE `wp_test_response` (
                            `id` int(11) NOT NULL AUTO_INCREMENT,
                            `question` varchar(250) CHARACTER SET utf8 NOT NULL,
                            `response` varchar(1) NOT NULL,
                            `question_type` varchar(5) NOT NULL,
                            `id_test` int(11) NOT NULL,
                            PRIMARY KEY (`id`),
                            KEY `id_test` (`id_test`),
                            CONSTRAINT `wp_test_response_ibfk_1` 
                                FOREIGN KEY (`id_test`) REFERENCES `wp_test_info` (`id_test`)
                            ) ENGINE=InnoDB AUTO_INCREMENT=1512 DEFAULT CHARSET=latin1';
                    if (!function_exists('dbDelta')) {
                        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                    }
                    dbDelta($sql);
                    update_option('table_response', true);
                }
            }
        }
        function insert_question($question, $id_domain, $type, $categ_id)
        {
            try {
                global $wpdb;
                $table_question = $wpdb->prefix . 'test_questions';
                $question = str_replace('\\', "", $question);
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
        function verify_question_exist($question,$categ_id)
        {
            try {
                global $wpdb;
                $query = "SELECT * 
                        FROM wp_test_questions 
                        JOIN wp_question_category  
                        ON wp_test_questions.id_question_categ=wp_question_category.idcateg
                        WHERE wp_question_category.idcateg =" . $categ_id . "
                        AND wp_test_questions.question ='$question'";
                $result = $wpdb->get_row($query);
                if (empty($result)) return false;
                return true;
            } catch (Exception $e) {
                echo "Message : " . $e->getMessage();
            }
        }

        function verify_question_update($id,$question)
        {
            try {
                global $wpdb;
                $query = "SELECT * 
                        FROM wp_test_questions
                        WHERE wp_test_questions.id ='$id'";
                $result = $wpdb->get_row($query);
                if (($result->question)==$question) return true;
                elseif (($result->question)!=$question) {
                    if ($this->verify_question_exist($question,$result->id_question_categ)) return false;
                    else return true;
                };
                return false;
            } catch (Exception $e) {
                echo "Message : " . $e->getMessage();
            }
        }
        function insert_survey_category($categ_name, $test_eval)
        {
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
        function update_survey_category($idcateg, $category_name, $test_evaluation)
        {
            try {
                global $wpdb;
                $wpdb->show_errors = TRUE;
                $wpdb->update(
                    'wp_question_category',
                    array(
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
        function insert_survey($question, $response,$question_type, $test_id)
        {
            try {
                global $wpdb;
                $table_test_response = $wpdb->prefix . 'test_response';
                $wpdb->insert($table_test_response, array(
                    'question' => $question,
                    'response' => $response,
                    'question_type' => $question_type,
                    'id_test' => $test_id
                ));
                return true;
            } catch (Exception $e) {
                echo "Message : " . $e->getMessage();
            }
        }
        function insert_survey_meta($id_test, $first_name, $last_name, $child_age, $test_date, $email,$test_type)
        {
            try {
                global $wpdb;
                $table_test_info = $wpdb->prefix . 'test_info';
                $wpdb->insert($table_test_info, array(
                    'id_test' => $id_test,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'child_age' => $child_age,
                    'test_date' => $test_date,
                    'email' => $email,
                    'test_type' =>$test_type

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
            $query = "SELECT MAX(id_test) AS test_id 
                    FROM " .  $table_test_info . " ";
            $test_id = $wpdb->get_row($query);
            return $test_id;
        }
        function verif_suervey_id($id)
        {
            global $wpdb;
            $table_test_info = $wpdb->prefix . 'test_info';
            $query = "SELECT id_test 
                    FROM " . $table_test_info . " where id=" . $id;
            $wpdb->get_results($query);
            $rowcount = $wpdb->num_rows;
            return $rowcount;
        }
        function fetch_survey_category()
        {
            global $wpdb;
            $table_category = $wpdb->prefix . 'question_category';
            $query = "SELECT * 
                    FROM " . $table_category . " ";
            $catgories = $wpdb->get_results($query);
            return (array) $catgories;
        }
        function verify_survey_category_exist($name)
        {
            global $wpdb;
            $table_category = $wpdb->prefix . 'question_category';
            $query = "SELECT * 
                    FROM " . $table_category .
                    " WHERE _name = '$name'";
            $categories = $wpdb->get_results($query);
            if (empty($categories)) return false;
            return true;
        }
        function verify_survey_category_update($id,$nameCateg)
        {
            global $wpdb;
            $table_category = $wpdb->prefix . 'question_category';
            $query = "SELECT * 
                    FROM " . $table_category .
                " WHERE idcateg = '$id'";
            $categorie = $wpdb->get_row($query);
            if (($categorie->_name)==$nameCateg) return true;
            elseif (($categorie->_name)!=$nameCateg) {
                if ($this->verify_survey_category_exist($nameCateg)) return false;
                else return true;
            }
            return false;
        }
        function fetch_survey_category_by_id($id_categ)
        {
            global $wpdb;
            $table_category = $wpdb->prefix . 'question_category';
            $query = "SELECT * 
                    FROM " . $table_category .
                " WHERE idcateg = " . $id_categ;
            $category = $wpdb->get_row($query);
            return $category;
        }
        function get_number_count_by_id($id_categ)
        {
            global $wpdb;
            $table_question = $wpdb->prefix . 'test_questions';
            $query = "SELECT COUNT(*) AS countValue
                    FROM " . $table_question .
                " WHERE id_question_categ = " . $id_categ;
            $countValue = $wpdb->get_row($query);
            return $countValue->countValue;
        }
        function fetch_survey_result($test_id)
        {
            global $wpdb;
            $query = "SELECT question, response, question_type 
                    FROM `wp_test_response` 
                    where id_test = " . $test_id . " ";
            $test_response = $wpdb->get_results($query);
            return (array) $test_response;
        }
        function fetch_survey_type($id_test)
        {
            global $wpdb;
            $query = "SELECT test_type AS test_eval
            FROM wp_test_info
            WHERE wp_test_info.id_test = $id_test";
            $type = $wpdb->get_row($query);
            return $type;
        }
        function fetch_survey_meta()
        {
            global $wpdb;
            $table_test_info = $wpdb->prefix . 'test_info';
            $query = "SELECT * 
                    FROM " . $table_test_info . " ";
            $test_info = $wpdb->get_results($query);
            return (array) $test_info;
        }
        function fetch_all_questions()
        {
            global $wpdb;
            $table_question = $wpdb->prefix . 'test_questions';
            $query = "SELECT * 
                    FROM " . $table_question . " ";
            $questions = $wpdb->get_results($query);
            return (array) $questions;
        }
        function fetch_question_by_id($id)
        {
            global $wpdb;
            $query = "SELECT * 
                FROM wp_test_questions 
                JOIN wp_question_category  
                ON wp_test_questions.id_question_categ = wp_question_category.idcateg 
                JOIN wp_question_domaine 
                ON wp_question_domaine._id_domaine = wp_test_questions._id_domain 
                WHERE id = " . $id;
            $question = $wpdb->get_row($query);
            return  $question;
        }

        function fetch_question_txt_by_id($id)
        {
            global $wpdb;
            $query = "SELECT question, _type
                FROM wp_test_questions 
                WHERE id = " . $id;
            $question = $wpdb->get_row($query);
            return  $question;
        }

        function fetch_all_domain()
        {
            global $wpdb;
            $table_domain = $wpdb->prefix . 'question_domaine';
            $query = "SELECT * 
                    FROM " . $table_domain . " ";
            $domain = $wpdb->get_results($query);
            return (array) $domain;
        }
        function insert_domain($name_domain)
        {
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
        function fetch_test_questions($test_id_categ)
        {
            global $wpdb;
            $query = "SELECT id,question,_type,test_eval 
                    FROM wp_test_questions a 
                    JOIN wp_question_category b 
                    on a.id_question_categ=b.idcateg 
                    where b.idcateg = " . $test_id_categ;
            $questions = $wpdb->get_results($query);
            return (array) $questions;
        }
        function update_test_question($id, $question, $_id_domain, $_type, $id_question_categ)
        {
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
        function calculate_AQ_survey_score($test_id)
        {
            $test_response = $this->fetch_survey_result($test_id);
            $score = 0;
            foreach ($test_response as $key) {
                if ($key->question_type == "A") {
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
        function calculate_Mchat_survey_score($test_id)
        {
            $test_response = $this->fetch_survey_result($test_id);
            $score = 0;
            foreach ($test_response as $key) {
                if ($key->question_type == "A") {
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
        function verif_rows_survey_completed($id)
        {
            try {
                global $wpdb;
                $query = "SELECT 
                COUNT(*) AS rowCount
                FROM wp_test_info 
                JOIN wp_test_response 
                ON wp_test_info.id_test = wp_test_response.id_test 
                JOIN wp_test_questions 
                ON wp_test_questions.id = wp_test_response.id_question 
                WHERE wp_test_info.id_test = ".$id;
                $data = $wpdb->get_row($query);
                $rowcount = $data->rowCount;
                return $rowcount;
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
        function export_data()
        {
            try {
                global $wpdb;
                $query = "SELECT 
                wp_test_info.id_test AS id_test,email,first_name,
                last_name,child_age,test_date,test_type,question,response,question_type
                FROM wp_test_info 
                JOIN wp_test_response 
                ON wp_test_info.id_test = wp_test_response.id_test";
                $data = $wpdb->get_results($query);
                if (count($data) > 0) {
                    $fields = array(
                        'ID TEST', 'E-MAIL', 'FIRST_NAME', 'LAST_NAME', 'CHILD_AGE',
                        'TEST_DATE','TEST_TYPE', 'QUESTION', 'QUESTION_TYPE', 'RESPONSE'
                    );
                    $filename = "test_data_" . date('Y-m-d') . ".csv";
                    $f = fopen('php://output', 'w');
                    fprintf($f, chr(0xEF) . chr(0xBB) . chr(0xBF));
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Content-Description: File Transfer');
                    header('Content-Type: text/csv; charset=UTF-8;');
                    header("Content-Disposition: attachment; filename={$filename}");
                    header('Expires: 0');
                    header('Pragma: public');
                    fputcsv($f, $fields);
                    foreach ($data as $row) {
                        $lineData = array(
                            $row->id_test, $row->email, $row->first_name,
                            $row->last_name, $row->child_age, $row->test_date, $row->test_type,
                            $row->question, $row->question_type, $row->response
                        );
                        fputcsv($f, $lineData);
                    }
                    fclose($f);
                    die();
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
        function import_data()
        {
        }
    }
}
