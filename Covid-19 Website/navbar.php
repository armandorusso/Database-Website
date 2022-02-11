<?php
  if(!isset($_SESSION)) { 
    session_start(); 
  }
  
  if(isset($_POST['logout'])){
    $_SESSION['loginID'] = null;
    $_SESSION['isAdmin'] = null;
  }
?>
<html> 
  <head> 
    <style> 
      .putRight{ color:red;}
    </style>
  </head>
</html>

<div>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	  <div class="container-fluid">
			<a class="navbar-brand" href="#">COVID-19</a>
			
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" 		aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			
			<div class="collapse navbar-collapse" id="navbarNavAltMarkup">
			  <div class="navbar-nav">
					<a class="nav-link" href="index.php">Home</a>
					<a class="nav-link" href="appointment.php">Schedule Appointment</a>
          <a class="nav-link" href="symptomQuestionnaire.php">Symptom Questionnaire</a>
          <a class="nav-link" href="followUpForm.php">Follow Up Form</a>
         
          <?php
            //aria-current="page"
			      //<a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
            
            if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']){
              echo "<a class='nav-link ' href='application.php'>Applications</a>";
              echo "<a class='nav-link ' href='diagnostic.php'>Diagnostics</a>";
              echo "<a class='nav-link ' href='changeAlert.php'>Regions</a>";
              echo "<a class='nav-link ' href='modifyHealthInformation.php'>Health Information</a>";
            }
            
            if(isset($_SESSION['loginID'])){
              echo '<form method = "POST">';
              echo '<button class="nav-link putRight" name="logout" value="logout" type="submit">Logout</button>';
              echo '</form>';
            }
            else{
              echo "<a class='nav-link ' href='login.php'>Login</a>";
              echo "<a class='nav-link ' href='signUp.php'>Sign Up</a>";
            }
          ?>
		    </div>
			</div>
	  </div>
	</nav>
</div>