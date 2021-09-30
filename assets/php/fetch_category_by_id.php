<?php
$connect = mysqli_connect("localhost", "root", "", "auzy");
mysqli_set_charset($connect, "utf8");
$query ="" ;
if (isset($_POST["idcateg"])) {
    $query = "SELECT * FROM wp_question_category  where idcateg = " . $_POST["idcateg"];
}
$data = array();
$result = mysqli_query($connect, $query);
$row = mysqli_fetch_array($result);
echo json_encode($row);
?>