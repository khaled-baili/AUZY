
<?php
$connect = mysqli_connect("localhost", "root", "", "auzy");
mysqli_set_charset($connect, "utf8");
if(isset($_POST["question"], $_POST["type"], $_POST["category_id"]))
{
 $question = mysqli_real_escape_string($connect, $_POST["question"]);
 
 $type = mysqli_real_escape_string($connect, $_POST["type"]);
 
 $categ_id = mysqli_real_escape_string($connect, $_POST["category_id"]);

 $query = "INSERT INTO wp_test_questions (question, _type, id_question_categ) VALUES('$question', '$type', '$categ_id')";
 if(mysqli_query($connect, $query))
 {
  echo '<script>alert("Data Inserted")</script>';
 } else {
     echo '<script>alert("Something is going wrong data is not saved")</script>';
 }
}
?>
