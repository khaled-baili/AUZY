<?php

/**
 * @package AuzyTestPlugin
 */

if (!class_exists('Frontend')) {
    class Frontend extends Core
    {
        public function __construct()
        {
        }

        public function show_all_surveys()
        {
            echo '
        <div class="row">
            <table id="test-table" class="table table-striped table-bordered nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>E-mail</th>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Test date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>';
            $test_meta = Core::fetch_survey_meta();
            try {
                foreach ($test_meta as $result) {
                    echo '
                                <tr>
                                <td>' . $result->email . '</td>
                                <td>' . $result->first_name . '</td>
                                <td>' . $result->last_name . '</td>
                                <td>' . $result->test_date . '</td>
                                <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#testDetails_' . $result->id_test . '">Details</button></td>
                                </tr>';
                }
            } catch (Exception $e) {
                echo $e;
            }

            echo '
                </tbody>
            </table>
        </div>';
            foreach ($test_meta as $result) {
                $data = Core::fetch_survey_result($result->id_test);
                echo '
            <div class="modal fade" id="testDetails_' . $result->id_test . '" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Test Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Question</th>
                                    <th scope="col">Response</th>
                                </tr>
                            </thead>
                            <tbody>';
                $score = Core::calculate_survey_score($result->id_test);
                foreach ($data as $key) {
                    $response = '';
                    switch ($key->response) {
                        case "A":
                            $response = 'tout à fait d\'accord';
                            break;
                        case "B":
                            $response = 'plutôt d\'accord';
                            break;
                        case "C":
                            $response = 'plutôt pas d\'accord';
                            break;
                        case "D":
                            $response = 'pas du tout d\'accord';
                            break;
                        default:
                            $response = '';
                    }
                    echo '<tr>
                    <th scope="row">1</th>
                    <td>' . $key->question . '</td>
                    <td>' . $response . '</td>
                    </tr>';
                }
                echo '</tbody></table>
                    <center><h1>Your test score is : ' . $score . '</h1></center>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                     <button type="button" class="btn btn-primary">Imprimer</button>
                    </div>
                    </div>
                    </div>
                    </div>';
            }
        }





        function show_all_categories()
        {
            echo '<div class="row table-title">
        <div class="col-sm-6">
            <h2>Manage Category</h2>
        </div>
        <div class="col-sm-6">
            <div class="functional-btn">
                <button type="button" name="add_categ" id="add_categ" class="btn btn-info">Add category</button>
            </div>
        </div>
    </div>
    <div class="row">
        <table id="category_table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Test evaluation</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>';
        }






        function show_all_questions()
        {
            echo '<div class="row table-title">
        <div class="col-sm-6">
            <h2>Questions</h2>
        </div>
        <div class="col-sm-6">
            <div class="functional-btn">
                <button type="button" name="add" id="add" class="btn btn-info">Add question</button>
            </div>
        </div>
    </div>
    <div class="row">
        <table id="question_table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Type</th>
                    <th>Category</th>
                    <th>domain</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>';
        }






        function test_AQ_form()
        {
            echo '<div class="row">
            <form action="" method="post">
                <div class="row form-inscription">
                    <fieldset class="border p-3">
                        <legend class="w-auto">Personal information</legend>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" name="first_name" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" name="last_name" required>
                        </div>
                    </fieldset>

                </div>';
            $output = '<table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Question</th>
                            <th scope="col">tout à fait d\'accord</th>
                            <th scope="col">plutôt d\'accord</th>
                            <th scope="col">plutôt pas d\'accord</th>
                            <th scope="col">pas du tout d\'accord</th>
                        </tr>
                    </thead>
                    <tbody>';
            $test_evaluation = Core::fetch_survey_category_by_id(8);
            $test_evaluation_type = $test_evaluation->test_eval;
            $results = Core::fetch_test_questions(8);
            if ($test_evaluation_type == "AQ" && !empty($results)) {
                $index = 1;
                foreach ($results as $result) {
                    $output .= '<tr>
                                <td>' . $index++ . '</td>
                             <td>' . $result->question . '</td>
                             <td>
                             <div class="form-check form-check-inline">
                             <input class="form-check-input" type="radio" name="quest_response_' . $result->id . '" value="A" ';
                    if (isset($_POST['quest_response_' . $result->id . '']) && $_POST['quest_response_' . $result->id . ''] == 'A') {
                        $output .= 'checked';
                    }
                    $output .= ' required> 
                           </div>
                           </td>
                           <td>
                           <div class="form-check form-check-inline">
                             <input class="form-check-input" type="radio" name="quest_response_' . $result->id . '" value="B" ';
                    if (isset($_POST['quest_response_' . $result->id . '']) && $_POST['quest_response_' . $result->id . ''] == 'B') {
                        $output .= 'checked';
                    }

                    $output .= ' >
                           </div>
                           </td>
                           <td>
                           <div class="form-check form-check-inline">
                             <input class="form-check-input" type="radio" name="quest_response_' . $result->id . '" value="C" ';
                    if (isset($_POST['quest_response_' . $result->id . '']) && $_POST['quest_response_' . $result->id . ''] == 'C') {
                        $output .= 'checked';
                    }
                    $output .= ' >
                           </div>
                           </td>
                           <td>
                           <div class="form-check form-check-inline">
                           <input class="form-check-input" type="radio" name="quest_response_' . $result->id . '" value="D" ';
                    if (isset($_POST['quest_response_' . $result->id . '']) && $_POST['quest_response_' . $result->id . ''] == 'D') {
                        $output .= 'checked';
                    }
                    $output .= '>
                         </div>
                           </td>
                         </tr>';
                }
                $output .= '
                        </tbody>
                    </table>';
                echo $output;

                echo '
                    <div class="row btn-submit">
                        <input class="btn btn-primary" name="submit" type="submit" value="Submit">
                    </div>
                </form>
            </div>';
            } else if ($test_evaluation_type == "Mchat" && !empty($results)) {
                $index = 1;
                foreach ($results as $result) {
                    $output .= '<tr>
                                <td>' . $index++ . '</td>
                             <td>' . $result->question . '</td>
                             <td>
                             <div class="form-check form-check-inline">
                             <input class="form-check-input" type="radio" name="quest_response_' . $result->id . '" value="A" ';
                    if (isset($_POST['quest_response_' . $result->id . '']) && $_POST['quest_response_' . $result->id . ''] == 'A') {
                        $output .= 'checked';
                    }
                    $output .= 'required> 
                           </div>
                           </td>
                           <td>
                           <div class="form-check form-check-inline">
                             <input class="form-check-input" type="radio" name="quest_response_' . $result->id . '" value="B" ';
                    if (isset($_POST['quest_response_' . $result->id . '']) && $_POST['quest_response_' . $result->id . ''] == 'B') {
                        $output .= 'checked';
                    }
                    $output .= '>
                           </div>
                           </td>';
                    $output .= '
                           </div>
                           </td>';
                    $output .= '
                         </div>
                           </td>
                         </tr>';
                }
                $output .= '
                        </tbody>
                    </table>';
                echo $output;

                echo '
                    <div class="row btn-submit">
                        <input class="btn btn-primary" name="submit" type="submit" value="Submit">
                    </div>
                </form>
            </div>';
            } else {
                echo '<script>confirm("Sorry your entered id test doesnt match with any test evaluation")</script>';
            }


            try {
                if (isset($_POST['submit'], $_POST['first_name'], $_POST['last_name'], $_POST['email'])) {
                    $quiz_data = array();
                    $test_id =  Core::get_suervey_max_id();
                    $id_test = $test_id->test_id + 1;
                    $first_name = $_POST['first_name'];
                    $last_name = $_POST['last_name'];
                    $email = $_POST['email'];
                    $test_insertion_meta = Core::insert_survey_meta($id_test, $first_name, $last_name, $email);
                    if ($test_insertion_meta == false) {
                        echo '<script>confirm("personnel data does not saved")</script>';
                    };
                    foreach ($results as $question) {
                        $object = new stdClass();
                        if (isset($_POST['quest_response_' . $question->id . ''])) {
                            $object->id_question = $question->id;
                            $object->response  = $_POST['quest_response_' . $question->id . ''];
                            $quiz_data[] = $object;
                        }
                    };
                    foreach ($quiz_data as $key) {
                        Core::insert_survey($key->id_question, $key->response, $id_test);
                    };
                    if ($test_evaluation_type == "AQ") {
                        $score = Core::calculate_survey_score($id_test);
                    }
                    echo '<script>alert("test passed successfully")</script>';
                    echo '<center><h1>Your test score is : ' . $score . '</h1></center>';
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
                echo $error;
            }
        }


        function survey_shortcode($atts)
        {
            $this->test_AQ_form();
        }
    }
}



