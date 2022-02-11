<?php
  if(!isset($_SESSION)){
    session_start();
  }
  
  include 'databaseConnection.php';
  
  class Application{
    public $id;
    public $medicalNumber;
    public $submissionDate;
  }
  
  if(!isset($_SESSION['loginID'])){
    header('index.php');
  }
  else{
    $result = executeQuery('SELECT * FROM Application WHERE medicalNumber = '.$_SESSION['loginID']);
    $applications = array();
    
    while($row = $result->fetch_assoc()){
      $application = new Application();
      $application->id = $row['applicationID'];
      $application->medicalNumber = $row['medicalNumber'];
      $application->submissionDate = $row['submissionDate'];
      
      array_push($applications, $application);
    }
    $result = executeQuery('SELECT * FROM Symptoms s, Application a WHERE s.applicationID = a.applicationID AND a.medicalNumber = '.$_SESSION['loginID']);
    
    $symptomIDs = array();
    
    while($row = $result->fetch_assoc()){
      $symptomID = $row['applicationID'];
      array_push($symptomIDs, $symptomID);
    }
    
    $needsSymptoms = array();
    
    for($i = 0; $i < sizeof($applications); $i++){
      $foundSymptoms = false;
      for($j = 0; $j < sizeof($symptomIDs); $j++){
        if($applications[$i]->id == $symptomIDs[$j]){
          $foundSymptoms = true;
          break;
        }
      }
      
      if(!$foundSymptoms){
        array_push($needsSymptoms, $applications[$i]);
      }
    }
    
    if(sizeof($needsSymptoms)>0){
      usort($needsSymptoms, function($a, $b) {
        return strtotime($b->submissionDate) - strtotime($a->submissionDate);
      });
      
      if(isset($_POST['submit'])){
        $data = array();
        
        array_push($data, $needsSymptoms[0]->id);
        array_push($data, date('Y-m-d H:i:s'));
        array_push($data, null);
        array_push($data, isset($_POST['fever']));
        array_push($data, isset($_POST['cough']));
        array_push($data, isset($_POST['breath']));
        array_push($data, isset($_POST['tasteSmell']));
        array_push($data, isset($_POST['nauseau']));
        array_push($data, isset($_POST['stomachAche']));
        array_push($data, isset($_POST['vomiting']));
        array_push($data, isset($_POST['headache']));
        array_push($data, isset($_POST['muscles']));
        array_push($data, isset($_POST['diarrhea']));
        array_push($data, isset($_POST['soreThroat']));
        array_push($data, $_POST['otherSymptoms']);
        array_push($data, false);
        
        insertQuery('Symptoms', $data);
      }
    }
    else{
      header('index.php');
    }
  }
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
  
    <title>INDEX</title>
  </head>
  <body>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
  	
    <?php
      
    ?>
   
  	<?php
        include 'navbar.php';
    ?>
  
  	<div> 
  		<div class="jumbotron jumbotron-fluid mt-5" >
  			<div class="container center">
  				<h1 class="display-4">COVID-19 Symptom Form</h1>
  				<p class="lead">Please fill out the COVID-19 form below.</p>
  			</div>
  		</div>
  	</div>
  	
  	<div class="container mt-5">
  		<div class="row mx-auto">
			  <div  class="mx-auto">
				  <div id="theForm">
  					<form method = 'POST'>	
    					<h2> Common Symptoms </h2>
  						<div class="form-check pb-2">
  						  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="fever">
  						  <label class="form-check-label" for="flexCheckDefault">
  							  Fever(Temperature higher than 38.1 degrees)
  						  </label>
  						</div>
					
  						<div class="form-check pb-2">
  						  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="cough">
  							<label class="form-check-label" for="flexCheckDefault">
  							  Cough
  							</label>
  						</div>
					
  						<div class="form-check pb-2">
  						  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="breath">
  						  <label class="form-check-label" for="flexCheckDefault">
  							  Shortness Of Breath Or Difficulty Breathing
  						  </label>
  						</div>
  						
  						<div class="form-check pb-2">
  						  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="tasteSmell">
  							<label class="form-check-label" for="flexCheckDefault">
  							  Loss Of Taste And Smell
  							</label>
  						</div>
					
					    </br> 
						
						  <h2> Uncommon Symptoms </h2>
						
  						<div class="form-check pb-2">
  						  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="nausea">
  						  <label class="form-check-label" for="flexCheckDefault">
  							  Nausea
  						  </label>
  						</div>
						
  						<div class="form-check pb-2">
  						  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="stomachAche">
  							<label class="form-check-label" for="flexCheckDefault">
  							  Stomach Aches
  							</label>
  						</div>
						
  						<div class="form-check pb-2">
  						  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="vomiting">
  						  <label class="form-check-label" for="flexCheckDefault">
  							  Vomitting
  						  </label>
  						</div>
						
  						<div class="form-check pb-2">
  						  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="headache">
  							<label class="form-check-label" for="flexCheckDefault">
  							  Headache
  							</label>
  						</div>
						
						
  						<div class="form-check pb-2">
  						  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="muscles">
  							<label class="form-check-label" for="flexCheckDefault">
  							  Muscle Pain
  							</label>
  						</div>
						
  						<div class="form-check pb-2">
  						  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="diarrhea">
  						  <label class="form-check-label" for="flexCheckDefault">
  							  Diarrhea 
  						  </label>
  						</div>
						
  						<div class="form-check pb-2">
  						  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="soreThroat">
  							<label class="form-check-label" for="flexCheckDefault">
  							  Sore Throat
  							</label>
  						</div>
						
						  </br> 
						
						  <h2> Other Symptoms </h2>
						
						  <div class="form-group">
							  <label for="exampleFormControlTextarea1">List all other symptoms that were not mentioned.</label>
							  <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="otherSymptoms"></textarea>
						  </div>
						  <button class="btn btn-dark btn-lg btn-block" type="submit" name="submit">Submit</button>
  					</form>
  				</div>
  			</div>
  		</div>
  	</div>
  </body>
</html>