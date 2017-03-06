<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="favicon.ico">

    <title><?= $this->title ?></title>
    <link href="public/css/bootstrap.min.css" rel="stylesheet">

    <link href="public/css/main.css" rel="stylesheet">
  </head>

  <body>
      <div class="header">
          <div class="container">
              <div class="col-md-2 text-left" >
                  <a id="logo"  href="/">Comments</a>
              </div>
              <div class="col-md-3 col-md-offset-7 text-right">
                  <div>
                      <?php if(empty($_SESSION['user'])):?>
                        <a href="/login">login</a>
                      <?php else:?>
                        <a href="/logout">logout</a>
                      <?php endif;?>
                  </div>
              </div>
          </div>
      </div>
    

    <div class="container">