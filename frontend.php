<?php

/**
 * @package AuzyTestPlugin
 */
if (!class_exists('Frontend')) {
    class Frontend extends Core
    {
        public function show_all_surveys()
        {
            $output = '<div class="row">
                  <table id="test-table" class="table table-striped table-bordered nowrap" style="width:100%">
                    <thead>
                    <tr>
                        <th>E-mail</th>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Child Age</th>
                        <th>Test date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>';
            $test_meta = Core::fetch_survey_meta();
            try {
                foreach ($test_meta as $result) {
                    $output .= '<tr>
                            <td>' . $result->email . '</td>
                            <td>' . $result->first_name . '</td>
                            <td>' . $result->last_name . '</td>
                            <td>' . $result->child_age . '</td>
                            <td>' . $result->test_date . '</td>
                            <td><button type="button" class="btn btn-primary" data-toggle="modal"
                             data-target="#testDetails_' . $result->id_test . '">
                             Details</button></td></tr>';
                }
            } catch (Exception $e) {
                echo $e;
            }
            $output .= '</tbody>
                </table>
            </div>';
            foreach ($test_meta as $result) {
                $data = Core::fetch_survey_result($result->id_test);
                $output .= '<div class="modal fade" id="testDetails_' . $result->id_test . '"data-backdrop="static" 
                data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                 <h5 class="modal-title" id="staticBackdropLabel">Test Details</h5>
                                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
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
                $test_type = Core::fetch_survey_type($result->id_test);
                $test_eval = $test_type->test_eval;
                if ($test_eval=='AQ') $score = Core::calculate_AQ_survey_score($result->id_test);
                else $score = Core::calculate_Mchat_survey_score($result->id_test);
                $i = 1;
                if ($test_eval == "AQ") {
                    foreach ($data as $key) {
                        $response = '';
                        switch ($key->response) {
                            case "A":
                                $response = 'Definitely Agree';
                                break;
                            case "B":
                                $response = 'Slightly Agree';
                                break;
                            case "C":
                                $response = 'Slightly Disagree';
                                break;
                            case "D":
                                $response = 'Definitely Disagree';
                                break;
                            default:
                                $response = '';
                        }
                        $output .= '<tr>
                                        <th scope="row">'.$i++.'</th>
                                        <td>' . $key->question . '</td>
                                        <td>' . $response . '</td>
                                    </tr>';
                    }
                } else {
                    foreach ($data as $key) {
                        $response = '';
                        switch ($key->response) {
                            case "A":
                                $response = 'Yes';
                                break;
                            case "B":
                                $response = 'No';
                                break;
                            default:
                                $response = '';
                        }
                        $output .= '<tr>
                        <th scope="row">'.$i++.'</th>
                        <td>' . $key->question . '</td>
                        <td>' . $response . '</td>
                    </tr>';
                    }
                }
                $output .= ' </tbody></table>
                            <center><h1>Your test score is : ' .$score.'</h1></center>
                            </div>
                            <div class="modal-footer">
                                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>';
            }
            echo $output;
        }

        function show_all_categories()
        {
            $output = '<div class="row table-title">
                    <div class="col-sm-6">
                        <h2>Manage Category</h2>
                    </div>
                    <div class="col-sm-6">
                        <div class="functional-btn">
                            <button type="button" name="add_categ" id="add_categ" class="btn btn-info">
                            Add category</button>
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
                                <th>Short Codes</th>
                            </tr>
                        </thead>
                    </table>
                </div>';
            $output .= '<div id="recordModal" class="modal fade">
                        <div class="modal-dialog">
                        <form method="post" id="recordForm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">×</button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group"
                                        <label for="category_name" class="control-label">Category Name</label>
                                        <input type="text" class="form-control" id="category_name" name="category_name" 
                                        placeholder="category_name" required>			
                                    </div>
                                    <div class="form-group">
                                        <label for="test_evaluation" class="control-label">Test Evaluation</label>							
                                        <select name="test_evaluation" id="test_evaluation" class="form-control">
                                            <option value="AQ">AQ</option>
                                            <option value="Mchat">Mchat</option>
                                        </select>						
                                    </div>	   	
                                    <div class="modal-footer">
                                        <input type="hidden" name="idcateg" id="idcateg" />
                                        <input type="hidden" name="action" id="action" value="" />
                                        <input type="submit" name="save" id="save" class="btn btn-info" value="Save" />
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                         </form>
                        </div>
                        </div>';
            echo $output;
            if (isset($_POST['save'])) {
                Core::update_survey_category($_POST['idcateg'], $_POST['category_name'], $_POST['test_evaluation']);
            }
        }

        function show_all_questions()
        {
            echo '<div class="row table-title">
                    <div class="col-sm-6">
                        <h2>Questions</h2>
                    </div>
                    <div class="col-sm-6">
                        <div class="functional-btn">
                            <button type="button" data-toggle="modal" data-target="#recordModal" 
                            id="add_question" class="btn btn-info">Add question</button>
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
            echo '<div id="recordModal" class="modal fade">
                    <div class="modal-dialog">
                        <form method="post" id="recordForm">
                            <div class="modal-content">
                            <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group"
                            <label for="question" class="control-label">Question</label>
                            <input type="text" class="form-control" id="question" name="question" placeholder="Question" required>			
                        </div>
                    <div class="form-group">
                        <label for="type" class="control-label">type</label>							
                        <select name="type" id="type" class="form-control">
                            <option value="A">ASC</option>
                            <option value="D">DESC</option>
                        </select>						
                    </div>	   	
                    <div class="form-group">
                        <label for="category" class="control-label">Category</label>							
                        <select  class="form-control" id="category" name="category" placeholder="Category" required>';
            $data = Core::fetch_survey_category();
            foreach ($data as $row) {
                echo '<option value="' . $row->idcateg . '">' . $row->_name . '</option>';
            }
            echo '</select>			
              </div>		
              <div class="form-group">
                <label for="domaine" class="control-label">Domaine</label>							
                <select class="form-control"  id="domaine" name="domaine" placeholder="domaine" required">';
            $record = Core::fetch_all_domain();
            foreach ($record as $row) {
                echo '<option value="' . $row->_id_domaine . '">' . $row->_name_domaine . '</option>';
            }

            echo '</select>								
                        </div>	 				
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id" id="id" />
                            <input type="hidden" name="action" id="action" value="" />
                            <input type="submit" name="save" id="save" class="btn btn-info" value="Save" />
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                            </div>
                        </form>
                        </div>
                    </div>';
            if (isset($_POST['save']) && $_POST['action'] == 'updateRecord') {
                Core::update_test_question($_POST['id'], $_POST['question'], $_POST['domaine'], $_POST['type'], $_POST['category']);
            }
            if (isset($_POST['save']) && $_POST['action'] == 'addRecord') {
                Core::insert_question($_POST['question'], $_POST['domaine'], $_POST['type'], $_POST['category']);
            }
        }

        function test_AQ_form($id_test)
        {
            $test_evaluation = Core::fetch_survey_category_by_id($id_test);
            $test_evaluation_type = $test_evaluation->test_eval;
            $output = '<div class="container">
            <div class="row">
            <form action="" method="post" id="form">
                <legend  class="w-auto">Personnal information</legend>
                <div class="row form-inscription">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>
                             <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="child_age">Child Age</label>
                                <input type="number" class="form-control"  min="0" max="100" id="child_age" name="child_age" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                </div>
                <hr>';
            $output .= '<table id="survey_table" class="table">
                    <thead ><th></th></thead>
                    <tbody>';
            // $test_evaluation = Core::fetch_survey_type($id_test);
            // $test_evaluation_type = $test_evaluation->test_eval;
            $results = Core::fetch_test_questions($id_test);
            if ($test_evaluation_type == "AQ" && !empty($results)) {
                $index = 1;
                foreach ($results as $result) {
                    $output .= '<tr style="border: none">
                                <td class="question" style="border: none"><h5>' . $index++ . ". " . $result->question .'</h5><br>
                                <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="data-1'.$result->id.'" name="' . $result->id . '" value="A" ';
                    $output .= ' required>  
                            <label class="form-check-label" for="data-1'.$result->id.'">
                                Definitely Agree
                            </label>
                            </div>
                            <br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="data-2'.$result->id.'" name="' . $result->id . '" value="B"';
                    $output .= ' >
                            <label class="form-check-label" for="data-2'.$result->id.'">
                                Slightly Agree
                            </label>
                           </div>
                           <br>
                           <div class="form-check form-check-inline">
                             <input class="form-check-input" type="radio" id="data-3'.$result->id.'" name="' . $result->id . '" value="C"';
                    $output .= ' ><br>
                            <label class="form-check-label" for="data-3'.$result->id.'">
                                 Slightly Disagree
                            </label>
                           </div>
                           <br>
                           <div class="form-check form-check-inline">
                           <input class="form-check-input" type="radio" id="data-4'.$result->id.'" name="' . $result->id . '" value="D"';
                    $output .= '> <br>
                        <label class="form-check-label" for="data-4'.$result->id.'">
                            Definitely Disagree
                        </label>
                         </div>
                         <br>
                         </td>
                         </tr>';
                }
                $output .= '
                        </tbody>
                    </table>';
                $output .= '
                    <input type="hidden" name="test_evaluation" id="test_evaluation" value="'.$test_evaluation_type.'" />
                    <div class="row btn-submit">
                        <input class="btn btn-primary" name="submit" type="submit" value="Submit">
                    </div>
                </form>
            </div>';
                echo $output;
            } else if ($test_evaluation_type == "Mchat" && !empty($results)) {
                $index = 1;
                foreach ($results as $result) {
                    $output .= '<tr>
                                    <td>' . $index++ . '</td>
                                    <td>' . $result->question . '
                                    <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="' . $result->id . '" value="A"';
                    $output .= 'required> 
                           </div>
                           <div class="form-check form-check-inline">
                             <input class="form-check-input" type="radio" name="' . $result->id . '" value="B"';
                    $output .= '>
                           </div>';
                    $output .= '
                           </div>';
                    $output .= '
                         </div>
                           </td>
                         </tr>';
                }
                $output .= '</tbody>
                        </table>';
                $output .= '<div class="row btn-submit">
                            <input type="hidden" name="test_evaluation" id="test_evaluation" value="'.$test_evaluation_type.'" />
                            <div class="row btn-submit">
                                <input class="btn btn-primary" name="submit" type="submit" value="Submit">
                            </div>
                        <input class="btn btn-primary submit-btn" name="submit" type="submit" value="Submit">
                      </div>
                  </form>
                </div>
                </div>';
                echo $output;
            } else echo '<script>confirm("Sorry your entered id test doesn t match with any test evaluation")</script>';
            echo '<center><div id="test_result"><h1>Your test score is : <div id="test_score"></div></h1></div></center>';
        }
    }
}
