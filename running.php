<?php
include "db.php";

if ($return = $conn->query("SHOW TABLES LIKE 'running'")){
    if($return->num_rows == 0) {
      $table = $conn->query("CREATE TABLE running (
        id INT(100),
        title VARCHAR(30) NOT NULL,
        disription VARCHAR(100)
      )");
      echo "running Table Created.";
    }
}

//deleting row from index page...
if(isset($_POST['submit']) && isset($_POST['title_id'])){
    $title_id = $_POST['title_id'];
    $rowww = $conn->query("SELECT * FROM todotable WHERE id = $title_id")->fetch_all();
    if(sizeof($rowww) > 0) {
        $row = $rowww[0];
        $insert = $conn->query("INSERT INTO running (id, title, disription) VALUES ( '$row[0]', '$row[1]', '$row[2]')");

        //deleting fromtodo table...
        $conn->query("DELETE FROM todotable WHERE id = '$title_id'");
    }
}
$select = $conn->query("SELECT * FROM running")->fetch_all();

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
            <li class="nav-item ml-3" >
                <a class="nav-link" href="Completed_tasks.php">Completed tasks</a>
            </li>
        </ul>
    </nav>
    <div class="container" style="margin-top:80px;">
        <!-- <div class="row ml-1" style=" background-color:#1e81b0;"></div> -->
        <div class="table-responsive-sm">
            <center>
                <h2 class="title">
                    Running Tasks!
                </h2>
            </center>
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
                        End Task
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
                                <form action="Completed_tasks.php" method="get">
                                    <?php //echo $res[$i][0];
                                    // exit();
                                    ?>
                                    <input type="hidden" name="title_id" value="<?php echo $select[$i][0]; ?>">
                                    <button type="submit" name="submit"  class="btn btn-danger" value='1'>End</button>
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