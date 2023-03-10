<?php
    include 'user.php';
    include 'header.php';
    session::checksession();
?>
<?php
    if (isset($_GET['action']) && $_GET['action'] == "logout") {
        session::distroy();
    }
?>
<?php
    $session_id = session::get("id");
    $user = new user();
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
        $updateUser = $user->updateUserData($session_id, $_POST);
    }
?>
<?php
    $conn = mysqli_connect('localhost', 'root', '', 'public_project_monitoring_system');
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $current_id = isset($_GET['id']) ? $_GET['id'] : $session_id;
    $sql = "SELECT type FROM users WHERE id = $current_id and verification_status=1 and admin_verification_status=1";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    $current_type = $row['type'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ব্যবহারকারীর তথ্য</title>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>
<body>
    
    <!-- Navbar Start -->
    <?php
        include 'navbar.php';
    ?>
    <!-- Navbar End -->
    
    <div class="text-center mt-5">
        <h2>ব্যবহারকারীর তথ্য</h2>
    </div>
    
    <div class="container">
        <div style="max-width: 600px; margin: 0 auto">
        <?php
            if (isset($updateUser)) {
            echo $updateUser;
            $updateUser = "";
        }
        ?>
        <?php
            $userdata = $user->getuserbyid($current_id);
            if ($userdata) {
        ?>
            <form action="" method="POST">
                <div class="form-group mt-3">
                    <label for="name">নাম</label>
                    <input name="name" id="name" class="form-control" value="<?php echo $userdata->name; ?>" type="text" placeholder="Name" <?php if($current_id!=$session_id) echo "readonly";?> >
                </div>
                <div class="form-group mt-3">
                    <label for="mobile">মোবাইল</label>
                    <input name="mobile" id="mobile" class="form-control" value="<?php echo $userdata->mobile; ?>" type="text" placeholder="01XXXXXXXXX" <?php if($current_id!=$session_id) echo "readonly";?>>
                </div>
                <div class="from-group mt-3">
                    <?php
                        if ($current_id == $session_id) {
                    ?>
                        <input class="btn btn-success mt-3" type="submit" name="submit" value="আপডেট">
                    <?php } ?>
                </div>
            </form>
            <?php } ?>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
</body>
</html>