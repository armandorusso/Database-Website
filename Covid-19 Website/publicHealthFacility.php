<?php
  function generateRadios($appointmentOnly){
    $result = null;
    
    if($appointmentOnly){
      $result = executeQuery('SELECT name FROM PublicHealthFacility WHERE acceptsAppointments = true');
    }
    else{
      $result = executeQuery('SELECT name FROM PublicHealthFacility');
    }
    
    while($row = $result -> fetch_assoc()){
      echo '<input type="radio" id="'.$row['name'].'" name="publicHealthFacility" value="'.$row['name'].'">';
      echo'<label for="'.$row['name'].'">'.$row['name'].'</label><br>';
    }
  }
  
  function generateFacilityTable(){
    $check = "&#x2713";
    $cross = "X";
    
    $result = executeQuery("SELECT name, hasDriveThru, acceptsWalkIn, acceptsAppointments FROM PublicHealthFacility");
    
    echo '<table>';
    echo '<tr>';
    echo '<th>Hospital Name </th>';
    echo '<th>Drive-Thru</th>';
    echo '<th>Walk-In</th>';
    echo '<th>Accepts Appointment</th>';
    echo '</tr>';
    
    if($result->num_rows>0){
      while($row = $result -> fetch_assoc()){
        $HDT = '';
        $AWI = '';
        $APT = '';
        
        if($row["hasDriveThru"] == 0){
          $HDT = $cross;
        }
        else {
          $HDT = $check;
        }
        
        if($row["acceptsWalkIn"] == 0){
          $AWI = $cross;
        }
        else {
          $AWI = $check;
        }
        
        if($row["acceptsAppointments"] == 0){
          $APT = $cross;
        }
        else {
          $APT = $check;
        }
        
        echo"<tr><td>". $row["name"] ."</td><td>". $HDT."</td><td>". $AWI."</td><td>". $APT."</td></tr> ";
      }
    }
    echo '</table>';
  }
?>