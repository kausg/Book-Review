<?php
session_start();
$servername = "localhost";
$username = "id10247591_qnc";
$password = "z4<68q60";
$database = "id10247591_quillncoffee";

$salt='S1h_r*sHT!';
$FAILURE=false;
if ( isset($_SESSION['FAILURE']) ) {
    $FAILURE = htmlentities($_SESSION['FAILURE']);
    unset($_SESSION['FAILURE']);
}

if ( isset($_POST['email']) && isset($_POST['pass']) && isset($_POST['username']) ) 
{
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 || strlen($_POST['username']) < 1 ) 
    {
        $_SESSION['FAILURE'] = "Please complete all fields.";
        header("Location: login.php");
        return;
    }
    if (strpos($_POST['email'], '@') === false)
    {
        $_SESSION['FAILURE'] = "Email address must contain @";
        header("Location: login.php");
        return;
    }
    $pass = htmlentities($_POST['pass']);
    $email = htmlentities($_POST['email']);
    try 
    {
        $pdo = new PDO("mysql:host=$servername;dbname=$database",$username,$password);
        // set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e)
    {
        echo "Connection failed: " . $e->getMessage();
        die();
    }
    $stmt = $pdo->prepare("
        SELECT * FROM users WHERE
        email= :email AND password= :password
    ");
    $stmt->execute([ 
        ':email'=>$_POST['email'],
        ':pass'=>hash('md5',$salt.$pass)
    ]);
    $row = $stmt->fetch(PDO::FETCH_OBJ);
    if ($row !== false) 
    {
        error_log("Login success ".$email);
        $_SESSION['name'] = $row->user_name;
        $_SESSION['user_id'] = $row->user_id;
        header("Location: submit.php");
        return;
    }
    $_SESSION['name'] = $_POST['username'];
    header("Location: ./login.php");
    return;
}
?>
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="./login.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300" type="text/css" />
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Bowlby+One+SC" />
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Bubblegum+Sans" />
    <link href="https://fonts.googleapis.com/css?family=Permanent+Marker" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" charset="utf-8"></script>
    <title>Welcome</title>
</head>

<body>

    <div class="header">
        <h3 class="logo" style="color:rgba(37, 41, 25, 0.993); text-shadow: 3px 2px rgb(250, 169, 63)"><span style="font-size: 150%">&nbsp;Quill<i class="fas fa-feather-alt"></i></span><br><span style="font-size: 120%; ">&nbsp;& Coffee</span></h3>
        <input type="checkbox" id="chk">
        <label for="chk" class="show-menu-btn">
          <i class="fas fa-ellipsis-h"></i>
        </label>

        <ul class="menu">
            <a href="./index.html" class="active">Home</a>
            <a href="./aboutus.html">About</a>
            <a href="./catalogue.html">Catalogue</a>
            <a href="./submit.html">Submit</a>
            <a href="./contact.html">Contact</a>
            <a href="#">Log-In</a>
            <label for="chk" class="hide-menu-btn">
            <i class="fas fa-times"></i>
          </label>
        </ul>
    </div>

    <form action="index.html" class="login-form" method="POST">
        <h1>Login</h1>

        <div class="txtb">
            <input type="text">
            <span data-placeholder="Username"></span>
        </div>

        <div class="txtb">
            <input type="password">
            <span data-placeholder="Password"></span>
        </div>

        <input type="submit" class="logbtn" value="Login">

        <div class="bottom-text">
            Don't have account? <a href="signup.php">Sign up</a>
        </div>
    </form>

    <script type="text/javascript">
        $(".txtb input").on("focus", function() {
            $(this).addClass("focus");
        });

        $(".txtb input").on("blur", function() {
            if ($(this).val() == "")
                $(this).removeClass("focus");
        });
    </script>

</body>

</html>