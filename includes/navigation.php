<?php
//parse_url gets the current page without the querystring, 
//eg from products?page=0&category=3 to '/products.php'
//basename() removes the '/' at the beginning
$currentpage = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
//navigation items as an array
$navigation_items = array(
  "home"=>"index.php",
  "new question"=>"question.php",
  "my account"=>"account.php",
  "about us"=>"about.php",
  "login"=>"login.php",
  "register"=>"register.php",
);


?>
<div class="navbar navbar-default ">
  <div class="container-fluid">
    <div class="navbar-header">
    <a href="index.php" class="navbar-brand">
      <img class="img-responsive logo" src="images/Hello_logo.svg">
    </a>
    </div>
    <span class="navbar-text">
      <?php 
      if($_SESSION["username"]){
        echo "Hello ".$_SESSION["username"]; 
      }
      ?>
    </span>
    <form class="form-inline navbar-form navbar-right" action="search.php" method="get">
      <input class="form-control mr-sm-2" type="text" name="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success" type="submit">
        Search
      </button>
    </form>
    <ul class="nav navbar-nav navbar-right">
      <?php
      //render navigation items
      foreach($navigation_items as $key=>$value){
        if($value == $currentpage){
          echo "<li class=\"active\"><a href=\"$value\">$key</a></li>";
        }
        else{
          echo "<li><a href=\"$value\">$key</a></li>";
        }
      }
      ?>
      <a class="navbar-text dev" target="_blank" href="phpmyadmin/">Database</a>
    </ul>
    
  </div>
</div> 

<div class="container">
  <!--<div class="col-md-12">-->
    <ol class="breadcrumb">
      <li><a href="index.php">Home</a></li>
      <?php
      if($currentpage!=="index.php"){
        foreach($navigation_items as $name=>$link){
          if($link==$currentpage){
            echo "<li class=\"active\"><a href=\"$link\">$name</a></li>";
          }
        }
      }
      ?>
    </ol>
  <!--</div>-->
</div>