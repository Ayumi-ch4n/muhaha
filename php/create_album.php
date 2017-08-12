<?php
  session_start();
  include('functions.php');
  db_con("");
  not_logged();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/custom.css" rel="stylesheet">
    <title>Album | Create Album</title>
  </head>

  <body>
    <nav class="navbar navbar-default">
      <div class="container">
        <ul class="nav navbar-nav">
          <li><a href="/"><span class="glyphicon glyphicon-home"></span> Home</a></li>
          <li><a href="my_albums.php"><span class="glyphicon glyphicon-picture"></span> My Albums</a></li>
          <li class="active"><a style="color: #39e600;"><span class="glyphicon glyphicon-plus"></span> Create Album</a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
          <li><p class="navbar-text">Signed in as <?php echo $_SESSION['username'] ?></p></li>
          <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
      </div>
    </nav>

    <div class="container-fluid">
      <form class="form-inline" method="post" enctype="multipart/form-data">
        <div class="form-group col-sm-4">
          <input type="text" class="form-control" id="name" name="name" placeholder="Album Name" style="width: 100%;">
        </div>

        <div class="form-group">
          <input type="file" class="form-control btn btn-primary" name="images[]" accept="image/*" multiple>
            <label><input type="radio" name="radio" value="public" checked> Public</label>
            <label><input type="radio" name="radio" value="private"> Private</label>
          <button type="submit" class="btn btn-success" name="create"><span class="glyphicon glyphicon-ok"></span> Create</button>
          <a href="my_albums.php" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Cancel</a>
        </div>
      </form>
    </div>

    <?php
      if(isset($_POST['create'])) {
        $name = validate($_POST['name']);

        if(strlen($name) < 4 || sizeof($_FILES['images']['name']) == 0) {
          echo '<div class="alert alert-danger" style="margin-top: 5px;">Enter <strong>album name</strong> (at least 4 characters long) and choose at least <strong>1 picture</strong> to upload!</div>';
        }
        else {
          $dir = "../users/" .$_SESSION['username'] ."/" .$name;

          if(file_exists($dir)) {
            echo '<div class="alert alert-danger" style="margin-top: 5px;">Album <strong>already</strong> exists!</div>';
          }
          else {
            mkdir($dir);
            for($i = 0; $i < sizeof($_FILES['images']['name']); $i++) {
              move_uploaded_file($_FILES['images']['tmp_name'][$i], $dir .'/' .$_FILES['images']['name'][$i]);
            }

            if($_POST['radio'] == "public") {
              database::query('INSERT INTO album(user_id, name, public) VALUES(' .$_SESSION['user_id'] .', "' .$name .'", 1)');
            }
            else {
              database::query('INSERT INTO album(user_id, name, public) VALUES(' .$_SESSION['user_id'] .', "' .$name .'", 0)');
            }
            header('location:my_albums.php');
          }
        }
      }


      if(isset($_POST['cancel'])) {
        header('location:my_albums.php');
      }
    ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
  </body>
</html>
