<?php
$connect = mysqli_connect("localhost", "root", "", "auzy");
$columns = array('idcateg','name','test_eval');
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


$number_filter_row = mysqli_num_rows(mysqli_query($connect, $query));

$result = mysqli_query($connect, $query . $query1);

$data = array();

while ($row = mysqli_fetch_array($result)) {
    $sub_array = array();
    $sub_array[] = '<div contenteditable class="update_categ" data-id="' . $row["idcateg"] . '" data-column="_name">' . $row["_name"] . '</div>';
    $sub_array[] = '<div class="update_categ" data-id="' . $row["idcateg"] . '" data-column="_name">' . $row["test_eval"] . '</div>';
    $sub_array[] = '<button type="button" name="delete" class="btn btn-danger btn-xs delete_categ" id="' . $row["idcateg"] . '"><i class="fas fa-trash-alt"></i></button><button type="button" name="update" id="'.$row["idcateg"].'" class="btn btn-warning btn-xs update"><i class="fas fa-pencil-alt"></i></button>';
    $data[] = $sub_array;
}


function get_all_data($connect)
{
    $query = "SELECT * FROM wp_question_category ";
    $result = mysqli_query($connect, $query);
    return mysqli_num_rows($result);
}

$output = array(
    "draw"    => intval($_POST["draw"]),
    "recordsTotal"  =>  get_all_data($connect),
    "recordsFiltered" => $number_filter_row,
    "data"    => $data
);
echo json_encode($output,JSON_UNESCAPED_UNICODE);
?>