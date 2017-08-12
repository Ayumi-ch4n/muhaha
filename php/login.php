<?php
  session_start();
  include('functions.php');
  db_con("");
  logged();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/custom.css" rel="stylesheet">
    <title>Album | Login</title>
  </head>

  <body>
    <nav class="navbar navbar-default">
      <div class="container">
        <ul class="nav navbar-nav">
          <li><a href="/"><span class="glyphicon glyphicon-home"></span> Home</a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
          <li class="active"><a><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
          <li><a href="register.php"><span class="glyphicon glyphicon-user"></span> Register</a></li>
        </ul>
      </div>
    </nav>

    <div class="container">
      <form method="post" class="reg_log_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" class="form-control" id="username" name="username" placeholder="Username">
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Password">
        </div>

        <button type="submit" class="btn btn-success btn-block" name="login">Login</button>
      </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
  </body>
</html>

<?php
  if(isset($_POST['login'])) {
    $username = validate($_POST['username']);
    $password = validate($_POST['password']);
    $e_password = sha1($password);
    $isRegistered = database::querySingle('SELECT EXISTS(SELECT * FROM user WHERE username = "' .$username .'")');
    $userPassword = database::querySingle('SELECT password FROM user WHERE username = "' .$username .'"');
    $userId = database::querySingle('SELECT id FROM user WHERE username = "' .$username .'"');

    if($isRegistered) {
      if($e_password == $userPassword) {
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $userId;
        header('location:/');
      }
      else {
        echo '<div class="alert alert-danger reg_log_alert">Invalid <strong>Username</strong> or <strong>Password</strong>!</div>';
      }
    }
    else {
      echo '<div class="alert alert-danger reg_log_alert">Invalid <strong>Username</strong> or <strong>Password</strong>!</div>';
    }

  }
?>
