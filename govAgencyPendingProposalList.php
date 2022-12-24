<?php
    include 'user.php';
    include 'header.php';
    session::checksession();

    $pageType = 'APPROV';
    include 'individualSessionCheck.php';
?>
<?php
    $loginmgs = session::get("loginmgs");
    if (isset($loginmgs)) {
        echo $loginmgs;
    }
    session::set("loginmgs",NULL);
?>

<?php
    $conn = mysqli_connect('localhost', 'root', '', 'public_project_monitoring_system');
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
	$limit = 25;
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
    if($page < 1)
        $page = 1;
	$start = ($page - 1) * $limit;
    $code = session::get("code");

	$result = $conn->query("SELECT * FROM proposals ORDER BY proposal_date LIMIT $start, $limit");
    $proposals = $result->fetch_all(MYSQLI_ASSOC);

	$result1 = $conn->query("SELECT count(project_id) AS id FROM proposals");
	$custCount = $result1->fetch_all(MYSQLI_ASSOC);
	$total = $custCount[0]['id'];
	$pages = ceil( $total / $limit );

	$Previous = $page - 1;
	$Next = $page + 1;
 ?>


<?php
    if (isset($_GET['action']) && $_GET['action'] == "logout") {
        session::distroy(); 
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Executive Agency Index</title>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>
<?php
    if (isset($_GET['action']) && $_GET['action'] == "logout") {
        session::distroy(); 
    }
?>
<body>
    <?php
        include 'navbar.php';
    ?>
    <div class="panel-heading">
            <h3 class="text-center mt-3">Pending Requests</h3>
    </div>
    <div class="container">
        <div style="margin: 0 auto">
            <form>
                <!-- Pagination Start -->
                <div class="row">
                    <div class="col-md-10">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination pagination-sm">
                                <li class="page-item <?php if($Previous == 0):?> disabled <?php endif; ?>">
                                    <a class="page-link" href="govAgencyPendingProposalList.php".php?page=<?= $Previous; ?>" aria-label="Previous">
                                        <span class = "page-link" aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <?php for($i = 1; $i<= $pages; $i++) : ?>
                                        <li class="page-item <?php if($i == $page):?> active <?php endif; ?>">
                                            <a class="page-link" href="govAgencyPendingProposalList.php?page=<?= $i; ?>"> 
                                                <span class = "page-link"> <?= $i; ?> </span>
                                            </a>
                                        </li>
                                <?php endfor; ?>
                                <li class="page-item <?php if($Next == $pages+1):?> disabled <?php endif; ?>">
                                    <a class="page-link" href="govAgencyPendingProposalList.php?page=<?= $Next; ?>" aria-label="Next">
                                        <span class = "page-link" aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Pagination End -->

                <!-- Table Start -->
                <div style="height: 400px; overflow-y: auto;">
                    <table id="" class="table table-striped table-bordered">
                        <thead class="table-success text-center">
                            <tr>
                                <th>No.</th>
                                <th>Project ID</th>
                                <th>Agency Name</th>
                                <th>Date of Proposal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $count = $start;
                                foreach($proposals as $proposal) :  ?>
                                <tr>
                                    <td class="text-center"><?= $count = $count+1; ?></td>
                                    <?php
                                        $code = $proposal['exec'];
                                        $sql = 'SELECT * FROM agencies WHERE code = "'.$code.'" LIMIT 1';
                                        $res =  $conn->query($sql);
                                        $row = $res->fetch_assoc();
                                        $name = $row['name'];
                                        $project_id = $proposal['project_id'];
                                    ?>
                                    <td class="text-center"><?= $project_id; ?></td>
                                    <td class="text-center"><?= $name; ?></td>
                                    <td class="text-center"><?= $proposal['proposal_date']; ?></td>
                                    <td class="text-center">
                                        <a href="govAgencyPendingProposalList.php?id=<?= $proposal['project_id'];?>">   
                                        <span class="btn btn-outline-success btn-sm"> View </span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Table End -->
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>

</body>
</html>    