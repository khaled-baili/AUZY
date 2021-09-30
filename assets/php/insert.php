
<?php
$connect = mysqli_connect("localhost", "root", "", "auzy");
mysqli_set_charset($connect, "utf8");
if(isset($_POST["question"], $_POST["type"], $_POST["category_id"],$_POST["category_id"] ))
{
 $question = mysqli_real_escape_string($connect, $_POST["question"]);
 
 $type = mysqli_real_escape_string($connect, $_POST["type"]);
 
 $categ_id = mysqli_real_escape_string($connect, $_POST["category_id"]);

 $domain_id = mysqli_real_escape_string($connect, $_POST["domaine_id"]);

 $query = "INSERT INTO wp_test_questions (question, _id_domain, _type, id_question_categ) VALUES ('$question', '$domain_id' ,'$type', '$categ_id')";
 if(mysqli_query($connect, $query))
 {
  echo '<script>alert("Data Inserted")</script>';
 } else {
     echo '<script>alert("Something is going wrong data is not saved")</script>';
 }
} else {
    echo '<script>alert("try to reload the page and re-enter the data")</script>';
}
?>
