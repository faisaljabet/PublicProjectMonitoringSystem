<?php
    $conn = mysqli_connect('localhost', 'root', '', 'public_project_monitoring_system');
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $code = session::get("code");
    $role = session::get("role");
    /*
    if($role == "citizen"){
        $sql = "SELECT * FROM users WHERE code = $code";
        $res = $conn->query($sql);
        $row = $res->fetch_assoc();
    }
    else if($role == "EXEC"){
        $sql = "SELECT * FROM agencies WHERE code = ".$code;
        $res = $conn->query($sql);
        $row = $res->fetch_assoc();
    }
    else if($role == "APPROV"){
        $sql = "SELECT * FROM agencies WHERE code = ".$code;
        $res = $conn->query($sql);
        $row = $res->fetch_assoc();
    }
    else if($role == "admin"){
        $sql = "SELECT * FROM users WHERE code = $code";
        $res = $conn->query($sql);
        $row = $res->fetch_assoc();
    }*/
    
    if($role != $pageType)
    {
        header("Location:accessDenied.php");
    }
?>