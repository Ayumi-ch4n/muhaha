<?php
  session_start();
  include('php/functions.php');
  db_con("php/");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <title>Album | Home</title>
  </head>

  <body>
    <nav class="navbar navbar-default">
      <div class="container">
        <ul class="nav navbar-nav">
          <li class="active"><a><span class="glyphicon glyphicon-home"></span> Home</a></li>
          <li class="<?php echo isset($_SESSION['username']) ? "" : "hidden"; ?>"><a href="php/my_albums.php"><span class="glyphicon glyphicon-picture"></span> My Albums</a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
          <li class="<?php echo isset($_SESSION['username']) ? "hidden" : ""; ?>"><a href="php/login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
          <li class="<?php echo isset($_SESSION['username']) ? "hidden" : ""; ?>"><a href="php/register.php"><span class="glyphicon glyphicon-user"></span> Register</a></li>
          <li class="<?php echo isset($_SESSION['username']) ? "" : "hidden"; ?>"><p class="navbar-text">Signed in as <?php echo $_SESSION['username'] ?></p></li>
          <li class="<?php echo isset($_SESSION['username']) ? "" : "hidden"; ?>"><a href="php/logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
      </div>
    </nav>

    <div class="container">
      <div class="row">
        <?php
          $numberOfAlbums = database::querySingle('SELECT COUNT(*) FROM album WHERE public = 1');
          $listOfAlbums = database::queryAll('SELECT * FROM album WHERE public = 1');
          load_albums($numberOfAlbums, $listOfAlbums, "", "php/", false);
        ?>
      </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
