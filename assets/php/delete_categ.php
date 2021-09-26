<?php
$connect = mysqli_connect("localhost", "root", "", "auzy");
if(isset($_POST["id"]))
{
 $query = "DELETE FROM wp_question_category WHERE idcateg = '".$_POST["id"]."'";
 if(mysqli_query($connect, $query))
 {
  echo 'Data Deleted';
 }
}
