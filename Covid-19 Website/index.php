<?php
  if(!isset($_SESSION)){
    session_start();
  }     
?>

<!DOCTYPE HTML>
<html>
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="stylesheet.css">

    <title>INDEX</title>
    
    <style> 
      *{
        font-family: Helvetica;
      }
      
      .color{
        color:white;
        border-style: solid;
      }    
      
      ul{
        padding:20px;
      }
      
      ol{
        padding:20px;
      }
    </style>
    
  </head>
  <body>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    
    <?php
      include 'navbar.php';
    ?>
    
    <div>
      <div class="jumbotron jumbotron-fluid mt-5" >
        <div class="container center">
          <h1 class="display-4">Health Information</h1>
        </div>
      </div>
    </div>

    <div>
      <div class="container">
        <div class="row ">
          <div class="col color ">
            <?php 
              include 'healthInformation.php';
              generateHealthInfoBullets();          
            ?>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>