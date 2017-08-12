<?php
  session_start();

  require_once('database.php');
  database::connect('localhost', 'remi', 'root', 'admin');

  $album = database::queryOne('SELECT * FROM album WHERE id = ' .$_GET['album']);
  $username = database::querySingle('SELECT username FROM user WHERE id = ' .$album['user_id']);

  if($album['public'] == 0) {
    if(!isset($_SESSION['username']) || $album['user_id'] != $_SESSION['user_id']) {
      header('location:/');
    }
  }

  $creator = database::querySingle('SELECT username FROM user WHERE id = ' .$album['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/custom.css" rel="stylesheet">
    <title>Album | <?php echo $album['name'] ?></title>
  </head>

  <body>
    <nav class="navbar navbar-default">
      <div class="container">
        <ul class="nav navbar-nav">
          <li><a href="/"><span class="glyphicon glyphicon-home"></span> Home</a></li>
          <li class="<?php echo isset($_SESSION['username']) ? "" : "hidden"; ?>"><a href="../php/my_albums.php"><span class="glyphicon glyphicon-picture"></span> My Albums</a></li>
          <li class="<?php echo $_SESSION['username'] == $creator ? "" : "hidden" ?>"><a href="edit_album.php?album=<?php echo $album['id'] ?>" style="color: #0000e6;"><span class="glyphicon glyphicon-pencil"></span> Edit</a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
          <li class="<?php echo isset($_SESSION['username']) ? "hidden" : ""; ?>"><a href="../php/login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
          <li class="<?php echo isset($_SESSION['username']) ? "hidden" : ""; ?>"><a href="../php/register.php"><span class="glyphicon glyphicon-user"></span> Register</a></li>
          <li class="<?php echo isset($_SESSION['username']) ? "" : "hidden"; ?>"><p class="navbar-text">Signed in as <?php echo $_SESSION['username'] ?></p></li>
          <li class="<?php echo isset($_SESSION['username']) ? "" : "hidden"; ?>"><a href="../php/logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
      </div>
    </nav>

    <div class="container">
      <div class="row">
        <?php
          $dir = "../users/" .$username ."/" .$album['name'];

          if(is_dir($dir)) {
            if($odir = opendir($dir)) {
              while(($photo = readdir($odir)) != false) {
                if($photo != "." && $photo != "..") {
                  echo '<div class="col-md-4">
                    <a class="thumbnail" href="" data-toggle="modal" data-target="#lightbox">
                      <img alt="" class="img-responsive" src="' .$dir ."/" .$photo .'" style="height: 300px;">
                    </a>
                  </div>';
                }
              }
              closedir($odir);
            }
          }
        ?>
      </div>

      <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="lightbox">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-body">
              <img src="" alt="">
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/functions.js"></script>
  </body>
</html>
