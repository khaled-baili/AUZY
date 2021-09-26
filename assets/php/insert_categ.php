
<?php
$connect = mysqli_connect("localhost", "root", "", "auzy");
if(isset($_POST["categ_name"]) && isset($_POST["test_eval"]))
{
 $categ_name = mysqli_real_escape_string($connect, $_POST["categ_name"]);
 $test_eval = mysqli_real_escape_string($connect, $_POST["test_eval"]);
 

 $query = "INSERT INTO wp_question_category (_name, test_eval) VALUES ('$categ_name', '$test_eval')";
 
 if(mysqli_query($connect, $query))
 {
  echo '<script>alert("The new cateory inserted")</script>';
 } else {
     echo '<script>alert("Something is going wrong data is not saved try again")</script>';
 }
}
?>
