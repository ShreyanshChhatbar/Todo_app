<?php
include "db.php";

//logic while getting data from index.php through post method...
if(isset($_POST['submit'])){

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
        //   echo "completed_tasks Table Created.";
        }
    }

    $title_id = $_POST["title_id"];

    $rowww = $conn->query("SELECT * FROM todotable WHERE id = $title_id")->fetch_all();
    if(sizeof($rowww) > 0) {
        $done_time = $_POST["done_time"];
        $row = $rowww[0];
        // echo print_r($row);
        $insert = $conn->query("INSERT INTO completed_tasks (id, title, disription, start_time, end_time) VALUES ( '$row[0]', '$row[1]', '$row[2]', '$done_time', '$done_time')");
        $conn->query("DELETE FROM todotable WHERE id = '$title_id'");
    }
        
}

//logic while getting data from running.php through get method...
if(isset($_GET["submit"])){
    $tit_id = $_GET["title_id"];
    $end_time = $_GET["end_time"];
    $nodee = $conn->query("SELECT * FROM running WHERE id = $tit_id")->fetch_all();
    if(sizeof($nodee) > 0) {
        $node = $nodee[0];
        // echo print_r($node);
        // exit();
        $insert = $conn->query("INSERT INTO completed_tasks (id, title, disription, start_time,end_time) VALUES ( '$node[0]', '$node[1]', '$node[2]', '$node[3]', '$end_time')");

        //deleting running table...
        $conn->query("DELETE FROM running WHERE id = '$tit_id'");
    }
}

$select = $conn->query("SELECT * FROM completed_tasks")->fetch_all();

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
        <a class="navbar-brand" href="index.php">Home</a>
        <ul class="navbar-nav">
            <li class="nav-item ml-3">
                <a class="nav-link" href="Add_new.php">+ Add new</a>
            </li>
            <li class="nav-item ml-3">
                <a class="nav-link" href="running.php">Running tasks</a>
            </li>
            <li class="nav-item ml-3">
                <a class="nav-link" >Completed tasks</a>
            </li>
        </ul>
    </nav>
    <div class="container" style="margin-top:80px;">
        <div class="table-responsive-sm">
            <center><h2 class="title">
                Completed Tasks!
            </h2></center>
            
            <table class="table table-bordered table-dark">
                <?php if(sizeof($select)==0){
                  ?><tr>
                        <h2>Nothin' here...</h2>
                    </tr><?php
                }else{
                    ?>
                <tr>
                    
                    <!-- <th>
                        ID
                    </th> -->
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
                    <th>
                        Restore
                    </th>
                    <th>
                        Delete
                    </th>
                    
                </tr>
                <?php                
                    for($i=0;$i<sizeof($select);$i++){ ?>
                        <tr>
                            <?php
                        for($j=1;$j<sizeof($select[$i]);$j++){
                            ?>
                            <td>
                                <?php echo $select[$i][$j]?>
                            </td>
    <?php 
                        }?>
                            <td>
                                <!-- form to send data to index.php to send data from completed_tasks table to restore data of that individual task... -->
                                <form action="index.php" method="post">
                                    <input type="hidden" name="title_id" value="<?php echo $select[$i][0]; ?>">
                                    <button type="submit" name="submit"  class="btn btn-primary" value='1'>Restore</button>
                                </form>
                            </td> 
                            
                            <td>
                                <!-- form to send data to delete.php to send data from completed_tasks table to delete data of that individual task... -->
                                <form action="delete.php" method="get">
                                    <input type="hidden" name="title_id" value="<?php echo $select[$i][0]; ?>">
                                    <button type="submit" name="submit"  class="btn btn-danger" value='1'>Delete</button>
                                </form>
                            </td>
                        </tr><?php                       
                    }
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>