<?php
$connect = mysqli_connect("localhost", "root", "", "auzy");
mysqli_set_charset($connect, "utf8");
$query ="" ;
if (isset($_POST["id"])) {
    $query = "SELECT * FROM wp_test_questions join wp_question_category  on wp_test_questions.id_question_categ = wp_question_category.idcateg join wp_question_domaine on wp_question_domaine._id_domaine = wp_test_questions._id_domain where id = " . $_POST["id"];
}
$data = array();
$result = mysqli_query($connect, $query);
$row = mysqli_fetch_array($result);
echo json_encode($row);
?>