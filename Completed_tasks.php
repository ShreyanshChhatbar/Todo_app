<?php
    include "db.php";
if(isset($_POST['submit'])){

    //creating table if doesn't exist...
    if ($return = $conn->query("SHOW TABLES LIKE 'completed_tasks'")){
        if($return->num_rows == 0) {
          $table = $conn->query("CREATE TABLE completed_tasks (
            id INT(100),
            title VARCHAR(30) NOT NULL,
            disription VARCHAR(100)
          )");
          echo "completed_tasks Table Created.";
        }
    }

    // $data = $_POST;
    // echo '<pre>';
    // echo print_r($data);
    $title_id = $_POST["title_id"];

    $rowww = $conn->query("SELECT * FROM todotable WHERE id = $title_id")->fetch_all();
        // echo print_r($rowww);
    if(sizeof($rowww) > 0) {
        $row = $rowww[0];
        $insert = $conn->query("INSERT INTO completed_tasks (id, title, disription) VALUES ( '$row[0]', '$row[1]', '$row[2]')");

        //deleting fromtodo table...
        $conn->query("DELETE FROM todotable WHERE id = '$title_id'");
    }
    
    // echo print_r($select);
    // exit();
    // foreach ($array as $key => $value) {
    //     $$key = $value;
    // }
}
if(isset($_GET["submit"])){
    $tit_id = $_GET["title_id"];

    $nodee = $conn->query("SELECT * FROM running WHERE id = $tit_id")->fetch_all();
        // echo print_r($rowww);
    if(sizeof($nodee) > 0) {
        $node = $nodee[0];
        $insert = $conn->query("INSERT INTO completed_tasks (id, title, disription) VALUES ( '$node[0]', '$node[1]', '$node[2]')");

        //deleting fromtodo table...
        $conn->query("DELETE FROM running WHERE id = '$tit_id'");
    }
}
$select = $conn->query("SELECT * FROM completed_tasks")->fetch_all();
// echo"<pre>";
// echo print_r($row);


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
<body>
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
        <!-- <div class="row ml-1" style=" background-color:#1e81b0;"></div> -->
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
                        Restore
                    </th>
                    <th>
                        Delete
                    </th>
                    
                </tr>
                <?php
                // echo '<pre>';
                // echo sizeof($res);
                
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
                                <form action="index.php" method="post">
                                    <?php //echo $res[$i][0];
                                    // exit();
                                    ?>
                                    <input type="hidden" name="title_id" value="<?php echo $select[$i][0]; ?>">
                                    <button type="submit" name="submit"  class="btn btn-primary" value='1'>Restore</button>
                                </form>
                            </td> 
                            
                            <td>
                                <form action="delete.php" method="get">
                                    <?php //echo $res[$i][0];
                                    // exit();
                                    ?>
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