<?php
    
    
    $servername = "localhost";
    $db_username = "root";
    $db_password = "";

    // Create connection
    $conn = new mysqli($servername, $db_username, $db_password);
    
    // Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }

    // Create database if doesn't exist...
    if($result = $conn->query("SHOW DATABASES LIKE 'Todo'")){
        if($result->num_rows == 0){
            $db = $conn->query("CREATE DATABASE Todo");
        }
    }
    $dbname="Todo";

    // $conn = new mysqli($servername, $db_username, $db_password, $dbname);
    
    include "db.php";
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //create todotable if doesn't exist...
    if ($return = $conn->query("SHOW TABLES LIKE 'todotable'")){
        if($return->num_rows == 0) {
          $table = $conn->query("CREATE TABLE todotable (
            id INT(100) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(30) NOT NULL,
            disription TEXT(1000)
          )");
        //   echo "todotable Table Created.";
        }
    }

    //create running table if doesn't exist...
    if ($return = $conn->query("SHOW TABLES LIKE 'running'")){
        if($return->num_rows == 0) {
          $table = $conn->query("CREATE TABLE running (
            id INT(100),
            title VARCHAR(30) NOT NULL,
            disription VARCHAR(100),
            start_time timestamp
          )");
          echo "running Table Created.";
        }
    }

    //creating completed_tasks table if doesn't exist...
    if ($return = $conn->query("SHOW TABLES LIKE 'completed_tasks'")){
        if($return->num_rows == 0) {
          $table = $conn->query("CREATE TABLE completed_tasks (
            id INT(100),
            title VARCHAR(30) NOT NULL,
            disription VARCHAR(100),
            start_time timestamp,
            end_time timestamp
          )");
          echo "completed_tasks Table Created.";
        }
    }

    //restoring completed tasks by deleting it from completed_tasks and inserting it into todotable...
    if(isset($_POST['submit'])){
        $title_id = $_POST["title_id"];
        $rowww = $conn->query("SELECT * FROM completed_tasks WHERE id = $title_id")->fetch_all();
        if(sizeof($rowww) > 0){
            $row = $rowww[0];
            $insert = $conn->query("INSERT INTO todotable ( title, disription) VALUES ( '$row[1]', '$row[2]')");
            $conn->query("DELETE FROM completed_tasks WHERE id = '$title_id'");
        }
    }

    //getting data of all tables...

    $res = $conn->query("SELECT * FROM todotable")->fetch_all();
    $running = $conn->query("SELECT * FROM running")->fetch_all();
    $completed = $conn->query("SELECT * FROM completed_tasks")->fetch_all();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do App</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body style="background-color:#8ceda6">
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
        <a class="navbar-brand" href="index.php">Medkart</a>
        <ul class="navbar-nav">
            <li class="nav-item ml-3">
                <a class="nav-link" href="Add_new.php">+ Add new</a>
            </li>
            <li class="nav-item ml-3">
                <a class="nav-link" href="running.php">Running tasks</a>
            </li>
            <li class="nav-item ml-3">
                <a class="nav-link" href="Completed_tasks.php">Completed tasks</a>
            </li>
        </ul>
    </nav>
    <div class="container" style="margin-top:80px;">
        <div class="row ml-1" style=" background-color:#1e81b0;"></div>
        <div class="table-responsive-sm">
            <center>
                <h2 class="title">
                    Pending Tasks!
                </h2>
            </center>
            <table class="table table-bordered table-dark">
    <?php   //checking if there is no data in todotable then printing nothin' here... 
            if(sizeof($res)==0){
    ?>          <tr>
                    <h2>Nothin' here...Add new task...</h2>
                </tr>
    <?php
            }else{
    ?>
                <tr>
                    <th>
                        Title
                    </th>
                    <th>
                        Discription
                    </th>
                    <th>
                        Start Task
                    </th>
                    <th>
                        Mark Done
                    </th>
                    <th>
                        Edit
                    </th>
                    <th>
                        Delete
                    </th>
                </tr>
                <?php
                // echo '<pre>';
                // echo sizeof($res);
                //printing rows of todotable from multidimentional array using nested for-loop...
                    for($i=0;$i<sizeof($res);$i++){ ?>
                        <tr>                                                
    <?php
                        for($j=1;$j<sizeof($res[$i]);$j++){
    ?>
                            <td>
                                <?php echo $res[$i][$j]?>
                            </td>
    <?php 
                        }
    ?>
                            <td>
                                <!-- form to send id to running.php to send data from todotable to running table... -->
                                <form action="running.php" method="post">
                                    <input type="hidden" name="title_id" value="<?php echo $res[$i][0]; ?>">
                                    <input type="hidden" name="start_time" value="<?php $dateAdded = date('Y-m-d h:i:s'); echo $dateAdded; ?>">
                                    <button type="submit" name="submit"  class="btn btn-primary" value='1'>Start</button>
                                </form>
                            </td>
                            <td>
                                <!-- form to send id to Completed_tasks.php to send data from todotable to completed_tasks table... -->
                                <form action="Completed_tasks.php" method="post">
                                    <input type="hidden" name="title_id" value="<?php echo $res[$i][0]; ?>">
                                    <input type="hidden" name="done_time" value="<?php $dateAdded = date('Y-m-d h:i:s'); echo $dateAdded; ?>">
                                    <button type="submit" name="submit"  class="btn btn-success" value='1'>Done</button>
                                </form>
                            </td> 
                            <td>
                                <!-- form to send id to Add_new.php to send data from todotable to edit data of that individual task... -->
                                <form action="Add_new.php" method="get">
                                    <input type="hidden" name="title_id" value="<?php echo $res[$i][0]; ?>">
                                    <button type="submit" name="submit"  class="btn btn-warning" value='1'>Edit</button>
                                </form>
                            </td>
                            <td>
                                <!-- form to send id to delete.php to send data from todotable to delete data of that individual raw... -->
                                <form action="delete.php" method="post">
                                    <input type="hidden" name="title_id" value="<?php echo $res[$i][0]; ?>">
                                    <button type="submit" name="submit"  class="btn btn-danger" value='1'>Delete</button>
                                </form>
                            </td>
                        </tr>
    <?php                       
                    }
                }
    ?>
            </table>
        </div>
    </div>
    <div class="container" style="margin-top:80px;">
        <div class="row ml-1" style=" background-color:#1e81b0;"></div>
        <div class="table-responsive-sm">
            <center>
                <h2 class="title">
                    Running Tasks!
                </h2>
            </center>
            <table class="table table-bordered table-dark">
    <?php   //checking if there is no data in running table then printing nothin' here... 
            if(sizeof($running)==0){
    ?>          <tr>
                    <h2>Nothin' here...</h2>
                </tr>
    <?php   }else{
    ?>          <tr>
                    <th>
                        Title
                    </th>
                    <th>
                        Discription
                    </th>
                    <th>
                        Start Time
                    </th>
                </tr>
    <?php   // echo '<pre>';
            // echo sizeof($res);
            //printing rows of running table from multidimentional array using nested for-loop...
                for($i=0;$i<sizeof($running);$i++){ 
?>              <tr>                                                
    <?php           for($j=1;$j<sizeof($running[$i]);$j++){
    ?>              <td>
    <?php               echo $running[$i][$j]
    ?>              </td>
    <?php           }
    ?>          </tr>
    <?php       }
            }
    ?>      </table>
        </div>
    </div>
    <div class="container" style="margin-top:80px;">
        <div class="row ml-1" style=" background-color:#1e81b0;"></div>
        <div class="table-responsive-sm">
            <center>
                <h2 class="title">
                    Completed Tasks!
                </h2>
            </center>
            <table class="table table-bordered table-dark">
    <?php       //checking if there is no data in completed_tasks table then printing nothin' here... 
                if(sizeof($completed)==0){
    ?>          <tr>
                        <h2>Nothin' here...</h2>
                </tr>
    <?php       }else{
    ?>          <tr>
                    <th>
                        Title
                    </th>
                    <th>
                        Discription
                    </th>
                    <th>
                        Start Time
                    </th>
                    <th>
                        End Time
                    </th>
                </tr>
    <?php       // echo '<pre>';
                // echo sizeof($res);
                //printing rows of completed_tasks table from multidimentional array using nested for-loop...
                    for($i=0;$i<sizeof($completed);$i++){ 
    ?>          <tr>                                                
    <?php               for($j=1;$j<sizeof($completed[$i]);$j++){
    ?>              <td>
    <?php                   echo $completed[$i][$j]?>
                    </td>
    <?php               }
    ?>          </tr>
    <?php           }
                }
    ?>      </table>
        </div>
    </div>
</body>
</html>