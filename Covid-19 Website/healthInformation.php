<?php
  include 'databaseConnection.php';
  
  class HealthInformation{
      public $key;
      public $information;
  }
  
  function generateHealthInfoBullets(){
    $parentElement = null;
    $toProcess = array();
    $processed = array();

    $result = executeQuery('SELECT * FROM HealthInformation');

    while($row = $result->fetch_assoc()) {
        $healthInfo = new HealthInformation();
        $healthInfo->key = $row['infoID'];
        $healthInfo->information = $row['information'];

        array_push($toProcess, $healthInfo);
    }

    if(empty($toProcess)){
        return;
    }

    $currentElement = $toProcess[0];

    echo '<ul>';
    while($currentElement != null){
        $result = executeQuery('SELECT parentInfoID FROM InformationBulletParent WHERE childInfoID = '.$currentElement->key);
        $row = $result->fetch_assoc();
        $potentialKey = $row['parentInfoID'];

        $availableParent = findInformation($toProcess, array($potentialKey));

        if(!is_null($availableParent)){
            $currentElement = $availableParent;
        }
        else{
            echo '<li>'.$currentElement->information;

            array_push($processed, $currentElement);
            $toProcess = removeFromArray($toProcess, $currentElement);

            $flag = true;
            if(!is_null($parentElement)){
                $result = executeQuery('SELECT childInfoID FROM InformationBulletParent WHERE parentInfoID = '.$parentElement->key);

                $searchKeys = array();
                while($row = $result->fetch_assoc()){
                    array_push($searchKeys, $row['childInfoID']);
                }

                $currentElement = findInformation($toProcess, $searchKeys);
                if(!is_null($currentElement)){
                    $flag = false;
                }
                else{
                    echo '</ul>';
                }
            }
            if($flag) {
                $result = executeQuery('SELECT childInfoID FROM InformationBulletParent WHERE parentInfoID = ' . $currentElement->key);
                if ($result->num_rows > 0) {

                    echo '<ul>';
                    $searchKeys = array();
                    while($row = $result->fetch_assoc()){
                        array_push($searchKeys, $row['childInfoID']);
                    }
                    
                    $parentElement = $currentElement;
                    $currentElement = findInformation($toProcess, $searchKeys);
                }
                else{
                    echo '</li>';

                    $parentElement = null;
                    if(sizeof($toProcess) > 0){
                        $currentElement = $toProcess[0];
                    }
                    else{
                        $currentElement = null;
                        break;
                    }
                }
            }
        }
    }
    echo '</ul>';
  }

  function generateHealthInfo(){
    $result = executeQuery('SELECT * FROM HealthInformation');
  
    echo '<table>';
    echo '<tr><td>ID</td><td>Information</td></tr>';
    while($row = $result->fetch_assoc()) {
      echo '<tr>';
      echo '<td>'; 
      echo $row['infoID'];
      echo '</td>';
      echo '<td>';
      echo $row['information'];
      echo '</td>';
      echo '</tr>';
    }
    echo '</table>';
  }
  
  function generateParentChild(){
    $result = executeQuery('SELECT * FROM InformationBulletParent');
  
    echo '<table>';
    echo '<tr><td>Parent ID</td><td>Child ID</td></tr>';
    while($row = $result->fetch_assoc()) {
      echo '<tr>';
      echo '<td>'; 
      echo $row['parentInfoID'];
      echo '</td>';
      echo '<td>';
      echo $row['childInfoID'];
      echo '</td>';
      echo '</tr>';
    }
    echo '</table>';
  }
  
  function firstAvailableID(){
    $result = executeQuery('SELECT infoID FROM HealthInformation');
    
    $existingIDs = array();
    while($row = $result->fetch_assoc()) {
      array_push($existingIDs, $row['infoID']);
    }
    
    $searching = true;
    $id = 1;
    while($searching){
      $found = false;
      for($i = 0; $i < sizeof($existingIDs); $i++){
        if($id == $existingIDs[$i]){
          $found = true;
          break;
        }
      }
      if(!$found){
        $searching = false;
      }
      else{
        $id++;
      }
    }
    return $id;
  }
  
  function removeFromArray($list, $element){
      $toReturn = array();
      for($i = 0; $i < sizeof($list); $i++){
          if($list[$i]->key !== $element->key){
              array_push($toReturn, $list[$i]);
          }
      }
      return $toReturn;
  }
  
  function findInformation($list, $searchKeys){
      for($i = 0; $i < sizeof($list); $i++){
          for($j = 0; $j < sizeof($searchKeys); $j++){
              if($list[$i]->key == $searchKeys[$j]){
                  return $list[$i];
              }
          }
      }
      return null;
  }
?>