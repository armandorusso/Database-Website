<?php
  if(!isset($_SESSION)){
    session_start();
  }
?>
<html>
  <?php
    include 'databaseConnection.php';
    include 'publicHealthFacility.php';

    if (isset($_POST['submit']) && isset($_SESSION['loginID'])) {
        $id = $_SESSION['loginID'];
        $radio = $_POST['publicHealthFacility'];
        $date = $_POST['date'];
        
        $result = executeQuery('SELECT * FROM WorksAt WHERE name = "'.$radio.'"');
        
        if($result->num_rows > 0){
          $row = $result->fetch_assoc();
          $healthID = $row['medicalNumber'];
          
          $queryCommand = 'INSERT INTO Application (medicalNumber, submissionDate, publicHealthFacilityName, healthWorkerID) VALUES (' . $id . ', "' . $date . '", "'.$radio.'", ' . $healthID . ')';
          $queryResult = executeQuery($queryCommand);
        }
    }
  ?>
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    
    <title>Appointment</title>
    
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
  
    <div class="container">
      <h1> HOSPITALS PROVIDED BY OUR SYSTEM </h1>
      <?php
        generateFacilityTable();
      ?>
    
    </div> 
    <br>
    <div class="container"> 
      <h1> Please Make A Choice </h1>
      <form method="post">
        <?php
          generateRadios(true);
        ?>
        <br>
        <label for='date'>Date time for your appointment:</label>
        <input type="text" id="date" name="date" placeholder="YYYY-MM-DD HH:MM:SS">
        <br>
        <button class="btn btn-dark btn-lg btn-block" name="submit" value="submit" type="submit">Submit</button>
      </form> 
    </div>
  </body>
</html>
