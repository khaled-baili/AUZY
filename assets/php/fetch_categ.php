<?php
// $connect = mysqli_connect("localhost", "root", "", "auzy");
// $columns = array('idcateg','name','test_eval');
require_once('../../../../../wp-config.php');
global $wpdb;
$query = "SELECT * FROM wp_question_category ";

if (isset($_POST["search"]["value"])) {
    $query .= ' 
 WHERE _name LIKE "%' . $_POST["search"]["value"] . '%" 

 ';
}
if (isset($_POST["order"])) {
    $query .= 'ORDER BY ' . $columns[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' 
 ';
} else {
    $query .= 'ORDER BY idcateg DESC ';
}

$query1 = '';

if ($_POST["length"] != -1) {
    $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}


$results = $wpdb->get_results($query);
$number_filter_row = $wpdb->num_rows;
$categories = $wpdb->get_results($query . $query1);;

$data = array();

foreach ($categories as $row) {
    $sub_array[] = '<div contenteditable class="update_categ" data-id="' . $row->idcateg . '" data-column="_name">' . $row->_name . '</div>';
    $sub_array[] = '<div class="update_categ" data-id="' . $row->idcateg . '" data-column="_name">' . $row->test_eval . '</div>';
    $sub_array[] = '<button type="button" name="delete" class="btn btn-danger btn-xs delete_categ" id="' . $row->idcateg . '"><i class="fas fa-trash-alt"></i></button><button type="button" name="update" id="' . $row->idcateg . '" class="btn btn-warning btn-xs update"><i class="fas fa-pencil-alt"></i></button>';
    $data[] = $sub_array;
    $sub_array = array();
 }


function get_all_data()
{
    $result = Core::fetch_survey_category();
    return count((array)$result);
 }

$output = array(
    "draw"    => intval($_POST["draw"]),
    "recordsTotal"  =>  get_all_data(),
    "recordsFiltered" => $number_filter_row,
    "data"    => $data
);
echo json_encode($output, JSON_UNESCAPED_UNICODE);
