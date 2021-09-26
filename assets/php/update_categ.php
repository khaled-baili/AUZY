<?php
$connect = mysqli_connect("localhost", "root", "", "auzy");
if(isset($_POST["id"]))
{
 $value = mysqli_real_escape_string($connect, $_POST["value"]);
 $query = "UPDATE wp_question_category SET ".$_POST["column_name"]."='".$value."' WHERE idcateg = '".$_POST["id"]."'";
 if(mysqli_query($connect, $query))
 {
  echo 'Data Updated';
 }
}
?>
