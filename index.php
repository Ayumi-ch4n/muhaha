<?php
  session_start();

  require_once('php/database.php');
  database::connect('localhost', 'remi', 'root', 'admin');
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

          for($i = 0; $i < $numberOfAlbums; $i++) {
            $username = database::querySingle('SELECT username FROM user WHERE id = ' .$listOfAlbums[$i]['user_id']);
            $img = glob('users/'  .$username .'/' .$listOfAlbums[$i]['name'] .'/*.jpg');
            $imga = array_rand($img);

            echo '<div class="col-md-4">
              <a class="thumbnail" href="php/show_album.php?album=' .$listOfAlbums[$i]['id'] .'">
                <img alt="" class="img-responsive"" src="' .$img[$imga] .'" style="height: 300px;">
                <div class="caption text-center">
                  <h4><strong>' .$listOfAlbums[$i]['name'] .'</strong></h4>
                </div>
              </a>
            </div>';
          }
        ?>
      </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
