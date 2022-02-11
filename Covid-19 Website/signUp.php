<?php
if(!isset($_SESSION)) {
    session_start();
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

    <title>COVID-19 - Sign Up Form</title>
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
                <h1 class="display-4">COVID-19 Person Form</h1>
                <p class="lead">Please fill out the COVID-19 form below.</p>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <div class="row mx-auto">
            <div  class="mx-auto">
                <div id="theForm">
                    <form method="POST">
                        <div class="r">
                            <label for="firstName">First Name:</label>
                            <input type="text" id="name" name="fname"> </br></br>
                        </div>
                        <div class="r">
                            <label for="lastName">Last Name:</label>
                            <input type="text" id="name" name="lname"> </br></br>
                        </div>
                        <div class="r">
                            <label for="dateOfBirth">Date Of Birth (Y-M-D):</label>
                            <input type="text" id="name" name="dob"> </br></br>
                        </div>
                        <div class="r">
                            <label for="email">Email:</label>
                            <input type="text" id="name" name="email"> </br></br>
                        </div>
                        <div class="r">
                            <label for="address">Address:</label>
                            <input type="text" id="name" name="address"> </br></br>
                        </div>
                        <div class="r">
                            <label for="phoneNumber">Phone Number:</label>
                            <input type="text" id="name" name="pnumber"> </br></br>
                        </div>
                        <div class="r">
                            <label for="city">City:</label>
                            <input type="text" id="name" name="city"> </br></br>
                        </div>
                        <div class="r">
                            <label for="postal">Postal Code:</label>
                            <input type="text" id="name" name="postal"> </br></br>
                        </div>
                        <div class="r">
                            <label for="province">Province:</label>
                            <input type="text" id="name" name="province"> </br></br>
                        </div>
                        <div class="r">
                            <label for="cs">Citizenship:</label>
                            <input type="text" id="name" name="citizenship"> </br></br>
                        </div>
                        <div class="r">
                            <label for="medicalNumber">Medical Number:</label>
                            <input type="number" id="name" name="medicalnum"> </br></br>
                        </div>
                        <button class="btn btn-dark btn-lg btn-block" name="submit" value="submit" type="submit">Submit</button>
                    </form>

                    <?php
                    include 'databaseConnection.php';
                    if(isset($_POST['submit'])) {
                        storeInfoInDB();
                    }

                    function storeInfoInDb() {
                        // Might be out of order. Check the order later
                        $fname = $_POST['fname'];
                        $lname = $_POST['lname'];
                        $dob = $_POST['dob'];
                        $email = $_POST['email'];
                        $address = $_POST['address'];
                        $pnumber = $_POST['pnumber'];
                        $city = $_POST['city'];
                        $postal = $_POST['postal'];
                        $province = $_POST['province'];
                        $citizenship = $_POST['citizenship'];
                        $medicalnum = $_POST['medicalnum'];

                        // Uncomment for Debug
                        /* echo "Medicare:".$medicalnum.'<br/>';
                        echo "fname:".$fname.'<br/>';
                        echo "lname:".$lname.'<br/>';
                        echo "dob:".$dob.'<br/>';
                        echo "email:".$email.'<br/>';
                        echo "address:".$address.'<br/>';
                        echo "pnum:".$pnumber.'<br/>';
                        echo "postal:".$postal.'<br/>';
                        echo "province:".$province.'<br/>';
                        echo "citizen:".$citizenship.'<br/>';
                        echo "city:".$city.'<br/>'; */

                        if (empty($fname) || empty($lname) || empty($dob) || empty($email) || empty($address) || empty($pnumber) || empty($city) ||
                            empty($postal) || empty($province) || empty($citizenship) || empty($medicalnum)) {
                            echo '<p>Fields are empty. Insert something!</p>';
                            return;
                        }

                        if (!(strpos($email, '@') !== false || strpos($email, '.com') !== false || strpos($email, '.ca') !== false)) {
                            return;
                        }

                        if (!(strpos($dob, '-') !== false)) {
                            echo '<p>Invalid Date of Birth! Please enter in the following format: Y-M-D (include -)</p>';
                            return;
                        }


                        // $queryCommand = 'INSERT INTO Person VALUES ("' . $medicalnum . '", "' . $fname . '", "' . $lname . '", "' . $email . '", "' . $address . '","' . $postal . '","' . $citizenship . '","' . $dob . '", "' . $pnumber . '")';
                        $inDatabase = isInDatabase($email, $medicalnum);

                        if(!$inDatabase) {
                            $postPersonArray = array($medicalnum, $fname, $lname, $email, $address, $postal, $citizenship, $dob, $pnumber);
                            $cityPostalArray = array($city, $postal);

                            $result = insertQuery('Person', $postPersonArray);
                            $result2 = insertQuery('CityPostal', $cityPostalArray);
                        }

                        else {
                            echo "<p>An existing account already has the same medicare number as the one you entered. If you forgot your password, it is your date of birth.</p>";
                            return;
                        }

                        if($result->num_rows > 0 && $result2->num_rows > 0) { // Fail to sign up
                            echo "<p>Medical Number already in DB, meaning an account is already made. If you forgot your password, it is your date of birth.</p>";
                        }
                    }

                    function isInDatabase($email, $medicalNumber): bool
                    {
                        $queryCommand = "SELECT email, medicalNumber FROM Person WHERE email = '".$email."' AND medicalNumber = $medicalNumber";

                        $result = executeQuery($queryCommand);

                        if($result->num_rows > 0) {
                            return true;
                        }

                        else {
                            echo "<p>Sign up successful! Your username will be your medicare number and your password is your date of birth.</p>"; // Debug
                            return false;
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>