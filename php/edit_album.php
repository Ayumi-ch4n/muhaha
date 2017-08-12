<?php
  session_start();
  include('functions.php');
  db_con("");
  check_logged();

  $album = database::queryOne('SELECT * FROM album WHERE id =' .$_GET['album']);
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
          <input type="text" class="form-control" id="name" name="name" placeholder="<?php echo $album['name'] ?> (leave blank for no change)" style="width: 100%;">
        </div>

        <div class="form-group">
          <input type="file" class="form-control btn btn-primary" name="images[]" accept="image/*" multiple>
            <label><input type="radio" name="radio" value="public" <?php echo $album['public'] == 1 ? "checked" : "" ?>> Public</label>
            <label><input type="radio" name="radio" value="private" <?php echo $album['public'] == 1 ? "" : "checked" ?>> Private</label>
          <button type="submit" class="btn btn-success" name="update"><span class="glyphicon glyphicon-ok"></span> Update</button>
          <a href="show_album.php?album=<?php echo $album['id'] ?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Cancel</a>
          <button type="submit" class="btn btn-danger" name="delete"><span class="glyphicon glyphicon-trash"></span> Remove</button>
        </div>
      </form>
    </div>

    <?php
      if(isset($_POST['update'])) {
        if(strlen($_POST['name']) != 0 && strlen($_POST['name']) < 4) {
          echo '<div class="alert alert-danger" style="margin-top: 5px;"><strong>Album name</strong> must be at least 4 characters long!</div>';
        }
        else if(strlen($_POST['name']) >= 4) {
          $name = htmlspecialchars($_POST['name']);
          $dir = "../users/" .$_SESSION['username'] ."/" .$album['name'];
          $dir = rename($dir, '../users/' .$_SESSION['username'] .'/' .$_POST['name']);
          database::query('UPDATE album SET name = "' .$name .'" WHERE id =' .$album['id']);
        }

        $dir = "../users/" .$_SESSION['username'] ."/" .$album['name'];

        for($i = 0; $i < sizeof($_FILES['images']['name']); $i++) {
          move_uploaded_file($_FILES['images']['tmp_name'][$i], $dir .'/' .$_FILES['images']['name'][$i]);
        }

        if($album['public'] == 1 && $_POST['radio'] == "private") {
          database::query('UPDATE album SET public = 0 WHERE id =' .$album['id']);
        }
        else if($album['public'] == 0 && $_POST['radio'] == "public") {
          database::query('UPDATE album SET public = 1 WHERE id =' .$album['id']);
        }

        header('location:show_album.php?album=' .$album['id']);
      }

      if(isset($_POST['cancel'])) {
        header('location:my_albums.php');
      }

      if(isset($_POST['delete'])) {
        $dir = "../users/" .$_SESSION['username'] ."/" .$album['name'];
        $files = scandir($dir);
        for($i = 0; $i < sizeof($files); $i++) {
          if($files[$i] != "." && $files[$i] != "..") {
            unlink($dir .'/' .$files[$i]);
          }
        }
        rmdir($dir);
        database::query('DELETE FROM album WHERE id =' .$album['id']);
        header('location:my_albums.php');
      }
    ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
  </body>
</html>
