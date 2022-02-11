<?php
  include 'mailer.php';
    if(!isset($_SESSION)) { 
        session_start(); 
    }
    
    function storeInDB(){
      $appID = $_POST['appId'];
      $diagnosis = $_POST['diagnosis'];
      $date = date('Y-m-d H:i:s');

      if(empty($appID)){
          echo '<p>Insert application ID</p>';
          return;
      }

      $inUnregisteredList = isInUnregisteredList($appID);
      
      if(!$inUnregisteredList){
          $postArray = array($appID, $diagnosis, $date);
          
          $result = insertQuery('Diagnostic', $postArray);
          
          if ($diagnosis == "1"){
            $warning = sendWarning($appID);
          } else {
            $negativeTest = sendNegative($appID);
          }
      }else{
          echo "<p>Diagnosis already exists</p>";
          return;
      }

      if($result->num_row > 0){
          echo "<p>Diagnosis already entered</p>";
      }
      
    }
  
    function isInUnregisteredList($appID) : bool
    {
        $result = executeQuery("SELECT applicationID From Diagnostic WHERE applicationID = $appID");
  
        if($result->num_row > 0){
            return true;
        } else {
            return false;
        }
    }
    
    function sendWarning($appID){
      $medNumber = (executeQuery("SELECT medicalNumber FROM Application WHERE applicationID = $appID") -> fetch_assoc())['medicalNumber'];
      
      $result = sendEmail($medNumber, 'COVID-19 Test Result', 'Result of COVID-19 Test: Positive', 'You need to quarantine yourself for the next 2 weeks', false);
      
      $group = executeQuery("SELECT p.medicalNumber FROM Participates p, Participates p2
                              WHERE p.medicalNumber != p2.medicalNumber AND p2.medicalNumber = $medNumber 
                              AND p.tag = p2.tag");
      
      if($group->num_rows > 0){
        while($row = $group -> fetch_assoc()){
          $result = sendEmail($row['medicalNumber'], 'COVID-19 Warning', 'A member of you group has tested positive to COVID-19', 'Go get tested!!!!', false);
        }
      }
    }
    
    function sendNegative($appID){
      $medNumber = (executeQuery("SELECT medicalNumber FROM Application WHERE applicationID = $appID") -> fetch_assoc())['medicalNumber'];
      
      $result = sendEmail($medNumber, 'COVID-19 Test Result', 'Result of COVID-19 Test: Negative', 'Keep wearing a mask and keep a distance of 2 meters.', false);
    }
    
    if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] || true){
      if(isset($_POST['submit'])){
          storeInDB();
      }
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
        <title>INDEX</title>
    </head>
    <style>
        .vertical-menu {
            width: 100%;
            height: 150px;
            overflow-y: auto;
        }

        .vertical-menu a{
            background-color: #eee;
            color: black;
            display: block;
            padding: 12px;
            text-decoration: none;
        }
        
        .vertical-menu a:hover {
          background-color: #ccc;
        }
        
        .vertical-menu a.active{
          background-color: #4CAF50;
          color: white;
        }
        
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
    <body>
        <?php
          include 'navbar.php';
        ?>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
        </script>
    
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous">
        </script>
        
        <div> 
  		    <div class="jumbotron jumbotron-fluid mt-5" >
  			    <div class="container center">
  				    <h1 class="display-4">Diagnostics</h1>
  			    </div>
      		</div>
      	</div>
       
       <div>
         <div class = "vertical-menu">
           <?php
             $result = executeQuery('SELECT * FROM Application WHERE applicationID NOT IN (SELECT applicationID from Diagnostic)');
             
             echo '<table>';
             echo '<tr>';
             echo '<th>Application ID</th>';
             echo '<th>Medical Number</th>';
             echo '<th>Public Health Facility Name</th>';
             echo '<th>Health Worker ID</th>';
             echo '</tr>';
             ;
             
             if($result->num_rows>0){
              while($row = $result -> fetch_assoc()){
                echo"<tr><td>". $row["applicationID"] ."</td><td>". $row["medicalNumber"]."</td><td>". $row["publicHealthFacilityName"]."</td><td>". $row["healthWorkerID"]."</td></tr> ";
              }
            }
            echo '</table>'
           ?>
         </div><br><br>
       </div>
       
       <div class = "container mt-5">
         <div class = "row mx auto">
            <div class = "mx-auto">
                <form method = "POST">
                    <div>
                        <label for = "appId">Application ID:</label><br>
                        <input type = "text" id = "appId" name = "appId"><br>
                        <input type = "hidden" name = "diagnosis" value = "0">
                        <input type = "checkbox" id = "check" name="diagnosis" value = "1">
                        <label for = "diagnosis">Does the patient have COVID-19?</label> <br><br>
                    </div>
                    <div>
                        <button class = "btn btn-dark btn-lg btn-block" name = "submit" value = "submit" type = "submit">Submit</button>
                    </div>
                </form> 
           </div>
         </div>
       </div>
    </body>
</html>