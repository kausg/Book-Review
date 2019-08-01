<?php

session_start();
$servername = "localhost";
$username = "**************";
$password = "**********";
$database = "******************";

if(!isset($_SESSION['name'])){
  die('Not logged in');
}
 
$status=false;
if (isset($_SESSION['status'])){
  $status=htmlentities($_SESSION['status']);
  $status_color=htmlentities($_SESSION['color']);
  unset($_SESSION['status']);
  unset($_SESSION['color']);
}
try 
    {
        $pdo = new PDO("mysql:host=$servername;dbname=$database",$username,$password);
        // set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
catch (PDOException $e){
  echo'Connection failed: '.$e->getMessage();
  die();
}

$name=htmlentities($_SESSION['name']);
$_SESSION['color']='red';


if (isset($_POST['title']) && isset($_POST['genre']) && isset($_POST['review'])) 
{  if (strlen($_POST['title']) < 1 || strlen($_POST['genre']) < 1 || strlen($_POST['review']) < 1)
    {
        $_SESSION['status'] = "All fields are required";
        header("Location: submit.php");
        return;
    }

    $title=htmlentities($_POST['title']);
    $genre=htmlentities($_POST['genre']);
    $review=htmlentities($_POST['review']);

    $stmt=$pdo->prepare("
      INSERT INTO Review (user_id, title, content)
      VALUES (:user_id, :title, :content)
      ");

    $stmt->execute(array(
        ':user_id' => $_SESSION['user_id'],
        ':title' => $title, 
        ':content' => $content
    ));

    $_SESSION['status']='Review added';
    $_SESSION['color']='green';
    header('Location: index.php');
    return;
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Review Site</title>
</head>
<body>
    <div class="container">
        <h1 style="margin-top:30px; font-size:30px">Submit a review </h1>
        <?php 
          if( $status!== false){
            echo(
              '<p style="color:'.$status_color.'" class="col-sm-9">'.htmlentities($status)."</p>\n"
            );
          }
        ?>
        <form method="POST" class="form-horizontal">
          <label for="title">Title : </label>
          <input type="text" name="title" id="title" size="40"> <br>
          <label for="genre">Genre : </label>
          <input type="text" name="genre" id="genre" size="40"> <br>
          <label for="review">Your Review : </label> <br>
          <textarea name="content" id="content" cols="80" rows="8"></textarea> <br>
          <input type="submit" value="Submit">
          <a href="index.php"><input type="button" value="Cancel"></a>
        </form>
    </div>



<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>
</html>
