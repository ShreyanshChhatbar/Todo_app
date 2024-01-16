<?php 
    include 'db.php';

    //while pressing edit btn on index...
    if(isset($_GET['submit'])){
        $title_id = $_GET['title_id'];
        $select = $conn->query("SELECT * FROM todotable WHERE id = $title_id")->fetch_all();
        // echo print_r($select);
        // exit();
        if(sizeof($select)> 0){
            $data = $select[0];
            $pretitle = $data[1];
            $predis = $data[2];            
        }else{
            header('location: http://localhost/index.php');
        }

    }

    //inserting submitted data by getting throught PHP_SELF...
    if(isset($_POST['submit'])) 
    { 
        // if(isset($_GET['title_id'])){
        //     //deleting row from todotable...
        //     echo'fghjjjjjjjjjj';
        //     exit();
        //     $conn->query("DELETE FROM todotable WHERE id = '$title_id'");
        // }

        //deleting task from database while it is being edited...
        if(isset($_POST['edited'])){
            $tit_id = $_POST['edited'];
            $conn->query("DELETE FROM todotable WHERE id = '$tit_id'");
        }

        $array= $_POST;
        //creating variables name according to data which is fetched through post method...
        foreach ($array as $key => $value) {
            $$key = $value;
        }
        
        //inserting data into todotable...
        $insert = $conn->query("INSERT INTO todotable ( title, disription) VALUES ( '$title', '$disription')");
        header('location: http://localhost/index.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add new To-Do</title>
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
            <a class="nav-link" href="Completed_tasks.php">Completed tasks</a>
        </li>
    </ul>
</nav>
<div class="container" style="margin-top:80px;">
    <div class="table-responsive-sm">
        <center>
            <h2 class="title">
                Add new Task!
            </h2>
        <!-- form to send data to Add_new.php itself... -->            
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <table class="table table-bordered table-dark ">
                
                <tr>
                    <td class="text-center">
                        <input name="title" type='text' placeholder="Title" value = "<?php if (isset($_GET['title_id'])) {echo $pretitle; } ?>" required>
                    </td>
                </tr>
                <br>
                <tr>
                    <td class="text-center">
                        <textarea name="disription" cols="100" rows="10" placeholder="disription" required><?php if (isset($_GET['title_id'])) {echo $predis; } ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td class="text-center pt-0">
                        <input type="hidden" name="edited" value="<?php if(isset($_GET["title_id"])){ echo $title_id;}  ?>">
                        <button type="submit" name="submit"  class="btn btn-success mt-5 ml-5" value="1">Submit</button>
                    </td>
                </tr>
            </table>
        </form>
        </center>
    </div>
</div>
</body>
</html>