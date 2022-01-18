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
                  <div class="d-flex justify-content-between">
                        <form action="" name="frmCSVImport" id="frmCSVImport" method="POST" enctype="multipart/form-data">
                        <input type="file" name="import_file" >
                            <button type="submit" class="btn btn-secondary btn-sm" name="import_data">
                                Import data
                            </button>
                        </form>
                        <button type="button" class="btn btn-primary btn-sm" id="export_data">
                            Export data
                        </button>
                  </div>
                  <table id="test-table" class="table table-striped table-bordered nowrap">
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
                            <td><button type="button" class="btn btn-primary" data-bs-toggle="modal"
                             data-bs-target="#testDetails_' . $result->id_test . '">
                             Details</button>
                             </td></tr>';
                }
            } catch (Exception $e) {
                echo $e;
            }
            $output .= '</tbody>
                </table>
            </div>';
            foreach ($test_meta as $result) {
                $data = Core::fetch_survey_result($result->id_test);
                $output .= '<div class="modal fade" id="testDetails_' . $result->id_test
                    . '" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" 
                            aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                 <h5 class="modal-title" id="staticBackdropLabel">
                                 Test Details
                                 </h5>
                                 <button type="button" class="btn-close" data-bs-dismiss="modal" 
                                    aria-label="Close">
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
                if ($test_eval == 'AQ') $score = Core::calculate_AQ_survey_score($result->id_test);
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
                                        <th scope="row">' . $i++ . '</th>
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
                        <th scope="row">' . $i++ . '</th>
                        <td>' . $key->question . '</td>
                        <td>' . $response . '</td>
                    </tr>';
                    }
                }
                $output .= ' </tbody></table>
                            <center><h1>Your test score is : ' . $score . '</h1></center>
                            </div>
                            <div class="modal-footer">
                                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                 Close
                                 </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    ';
            }
            echo $output;
            if (isset($_POST['import_data'])) {
                $extension = pathinfo($_FILES['import_file']['name'], PATHINFO_EXTENSION);
                if(!empty($_FILES['import_file']['name']) && $extension == 'csv'){
                    $totalInserted = 0;
                    $csvFile = fopen($_FILES['import_file']['tmp_name'], 'r');
                    fgetcsv($csvFile);
                    while(($csvData = fgetcsv($csvFile)) !== FALSE){
                        $csvData = array_map("utf8_encode", $csvData);
                        $dataLen = count($csvData);
                        if( $dataLen != 10) continue;
                        $id_test = trim($csvData[0]);
                        $email = trim($csvData[1]);
                        $first_name = trim($csvData[2]);
                        $last_name = trim($csvData[3]);
                        $child_age = trim($csvData[4]);
                        $test_date = trim($csvData[5]);
                        $id_question = trim($csvData[6]);
                        $question = trim($csvData[7]);
                        $_type = trim($csvData[8]);
                        $resp = trim($csvData[9]);
                        if(!empty($id_test) && !empty($email) && !empty($first_name) && !empty($last_name) &&  
                            !empty($child_age) && !empty($test_date) && !empty($id_question) 
                            && !empty($_type) && !empty($resp) 
                        ) {
                            if(Core::verif_suervey_id($id_test)==0) {
                                Core::insert_survey_meta(
                                    $id_test, $first_name, $last_name, $child_age, $test_date,$email
                                );
                            }
                            echo Core::verif_rows_survey_completed($id_test);
                            if(Core::verif_rows_survey_completed($id_test)<=20) {
                                Core::insert_survey($id_question, $resp, $id_test);
                                $totalInserted++ ;
                            }
                        }
                    }
                    if ($totalInserted != 0) {
                        echo "<h3 style='color: green;'>Total record Inserted : ".$totalInserted."</h3>";
                    } else echo "<h3 style='color: orange;'>Data already inserted</h3>";
                } else echo "<h3 style='color: red;'>Invalid Extension</h3>";
            }
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
                                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                                aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group"
                                        <label for="category_name" class="control-label">Category Name</label>
                                        <input type="text" class="form-control" id="category_name" 
                                        name="category_name" placeholder="category_name" required>			
                                    </div>
                                    <div class="form-group">
                                        <label for="test_evaluation" class="control-label">
                                        Test Evaluation</label>							
                                        <select name="test_evaluation" id="test_evaluation" 
                                        class="form-control">
                                            <option value="AQ">AQ</option>
                                            <option value="Mchat">Mchat</option>
                                        </select>						
                                    </div>	   	
                                    <div class="modal-footer">
                                        <input type="hidden" name="idcateg" id="idcateg" />
                                        <input type="hidden" name="action" id="action" value="" />
                                        <input type="submit" name="save" id="save" class="btn btn-info" 
                                        value="Save" /><button type="button" class="btn btn-secondary" 
                                        data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                         </form>
                        </div>
                        </div>';
            echo $output;
            if (isset($_POST['save'])) {
                Core::update_survey_category(
                    $_POST['idcateg'],
                    $_POST['category_name'],
                    $_POST['test_evaluation']
                );
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
                            <button type="button" data-bs-toggle="modal" data-bs-target="#recordModal" 
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
                            <button type="button" class="btn-close" data-bs-dismiss="modal" 
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group"
                            <label for="question" class="control-label">Question</label>
                            <input type="text" class="form-control" id="question" name="question" 
                            placeholder="Question" required>			
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
                        <select  class="form-control" id="category" name="category" 
                        placeholder="Category" required>';
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
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close</button>
                            </div>
                            </div>
                        </form>
                        </div>
                    </div>';
            if (isset($_POST['save']) && $_POST['action'] == 'updateRecord') {
                Core::update_test_question(
                    $_POST['id'],
                    $_POST['question'],
                    $_POST['domaine'],
                    $_POST['type'],
                    $_POST['category']
                );
            }
            if (isset($_POST['save']) && $_POST['action'] == 'addRecord') {
                Core::insert_question(
                    $_POST['question'],
                    $_POST['domaine'],
                    $_POST['type'],
                    $_POST['category']
                );
            }
        }

        function test_AQ_form($id_test)
        {
            $test_evaluation = Core::fetch_survey_category_by_id($id_test);
            $test_evaluation_type = $test_evaluation->test_eval;
            $global_score_test = "";
            if ($test_evaluation_type=="AQ") {
                $global_score_test = '150';
            } else $global_score_test = '20';
            $output = '<div class="container">
            <div class="row">
                <div id="proceed-form" class="proceed-form">
                    <center>
                        <div class="proceed-form-titile">
                            Basic Information
                        </div>
                    </center>
                    <div>
                        <div class="proceed-form-element">
                            Statements: <strong>50</strong>
                        </div>
                        <div class="proceed-form-element">
                            Type: <strong>Screening tool</strong>
                        </div>  
                        <div class="proceed-form-element">
                            Description: <strong>The Autism-Spectrum Quotient Test is a diagnostic 
                            questionnaire designed to measure the expression of Autism-Spectrum traits 
                            in an individual, by his or her own subjective self-assessment.</strong>
                        </div>                  
                    </div>
                    <div class="form-check" id="agreement-section">
                        <br>
                        <input class="form-check-input" type="checkbox" value="agreement" id="agreement">
                        <label class="form-check-label" for="agreement">
                            I agree that my submitted data is being collected and stored
                        </label>
                    </div>
                    <center>
                        <button type="button" id="proceed-btn" 
                        class="proceed-btn"><span>Proceed</span></button>
                    </center>
                </div>
                <form action="" method="post" id="test-form">
                <div class="row form-inscription">
                        <h3>Personnal Information</h3>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-labels" for="first_name">First Name</label>
                                <input type="text" class="form-control test-form-control" id="first_name" 
                                name="first_name" required>
                            </div>
                            <div class="form-group">
                                <label class="form-labels" for="last_name">Last Name</label>
                                <input type="text" class="form-control test-form-control" id="last_name" 
                                name="last_name" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-labels" for="child_age">Child Age</label>
                                <input type="number" class="form-control test-form-control"  min="0" max="100" 
                                id="child_age" name="child_age" required>
                            </div>
                            <div class="form-group">
                                <label class="form-labels" rep-label for="email">Email address</label>
                                <input type="email" class="form-control test-form-control" id="email" 
                                name="email" required>
                            </div>
                        </div>
                </div>
                <hr>';
            $output .= '
                    <table id="survey_table" class="survey_table">
                    <thead class="datatable-header""><th></th></thead>
                    <tbody>';
            $results = Core::fetch_test_questions($id_test);
            echo $test_evaluation_type;
            if ($test_evaluation_type == "AQ" && !empty($results)) {
                $index = 1;
                foreach ($results as $result) {
                    $output .= '<tr class="tab-line">
                                <td class="question"><h5 class="question-style">'
                        . $index++ . ". " . $result->question . '</h5> <br>
                                <div class="form-check form-check-inline question-box">
                                <input class="btn-check" type="radio" id="data-1' . $result->id . '" 
                                name="' . $result->id . '" value="A"  autocomplete="off" ';
                    $output .= ' required>  
                            <label class="btn btn-outline-primary rep-label" for="data-1' . $result->id . '">
                                Definitely Agree
                            </label>
                            </div>
                            <div class="form-check form-check-inline question-box">
                                <input class="btn-check" type="radio" id="data-2' . $result->id . '" 
                                name="' . $result->id . '" value="B"  autocomplete="off" ';
                    $output .= ' >
                            <label class="btn btn-outline-primary rep-label" for="data-2' . $result->id . '">
                                Slightly Agree
                            </label>
                           </div>
                           <div class="form-check form-check-inline question-box">
                             <input class="btn-check" type="radio" id="data-3'
                        . $result->id . '" name="' . $result->id . '" value="C"  autocomplete="off" ';
                    $output .= ' >
                            <label class="btn btn-outline-primary rep-label" for="data-3' . $result->id . '">
                                Slightly Disagree
                            </label>
                           </div>
                           <div class="form-check form-check-inline question-box">
                           <input class="btn-check" type="radio" id="data-4' . $result->id . '" 
                           name="' . $result->id . '" value="D"  autocomplete="off" ';
                    $output .= '>
                        <label class="btn btn-outline-primary rep-label" for="data-4' . $result->id . '">
                            Definitely Disagree
                        </label>
                         </div>
                         </td>
                         </tr>';
                }
                $output .= '
                        </tbody>
                    </table>';
                $output .= '
                    <input type="hidden" name="test_evaluation" id="test_evaluation" 
                    value="' . $test_evaluation_type . '" />
                    <div class="row btn-submit">
                        <input class="btn-primary" id="submit-btn" name="submit" type="submit" value="Submit">
                    </div>
                </form>
            </div>';
                echo $output;
            } else if ($test_evaluation_type == "Mchat" && !empty($results)) {
                $index = 1;
                foreach ($results as $result) {
                    $output .= '<tr class="tab-line">
                                <td class="question"><h5 class="question-style">'
                        . $index++ . ". " . $result->question . '</h5> <br>
                                <div class="form-check form-check-inline question-box">
                                <input class="btn-check" type="radio" id="data-1' . $result->id . '" 
                                name="' . $result->id . '" value="A"  autocomplete="off" ';
                    $output .= ' required>  
                            <label class="btn btn-outline-primary rep-label" for="data-1' . $result->id . '">
                                Oui
                            </label>
                            </div>
                            <div class="form-check form-check-inline question-box">
                                <input class="btn-check" type="radio" id="data-2' . $result->id . '" 
                                name="' . $result->id . '" value="B"  autocomplete="off" ';
                    $output .= ' >
                            <label class="btn btn-outline-primary rep-label" for="data-2' . $result->id . '">
                                Non
                            </label>
                           </div>';
                    $output .= '</tr>';
                }
                $output .= '
                        </tbody>
                    </table>';
                $output .= '
                    <input type="hidden" name="test_evaluation" id="test_evaluation" 
                    value="' . $test_evaluation_type . '" />
                    <div class="row btn-submit">
                        <input class="btn-primary" id="submit-btn" name="submit" type="submit" value="Submit">
                    </div>
                </form>
            </div>';
                echo $output;
            } else echo '<script>
                        confirm("Sorry your entered id test doesn t match with any test evaluation")
                        </script>';
            echo '<div id="test_result" class="proceed-form">
                    <center>
                        <div class="proceed-form-titile">
                            Test Passed <br>
                            <img src="' . plugin_dir_url(__FILE__) . 'lib/img/check-logo.png" 
                            class="logo-img" alt="No image">
                        </div>
                    </center>
                    <div>
                        <center>
                            <div class="proceed-form-titile">
                                Result: <strong><span id="test-score"></span>/'.$global_score_test.'</strong>
                            </div>
                        </center>                 
                    </div>
                </div>';
        }
    }
}
