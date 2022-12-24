<?php
    $db = mysqli_connect("localhost","root","","public_project_monitoring_system");
    $code = session::get("code");
    $sql = "SELECT * FROM users WHERE code='$code'";
    $result = $db->query($sql);
    $row = $result-> fetch_assoc();
    $pageLink = "executiveagencyIndex.php";
?>

<div class="container">
    <nav class="navbar navbar-expand-sm navbar-light" style="background-color: #e3f2fd;">
    <div class="container-fluid container">
                <a class="navbar-brand" href="home.php"><img src="images/logo1.png" alt="System logo" width="30" height="30" class="d-inline-block align-text-top">
                Public Project Monitoring System</a>
                <ul class="navbar-nav">
                <?php
                    $id = session::get("code");
                    $userlogin = session::get("login");
                    if ($userlogin == true) {
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="home.php">Home</a>
                </li>
                <?php
                //Checking if current page is user's own profile pages
                $pageName = basename($_SERVER['PHP_SELF']);
                $match = "#";
                $current_user_code = isset($_GET['code']) ? $_GET['code'] : $code;
                if($pageName == $match && $current_user_code == $user_code)
                    $isOwnProfilePage = true;
                else
                    $isOwnProfilePage = false;
                ?>
                <li class="nav-item">
                    <a class="nav-link <?php if($isOwnProfilePage == true){echo "disabled";}?>" href="#?code=<?php echo $code; ?>">Profile</a>
                </li>
                <?php
                //Checking if current page is one of the index pages
                $pageName = basename($_SERVER['PHP_SELF']);
                $pageName = strtolower($pageName);
                $match = "index";
                $isIndexPage = false;
                for($i=0; $i<strlen($pageName); $i++)
                {
                    $flag = true;
                    for($j=0; $j<strlen($match); $j++)
                    {
                        if($pageName[$i] != $match[$j])
                        {
                            $flag = false;
                            break;
                        }
                        else
                        {
                            $i++;
                        }
                    }
                    if($flag == true)
                    {
                        $isIndexPage = true;
                        break;
                    }
                }
                ?>
                <li class="nav-item">
                    <a class="nav-link <?php if($isIndexPage == true){echo "disabled";}?>" href="<?php echo $pageLink;?>">Index</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?action=logout">Log Out</a>
                </li>
                <?php }else{ ?>
                <li class="nav-item">
                    <a class="nav-link" href="home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Log In</a>
                </li>
                <?php } ?>
            </ul>
        </div>
    </nav>
</div>