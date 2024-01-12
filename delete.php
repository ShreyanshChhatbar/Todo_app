<?php
include "db.php";

//deleting row from index page...
if(isset($_POST['submit']) && isset($_POST['title_id'])){

    echo '<script> alert("Do you want to delete? sure?"); </script>';    
    $title_id = $_POST['title_id'];
    
    $conn->query("DELETE FROM todotable WHERE id = '$title_id'");
    header("location: http://localhost/index.php");
}

//deleting row from completed_tasks page...
if(isset($_GET["submit"]) && isset($_GET["title_id"])){
    $title_id = $_GET["title_id"];

    $conn->query("DELETE FROM completed_tasks WHERE id = '$title_id'");
    header("location: http://localhost/Completed_tasks.php");
}
?>