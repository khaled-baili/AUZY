<?php
require_once 'includes/phpmailer/PHPMailer.php';
require_once 'includes/phpmailer/SMTP.php';
require_once 'includes/phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


function question_form()
{
    global $wpdb;
?>
    <div class="container">

        <div class="row">
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

                </div>
                <?php
                $output ='<table class="table table-bordered">
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
                        $index = 1;
                        $table_name = $wpdb->prefix . 'test_questions';
                        $results = $wpdb->get_results("SELECT * FROM " . $table_name . " ");
                        foreach ($results as $result) {
                            $test_check = 0 ;
                            $output .= '<tr>
                            <td>' .  $index++ . '</td>
                         <td>' . $result->question . '</td>
                         <td>
                         <div class="form-check form-check-inline">
                         <input class="form-check-input" type="radio" name="quest_response_' . $result->id . '" value="A"';
                         if (isset($_POST['quest_response_' . $result->id . '']) == 0) {
                            $output .='checked';
                            $test_check = $test_check + 1;
                        }
                         $output .='required>
                       </div>
                       </td>
                       <td>
                       <div class="form-check form-check-inline">
                         <input class="form-check-input" type="radio" name="quest_response_' . $result->id . '" value="B"';
                         if (isset($_POST['quest_response_' . $result->id . '']) && $test_check ==0) {
                            $output .='checked';
                            $test_check = $test_check + 1;
                        }
                         
                         $output .='>
                       </div>
                       </td>
                       <td>
                       <div class="form-check form-check-inline">
                         <input class="form-check-input" type="radio" name="quest_response_' . $result->id . '" value="C" ';
                         if (isset($_POST['quest_response_' . $result->id . '']) && $test_check ==0) {
                            $output .='checked';
                            $test_check = $test_check + 1;
                        }
                        $output .='>
                       </div>
                       </td>
                       <td>
                       <div class="form-check form-check-inline">
                       <input class="form-check-input" type="radio" name="quest_response_' . $result->id . '" value="D" ';
                       if (isset($_POST['quest_response_' . $result->id . '']) && $test_check ==0) {
                        $output .='checked';
                        $test_check = $test_check + 1;
                    }
                    $output .= '>
                     </div>
                       </td>
                     </tr>';
                        }
                        
                        
                        $output .='
                    </tbody>
                </table>';
                echo $output;
                
                ?>
                <div class="row btn-submit">
                    <input class="btn btn-primary" name="submit" type="submit" value="Submit">
                </div>



            </form>
        </div>









    </div>
<?php
    try {
        if (isset($_POST['submit'])) {
            $quiz_data = array();
            $table_response = $wpdb->prefix . 'test_response';
            $table_question = $wpdb->prefix . 'test_questions';
            $table_test_info = $wpdb->prefix . 'test_info';
            $test_id = $wpdb->get_row("SELECT MAX(id_test) AS test_id FROM " .  $table_test_info . " ");
            $id_test = $test_id->test_id + 1;
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $email = $_POST['email'];
            $wpdb->insert($table_test_info, array(
                'id_test' => $id_test, 'first_name' => $first_name, 'last_name' => $last_name, 'test_date' => date("Y/m/d"), 'email' => $email
            ));
            $questions = $wpdb->get_results("SELECT * FROM " . $table_question . " ");
            $score = 0;
            foreach ($questions as $question) {
                $object = new stdClass();
                if (isset($_POST['quest_response_' . $question->id . ''])) {
                    if ($question->_type == "A") {
                        switch ($_POST['quest_response_' . $question->id . '']) {
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
                        switch ($_POST['quest_response_' . $question->id . '']) {
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
                    $object->id_question = $question->id;
                    $object->response  = $_POST['quest_response_' . $result->id . ''];
                    $quiz_data[] = $object;
                }
            }
            foreach ($quiz_data as $key) {
                $wpdb->insert($table_response, array(
                    'id_question' => $key->id_question, 'response' => $key->response, 'id_test' => $id_test
                ));
            }






            include('pdf.php');
            $file_name = md5(rand()) . '.pdf';
            $output .='<center><h1>Your test score is : ' . $score . '</h1></center>';
            $pdf = new Pdf();
            $pdf->load_html($output);
            $pdf->render();
            $file = $pdf->output();
            file_put_contents($file_name, $file);

            $mail = new PHPMailer;
            $mail->IsSMTP();   
            $mail->Host = 'smtp.gmail.com';      
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls';
            $mail->Port = '587';   
            $mail->SMTPOptions = array(
                'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
                )
                );
            $mail->Username = 'bailikhaled@gmail.com';                    
            $mail->Password = 'kh446062208167';                  
            $mail->SMTPSecure = '';                            
            $mail->setFrom('bailikhaled@gmail.com') ;           
            $mail->AddAddress($email, $first_name);       
            $mail->IsHTML(true);                            
            $mail->AddAttachment($file_name);                    
            $mail->Subject = 'Test Details';           
            $mail->Body = 'Thank you for passing the test you can check test result in the attached file';                
            if ($mail->Send())                                
            {
                $message = '<label class="text-success">Test details has been sent to your address email...</label>';
            }
            $mail->smtpClose();
            unlink($file_name);
            echo $message;
            echo '<script>alert("test passed successfully")</script>';
            echo '<center><h1>Your test score is : ' . $score . '</h1></center>';
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        echo $error;
    }
}
?>