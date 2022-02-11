<?php
  if(!isset($_SESSION)){
    session_start();
  }
  
  if(!isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
      header("Location: https://bec353.encs.concordia.ca/");
  }
?>
<html>
  <?php
    include 'mailer.php';
    include 'publicHealthFacility.php';
  ?>
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="stylesheet.css">

    <title>Change Alert</title>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
  </head>
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
  <body>
  <?php
  include 'navbar.php';
  ?>
    <div class="container mt-5">
        <div class="row mx-auto">
            <div  class="mx-auto">
                <div id="theForm">
                      <form method = "POST" class="sizing">
                          <input type="text" placeholder="Region Name" name="search">
                          <button class="btn btn-dark" name="submit" value="submit" type="submit">Search</button>
                      </form>
                </div>
            </div>
        </div>
      </div>
      <div class="container mt-5">
          <div class="row mx-auto">
              <div class="mx-auto">
                <?php
                /**
                 * @param array $result
                 */
                $regionName = '';

                function createAlertTable(array $result)
                {
                    echo '<table>';
                    echo '<th>Region</th><th>Level</th><th>Time</th>';

                    for ($row = 0; $row < sizeof($result); $row++) {
                        echo '<tr>';
                        echo '<td>';
                        echo $result[$row]->name . "  ";
                        echo '</td>';
                        echo '<td>';
                        echo "  " . $result[$row]->alertLevel . "   ";
                        echo '</td>';
                        echo '</td>';
                        echo '<td>';
                        echo $result[$row]->alertDate;
                        echo '</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                }

                if(isset($_POST['submit']))
                  {
                          $region = $_POST['search'];
                          // "SELECT regionName, MAX(alertDate),alertLevel FROM Region WHERE regionName = '".$region."'"
                          $result = getRegionsFromName($region);
                          $_SESSION['regionName'] = $result[0]->name;

                          if(sizeof($result) > 0)
                          {
                              createAlertTable($result);
                          }

                          else
                          {
                            echo "<p> This region does not exist in our database</p>";
                          }
                    }
                elseif (isset($_SESSION['regionName']) && (!isset($_POST['increase']) && !isset($_POST['decrease']))) {
                    $result = getRegionsFromName($_SESSION['regionName']);
                    createAlertTable($result);
                }
                ?>
              </div>
          </div>
      </div>
      <div class="container">
          <div class="row mx-auto">
              <div class="mx-auto">
                  <form method = "POST" class="sizing">
                      <?php
                      /**
                       * @param $alertRegionName
                       * @param int $increasedAlert
                       * @return array[]
                       */
                      function generateEmail($alertRegionName, int $increasedAlert, $emailSubject)
                      {
                          $getMedNumQuery = executeQuery('SELECT medicalNumber FROM Person p, CityPostal cp, RegionCity rc WHERE p.postalCode = cp.postalCode AND rc.cityName = cp.cityName AND rc.regionName = "'.$alertRegionName.'"');
                          $medInRegions = array();

                          while ($row = $getMedNumQuery->fetch_assoc()) {
                              array_push($medInRegions, $row['medicalNumber']);
                          }

                          $guideline = null;

                          if ($increasedAlert == 4) {
                              $guideline = 'Be extra careful';
                          }

                          for ($index = 0; $index < sizeof($medInRegions); $index++) {
                              sendEmail($medInRegions[$index], $emailSubject, 'The alert level has been changed. Stay safe!', $guideline, true);
                          }
                      }

                      if(isset($_POST['increase']) || isset($_POST['decrease'])) {
                          $alertRegionName = $_SESSION['regionName'];

                          $mostRecentAlert = getRegionsFromName($alertRegionName);

                          $increasedAlert = $mostRecentAlert[0]->alertLevel;

                          if(isset($_POST['increase'])) {
                              $increasedAlert += 1;
                          }

                          elseif(isset($_POST['decrease'])) {
                              $increasedAlert -= 1;
                          }

                          $increasedAlertDate = date('Y-m-d H:i:s');

                          if($increasedAlert >= 1 && $increasedAlert <= 4) {
                              $alertArray = array($alertRegionName, $increasedAlert, $increasedAlertDate);
                              $queryResult = insertQuery('Region', $alertArray);

                              if($queryResult) {
                                  $regionResult = getRegionsFromName($alertRegionName);
                                  createAlertTable($regionResult);
                              }

                              if(isset($_POST['increase'])) {
                                  generateEmail($alertRegionName, $increasedAlert, 'Increased Alert');
                              }

                              elseif(isset($_POST['decrease'])) {
                                  generateEmail($alertRegionName, $increasedAlert, 'Decreased Alert');
                              }

                          }

                          else {
                              $regionResult = getRegionsFromName($alertRegionName);
                              createAlertTable($regionResult);
                          }
                      }
                      ?>
                      <button class="btn btn-dark mt-5" name="increase" value="submit" type="submit">Increase</button>
                      <button class="btn btn-dark mt-5" name="decrease" value="submit" type="submit">Decrease</button>
                  </form>
              </div>
          </div>
      </div>
</body>
</html>

