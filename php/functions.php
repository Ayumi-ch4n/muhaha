<?php
  function db_con($path) {
    require_once($path .'database.php');
    database::connect('localhost', 'remi', 'root', 'admin');
  }

  function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  function check_logged() {
    if(!isset($_SESSION['username'])) {
      header('location:/');
    }
  }

  function check_not_logged() {
    if(isset($_SESSION['username'])) {
      header('location:/');
    }
  }

  function check_creator($album) {
    db_con();
    $user_id = database::querySingle('SELECT user_id FROM album WHERE id = ' .$album);

    if($user_id != $_SESSION['user_id']) {
      header('location:/');
    }
  }

  function check_private($album) {
    db_con("");
    $user_public = database::queryOne('SELECT user_id, public FROM album WHERE id = ' .$album);

    if($user_public['public'] == 0) {
      if($user_public['user_id'] != $_SESSION['user_id']) {
        header('location:/');
      }
    }
  }

  function load_albums($number, $list, $path1, $path2, $session) {
    for($i = 0; $i < $number; $i++) {
      if($session == false) {
        $username = database::querySingle('SELECT username FROM user WHERE id = ' .$list[$i]['user_id']);
      }
      else {
        $username = $session;
      }
      $img = glob($path1 .'users/'  .$username .'/' .$list[$i]['name'] .'/*.jpg');
      $imga = array_rand($img);

      echo '<div class="col-md-4">
        <a class="thumbnail" href="' .$path2 .'show_album.php?album=' .$list[$i]['id'] .'">
          <img alt="" class="img-responsive"" src="' .$img[$imga] .'" style="height: 300px;">
          <div class="caption text-center">
            <h4><strong>' .$list[$i]['name'] .'</strong></h4>
          </div>
        </a>
      </div>';
    }
  }

  function load_images($path) {
    if(is_dir($path)) {
      if($dir = opendir($path)) {
        while(($photo = readdir($dir)) != false) {
          if($photo != "." && $photo != "..") {
            echo '<div class="col-md-4">
              <a class="thumbnail" href="" data-toggle="modal" data-target="#lightbox">
                <img alt="" class="img-responsive" src="' .$path ."/" .$photo .'" style="height: 300px;">
              </a>
            </div>';
          }
        }
        closedir($dir);
      }
    }
  }
?>
