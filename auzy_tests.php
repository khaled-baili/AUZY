<?php



defined('ABSPATH') or die ('you are in the wrong place');

class Auzy_tests {

    function register_admin_scripts() {
        add_action('admin_enqueue_scripts',array($this,'enqueue') );
        add_action('admin_menu', array($this,'add_admin_pages'));
    }


    function uninstall() {

    }

    public function add_admin_pages() {
        add_menu_page('Auzy Tests', 'Auzy Tests', 'manage_tests', 'tests-slug',array($this,''),'dashicons-menu', 110);
        add_submenu_page('tests-slug', 'Manage Question', 'Manage Question', 'manage_options', 'manage-question-slug',array($this,'manage_question'));
        add_submenu_page('tests-slug', 'Question Form', 'Question Form', 'manage_options', 'question-form-slug', 'question_form');
        add_submenu_page('tests-slug', 'Consult result', 'Consult result', 'manage_options', 'consult-result-slug', 'consult_results');
    }



    public function manage_question() {
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

    //
    
    public function question_form() {

    }

    public function consult_results() {

    }

    function enqueue() {
        wp_enqueue_style('myPluginstyle', plugins_url('/assets/style.css', __FILE__));
        wp_enqueue_style('myPlugin_Bootstrap_Style', plugins_url('/assets/bootstrap/css/bootstrap.css', __FILE__));
        wp_enqueue_style('datatable_Bootstrap_Style', 'https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css');
        wp_enqueue_style('datatable_Bootstrap_Responsive_Style', 'https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css');
        wp_enqueue_style('myPlugin_fontAwesome_Style', plugins_url('/assets/fonts/fontawesome/css/all.css', __FILE__));
        wp_enqueue_style('bootstrap_datapicker', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css');
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-tooltip');
        wp_enqueue_script('myPlugin_Bootstrap_Script', plugins_url('/assets/bootstrap/js/bootstrap.min.js', __FILE__));
        wp_enqueue_script('bootstrap_datatable', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js');
        wp_enqueue_script('jquery_datatable', 'https://cdn.datatables.net/1.11.2/js/jquery.dataTables.min.js');
        wp_enqueue_script('js_datatable', 'https://cdn.datatables.net/1.11.2/js/dataTables.bootstrap.min.js');
        wp_enqueue_script('js_datatable_Responsive', 'https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js');
        wp_enqueue_script('datatable_Responsive_js', 'https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap.min.js');
        wp_enqueue_script('datatable_js', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js');
        wp_enqueue_script('myPlugin_Script', plugins_url('/assets/js/script.js', __FILE__));
    }


}


if (class_exists('Auzy_tests')) {
    $auzyTest = new Auzy_tests(); 
    $auzyTest -> register_admin_scripts();
}




// activation
require_once plugin_dir_path( __FILE__ ). 'inc/base/auzytest-activate-plugin.php' ;
register_activation_hook( __FILE__, array('AuzyTestPluginActivate','activate') );


// deactivate 
require_once plugin_dir_path( __FILE__). 'inc/base/auzytest-deactivate-plugin.php' ;
register_deactivation_hook( __FILE__, array('AuzyTestPluginDeactivate','deactivate') );
