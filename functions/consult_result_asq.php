<?php
function consult_results()
{
?>
    <div class="container">
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
                <tbody>
                    <?php
                    global $wpdb;
                    try {
                        $table_test_info = $wpdb->prefix . 'test_info';

                        $results = $wpdb->get_results("SELECT * FROM " .  $table_test_info  . " ");
                        foreach ($results as $result) {
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

                    ?>
                </tbody>
            </table>
        </div>
        <?php
        $table_question = $wpdb->prefix . 'wp_test_response';
        $results = $wpdb->get_results("SELECT * FROM " .  $table_test_info  . " ");
        foreach ($results as $result) {
            $data = $wpdb->get_results("SELECT * FROM wp_test_response join wp_test_questions on wp_test_response.id_question = wp_test_questions.id where wp_test_response.id_test =" . $result->id_test . " ");
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
            $score = 0;
            foreach ($data as $key) {
                $response = '';
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
        ?>
    </div>


<?php
}