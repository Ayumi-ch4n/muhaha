<?php
  session_start();

  if(!isset($_SESSION['username'])) {
    header('location:/');
  }

  require_once('database.php');
  database::connect('localhost', 'remi', 'root', 'admin');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/custom.css" rel="stylesheet">
    <title>Album | My Albums</title>
  </head>

  <body>
    <nav class="navbar navbar-default">
      <div class="container">
        <ul class="nav navbar-nav">
          <li><a href="/"><span class="glyphicon glyphicon-home"></span> Home</a></li>
          <li class="active"><a><span class="glyphicon glyphicon-picture"></span> My Albums</a></li>
          <li><a href="create_album.php" style="color: #39e600;"><span class="glyphicon glyphicon-plus"></span> Create Album</a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
          <li><p class="navbar-text">Signed in as <?php echo $_SESSION['username'] ?></p></li>
          <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
      </div>
    </nav>

    <div class="container">
      <div class="row">
        <?php
          $numberOfAlbums = database::querySingle('SELECT COUNT(*) FROM album WHERE user_id =' .$_SESSION['user_id']);
          $listOfAlbums = database::queryAll('SELECT * FROM album WHERE user_id =' .$_SESSION['user_id']);

          for($i = 0; $i < $numberOfAlbums; $i++) {
            $img = glob('../users/'  .$_SESSION['username'] .'/' .$listOfAlbums[$i]['name'] .'/*.jpg');
            $imga = array_rand($img);

            echo '<div class="col-md-4">
              <a class="thumbnail" href="show_album.php?album=' .$listOfAlbums[$i]['id'] .'">
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
    <script src="../js/bootstrap.min.js"></script>
  </body>
</html>
