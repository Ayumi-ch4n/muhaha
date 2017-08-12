<?php
  session_start();

  if(isset($_SESSION['username'])) {
    session_destroy();
  }

  require_once('database.php');
  database::connect('localhost', 'remi', 'root', 'admin');
  include('functions.php');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/custom.css" rel="stylesheet">
    <title>Album | Register</title>
  </head>

  <body>
    <nav class="navbar navbar-default">
      <div class="container">
        <ul class="nav navbar-nav">
          <li><a href="/"><span class="glyphicon glyphicon-home"></span> Home</a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
          <li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
          <li class="active"><a><span class="glyphicon glyphicon-user"></span> Register</a></li>
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

        <button type="submit" class="btn btn-primary btn-block" name="register">Register</button>
      </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
  </body>
</html>

<?php
  if(isset($_POST['register'])) {
    $username = validate($_POST['username']);
    $password = validate($_POST['password']);
    $e_password = sha1($password);
    $isRegistered = database::querySingle('SELECT EXISTS(SELECT * FROM user WHERE username = "' .$username .'")');

    if(strlen($username) < 5 || strlen($password) < 5) {
      echo '<div class="alert alert-danger reg_log_alert" role="alert"><strong>Username</strong> and <strong>Password</strong> must be at least <strong>5</strong> characters long!</div>';
    }
    else if($isRegistered) {
      echo '<div class="alert alert-danger reg_log_alert">Account <strong>already</strong> registered!</div>';
    }
    else {
      database::query('INSERT INTO user(username, password) VALUES("' .$username .'", "' .$e_password .'")');
      mkdir('../users/' .$username);
      echo '<div class="alert alert-success reg_log_alert">Account <strong>successfully</strong> created!</div>';
    }

  }
?>
