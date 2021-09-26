<?php 
$connect = mysqli_connect("localhost", "root", "", "auzy");

$columns = array('question', 'type','', 'category');
$query = "SELECT * FROM wp_test_questions join wp_question_category  on wp_test_questions.id_question_categ = wp_question_category.idcateg ";

if(isset($_POST["search"]["value"]))
{
 $query .= ' 
 WHERE question LIKE "%'.$_POST["search"]["value"].'%" 
 OR _type LIKE "%'.$_POST["search"]["value"].'%" 
 OR _name like "%'.$_POST["search"]["value"].'%"
 ';
}

if(isset($_POST["order"]))
{
 $query .= 'ORDER BY '.$columns[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' 
 ';
}
else
{
 $query .= 'ORDER BY id DESC ';
}

$query1 = '';

if($_POST["length"] != -1)
{
 $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}


$number_filter_row = mysqli_num_rows(mysqli_query($connect,  $query.$query1));

$result = mysqli_query($connect, $query);

$data = array();

while ($row = mysqli_fetch_array($result)) {
    $sub_array = array();
    $quest_type = '';
    if ($row["_type"]=='A') {
        $quest_type="ASC" ;
    } else {
        $quest_type="DESC" ;
    }
    echo '<script>alert('.$quest_type.')</script>';
    $sub_array[] = '<div contenteditable class="update" data-id="'.$row["id"].'" data-column="question">' .$row["question"] . '</div>';
    $sub_array[] = '<div class="update" data-id="'.$row["id"].'" data-column="type">' .$quest_type. '</div>';
    $sub_array[] = '<div class="update" data-id="'.$row["idcateg"].'" data-column="category">' .$row["_name"]. '</div>';
    $sub_array[] = '<button type="button" name="delete" class="btn btn-danger btn-xs delete" id="'.$row["id"].'"><i class="fas fa-trash-alt"></i>&nbsp Delete</button>';
    $data[] = $sub_array;
    
 }

 function get_all_data($connect) {
    $query = "SELECT * FROM wp_test_questions ";
    $result = mysqli_query($connect, $query);
    return mysqli_num_rows($result);
 }

 $output = array(
    "draw"    => intval($_POST["draw"]),
    "recordsTotal"  =>  get_all_data($connect),
    "recordsFiltered" =>$number_filter_row,
    "data"    => $data
   );
   echo json_encode($output);




?>