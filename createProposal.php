<?php
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    include 'user.php';
    include 'header.php';
    session::checksession();

    $pageType = 'EXEC';
    include 'individualSessionCheck.php';
?>

<?php
    $conn = mysqli_connect('localhost', 'root', '', 'public_project_monitoring_system');
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $code = session::get("code");
    $sql = 'SELECT * FROM agencies WHERE code ="'.$code.'"';
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    $name = $row['name'];
?>

<?php
    $loginmgs = session::get("loginmgs");
    if (isset($loginmgs)) {
        echo $loginmgs;
    }
    session::set("loginmgs",NULL);
?>
<?php
    if (isset($_GET['action']) && $_GET['action'] == "logout") {
        session::distroy(); 
    }
?>
<?php
    $db = mysqli_connect("localhost","root","","public_project_monitoring_system");

    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $location = $_POST['location'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $cost = $_POST['cost'];
        $timespan = $_POST['timespan'];
        $goal = $_POST['goal'];
        $exec = session::get("code");
        $proposal_date = date("d-m-Y");
        $user_code = session::get("code");
        $project_id = "prop".time();
        $date = date("Y-m-d");
        $status = "unseen";

        $msg =  "<div class='alert alert-success'><strong>Successfully submitted!</strong></div>";
        
        $query = "INSERT INTO proposals (name, location, latitude, longitude, cost, timespan, goal, exec, proposal_date, project_id) VALUES ('$name', '$location', '$latitude', '$longitude', '$cost', '$timespan', '$goal', '$exec', '$proposal_date', '$project_id')";
    
        $run = mysqli_query($db, $query);
        session::set("loginmgs", $msg);
        $_SESSION['status'] = "Data Inserted";
        $msg =  "<div class='alert alert-success'><strong>>আপনার বাজেট আবেদন সম্পন্ন হয়েছে</strong></div>";
        header("Location: executiveagencyIndex.php");
    }
?>

<html>
<head>
    <meta charset="UTF-8">
        <title>Create Proposal</title>
        <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="css/descriptionOfDemand.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="text/javascript" src="dist/jautocalc.js"></script>
</head>
<body>

    <!-- Navbar Start -->
    <?php
        include 'navbar.php';
    ?>
    <!-- Navbar End -->
    
    <h3 class="display-5 text-center my-1">Create Proposal</h3>
    <div class="container">
        <div style="max-width: 600px; margin: 0 auto">
            <?php
                if (isset($userLogin)) {
                    echo $userLogin;
                }
            ?>
            <strong>
            <form action="" method="POST">
                <div class="form-group mt-1">
                    <label for="name">Name</label>
                    <input name="name" id="name" class="form-control" type="name" placeholder="Name">
                </div>
                <div class="form-group mt-2">
                    <label for="location">Location</label>
                    <input name="location" id="location" class="form-control" type="location" placeholder="Location">
                </div>
                <div class="form-group mt-2">
                    <label for="latitude">Latitude</label>
                    <input name="latitude" id="latitude" class="form-control" type="latitude" placeholder="Latitude">
                </div>
                <div class="form-group mt-2">
                    <label for="longitude">Longitude</label>
                    <input name="longitude" id="longitude" class="form-control" type="longitude" placeholder="Longitude">
                </div>
                <div class="form-group mt-2">
                    <label for="cost">Budget</label>
                    <input name="cost" id="cost" class="form-control" type="cost" placeholder="In crore">
                </div>
                <div class="form-group mt-2">
                    <label for="timespan">Time Span</label>
                    <input name="timespan" id="timespan" class="form-control" type="timespan" placeholder="in Year">
                </div>
                <div class="form-group mt-2">
                    <label for="goal">Service Field</label>
                    <input name="goal" id="goal" class="form-control" type="goal" placeholder="Goal">
                </div>
                <div class="form-group mt-3">
                    <input name="submit" class="btn btn-success mt-3 mb-3 justify-content-center" type="submit" value="Submit">
                </div>
            </form>
            </strong>
        </div>
    </div>
    
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    </body>
</html>
