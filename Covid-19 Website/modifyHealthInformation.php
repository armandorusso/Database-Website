<?php
  if(!isset($_SESSION)){
    session_start();
  }
  
  include 'healthInformation.php';
  
  if(isset($_SESSION['isAdmin']) && ($_SESSION['isAdmin'])){
    if(isset($_POST['addHealthInfo']) && isset($_POST['healthInfo'])){
      insertQuery('HealthInformation', array(firstAvailableID(), $_POST['healthInfo']));
    }
    else if(isset($_POST['deleteHealthInfo']) && isset($_POST['deleteID'])){
      $id = (int)$_POST['deleteID'];
      
      executeQuery('DELETE FROM InformationBulletParent WHERE parentInfoID = '.$id.' OR childInfoID = '.$id);
      executeQuery('DELETE FROM HealthInformation WHERE infoID = '.$id);
    }
    else if((isset($_POST['addParentChild']) || isset($_POST['deleteParentChild'])) && isset($_POST['parentID']) && isset($_POST['childID'])){
      $parentID = (int)$_POST['parentID'];
      $childID = (int)$_POST['childID'];
      
      if(isset($_POST['addParentChild'])){
        if(executeQuery('SELECT * FROM InformationBulletParent WHERE childInfoID = '.$childID.' OR childInfoID = '.$parentID)->num_rows == 0 && $parentID != $childID){
          insertQuery('InformationBulletParent', array($parentID, $childID));
        }
      }
      else if(isset($_POST['deleteParentChild'])){
        executeQuery('DELETE FROM InformationBulletParent WHERE parentInfoID = '.$parentID.' AND childInfoID = '.$childID);
      }
    }
  }
  else{
    header('index.php');
    exit();
  }
?>
<html>
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="stylesheet.css">

    <title>Modify Health Information</title>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    
    <style> 
      table, th, td {
        border: 1px solid black;
        text-align:center;
      }  
        
      table{
        border: 1px solid black;
      
        width:100%;
        color:#588c7e;
        font-family:monospace;
        font-size:25px;
        text-align:left;
        border: 1px solid black;
      }
          
      th{
        background-color:#d96459;
        color:white;
      }
          
      tr{color:black;}
      tr:nth-child(even) {background-color:#ffe5d9;}
      tr:nth-child(odd) {background-color:white;}
          
      h1{
        text-align:center;
        color:white;
      }
    
      form{
        color:white;
        font-size:25px;
      }
    </style>
    
  </head>
  <body>
    <?php
      include 'navbar.php';
    ?>
    <form method='POST'>
      <div class="container">
        <h1>HEALTH INFORMATION</h1>
        <?php
          generateHealthInfo();
        ?>
      </div> 
      <div class="container">
          <div class="row mx-auto mt-5">
              <textarea placeholder="New Health Information...." name="healthInfo" id="healthInfo" rows="10" cols="70" ></textarea>
              <button class="btn btn-dark ml-3" name="addHealthInfo" value="submit" type="submit">Add</button>
          </div>
      </div>
      
      <br>
      
      <div class="container">
        <div class="row mx-auto mt-5"> 
        
                <input type="text" id="deleteID" name="deleteID" placeholder="Health Info ID">
                <button class="btn btn-dark ml-3" name="deleteHealthInfo" value="submit" type="submit">Delete</button>
        
        </div>
      </div>
      
      <br>
      
      <div class="container">
        <h1>PARENT CHILD INFORMATION</h1>
        <?php
          generateParentChild();
        ?>
      </div>
      
      <div class="container">
        <div class="row mx-auto mt-4"> 
                 <input type="text" id="parentID" name="parentID" placeholder="Parent ID" class="mr-2">
                 <input type="text" id="childID" name="childID" placeholder="Child ID">
        
        </div>
        

          
          <button class="btn btn-dark mt-5" name="addParentChild" value="submit" type="submit">Add</button>
          <button class="btn btn-dark mt-5" name="deleteParentChild" value="submit" type="submit">Delete</button>
      </div>
    </form>
  </body>
</html>