<?php
  include 'databaseConnection.php';
  
  if(!isset($_SESSION)) {
    session_start();
  }
  		
  if(isset($_POST['submit']))
	{
    $medicalNumber = $_POST['medicalNumber'];
    $dob = $_POST['dob'];

    if(!empty($medicalNumber) && !empty($dob)){
        $result = executeQuery('SELECT medicalNumber, dateOfBirth FROM Person WHERE medicalNumber = '.$medicalNumber.' AND dateOfBirth = "'.$dob.'"');
      
      if($result->num_rows>0){
        $_SESSION['loginID'] = $medicalNumber;
        
        $result = executeQuery('SELECT * FROM HealthWorker WHERE medicalNumber = '.$medicalNumber);
        
        if($result->num_rows > 0){
          $_SESSION['isAdmin'] = true;
        }
        else{
          $_SESSION['isAdmin'] = false;
        }
        
        header("Location: index.php");
        exit();
      }		
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
  </head
  <body>  
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous">
    </script>
   
    <?php
      include 'navbar.php';
    ?>
    
   	<div> 
  		<div class="jumbotron jumbotron-fluid mt-5" >
  			<div class="container center">
  				<h1 class="display-4">Covid-19 Login</h1>
  				<p class="lead">Please fill out the details below.</p>
  			</div>
  		</div>
  	</div>
      
    <div class="container mt-5">
      <div class="row mx-auto">
  			<div  class="mx-auto">
  				<div id="theForm">
  					<form action="" method="POST" >
  					  <div class="form-group">
    						<label for="exampleInputEmail1">Medical Number</label>
    						<input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="medicalNumber" input type="text" placeholder="Your medical number">
  					  </div>
              
  					  <div class="form-group">
    						<label for="exampleInputPassword1">Date of Birth</label>
    						<input type="text" class="form-control" id="exampleInputPassword1" name ="dob" placeholder="YYYY-MM-DD">
  					  </div>
  					  <button type="submit" name="submit" value="submit" class="btn btn-dark btn-lg btn-block">Submit</button>
  					</form>
  				</div>
  			</div>
  		</div>
  	</div>
  </body>
</html>