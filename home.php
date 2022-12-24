<?php
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    include 'user.php';
    include 'header.php';
    session::checksession();
?>

<?php
    if (isset($_GET['action']) && $_GET['action'] == "logout") {
        session::distroy(); 
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>Home Page</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
        integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
        integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
</head>
<body>
    <div class="container">
    <nav class="navbar navbar-expand-sm navbar-light" style="background-color: #e3f2fd;">
            <div class="container-fluid container">
                <a class="navbar-brand" href="home.php"><img src="images/logo1.png" alt="System logo" width="30" height="30" class="d-inline-block align-text-top">
                Public Project Monitoring System</a>
                <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active <?php if(session::get("code")!=null) echo 'invisible';?>" href="signUp.php">Sign Up</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active <?php if(session::get("code")!=null) echo 'invisible';?>" href="login.php">Log In</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active <?php if(session::get("code")==null) echo 'invisible';?>" href="#">Profile</a>
                        </li>
                        <li class="nav-item">
                    <a class="nav-link active<?php if(session::get("code")==null) echo 'invisible';?>" href="?action=logout">Log Out</a>
                </li>
                    </ul>
                </div>
            </div>
        </nav>



        <?php
        $hostname     = "localhost";
        $username     = "root";
        $password     = "";
        $databasename = "public_project_monitoring_system";
        // Create connection
        $conn = mysqli_connect($hostname, $username, $password,$databasename);
        // Check connection
        if (!$conn) {
            die("Unable to Connect database: " . mysqli_connect_error());
        }
        $db=$conn;
        // fetch query
        function fetch_data(){
        global $db;
        $query="SELECT * from projects ORDER BY start_date";
        $exec=mysqli_query($db, $query);


        if(mysqli_num_rows($exec)>0){
            $row= mysqli_fetch_all($exec, MYSQLI_ASSOC);
            return $row;  
        }else{
            return $row=[];
        }

        $row= mysqli_fetch_all($exec, MYSQLI_ASSOC);
        $array1 = array();
        $array2 = array();
        while ($row = mysql_fetch_assoc($exec)) {
            $array1[] = $row['latitude'];
            $array2[] = $row['longitude'];
        }
        echo 'const lat = '.json_encode($array1).';';
        echo 'const lng = '.json_encode($array2).';';


        }
        $fetchData= fetch_data();

        function show_data($fetchData){
        echo '<table border="1">
                <tr>
                <th>Lat</th>
                <th>Lng</th>
                </tr>';

        if(count($fetchData)>0){
            $sn=1;
            foreach($fetchData as $data){ 
        echo "<tr>
                <td>".$data['latitude']."</td>
                <td>".$data['longitude']."</td>
        </tr>";
            
        $sn++; 
            }
        }else{
            
        echo "<tr>
                <td colspan='7'>No Data Found</td>
            </tr>"; 
        }
        echo "</table>";
        }

        ?>


    <div id="map" style="width: 600px; height: 400px; margin:auto" class="mt-5"></div>

    
    <script>

        const map = L.map('map').setView([23.81, 90.41], 7);
        
        const project_id = ['proj1907', 'proj9956', 'proj3490', 'proj5020', 'proj7221', 'proj9280', 'proj8315', 'proj3936', 'proj5233', 'proj1644', 'proj4588', 'proj9145', 'proj4900', 'proj5731', 'proj5731', 'proj8071', 'proj1106', 'proj3469', 'proj9285', 'proj9488', 'proj9465', 'proj4903', 'proj5283', 'proj2669', 'proj7024', 'proj5660', 'proj3316', 'proj3348', 'proj7726', 'proj4907', 'proj1759', 'proj5852', 'proj7787', 'proj2298', 'proj7301', 'proj2485', 'proj2422', 'proj2754', 'proj5060', 'proj5838', 'proj4979', 'proj1181', 'proj9303', 'proj3281', 'proj5810', 'proj1810', 'proj3215', 'proj6016', 'proj8114', 'proj3971', 'proj8573', 'proj1092', 'proj7747', 'proj3806', 'proj1080', 'proj1430', 'proj8816', 'proj1227', 'proj8932', 'proj9843', 'proj1587', 'proj4048', 'proj4968', 'proj4224', 'proj9906', 'proj3072', 'proj3120', 'proj7823', 'proj3331', 'proj3778', 'proj2357', 'proj9099', 'proj6981', 'proj5770', 'proj8293', 'proj3213', 'proj5458', 'proj5114', 'proj6241', 'proj8961', 'proj4207', 'proj4716', 'proj8626', 'proj7688', 'proj3724', 'proj1718', 'proj5028', 'proj5373', 'proj8106', 'proj8269', 'proj6393', 'proj8267', 'proj8325', 'proj5730', 'proj5951', 'proj3173', 'proj1206', 'proj4932', 'proj4849', 'proj6752', 'proj2458', 'proj5547', 'proj6294', 'proj7969', 'proj1235', 'proj2090', 'proj5711', 'proj8160', 'proj5163', 'proj7741', 'proj9522', 'proj5876', 'proj5414', 'proj5879', 'proj2387', 'proj3407', 'proj4482', 'proj9681', 'proj1233', 'proj6340', 'proj4893', 'proj2669', 'proj4919', 'proj5197', 'proj5580', 'proj4135', 'proj6782', 'proj2607', 'proj4988', 'proj5450', 'proj1655', 'proj6242', 'proj9736', 'proj5145', 'proj7575', 'proj7880'];
        const lat = [26.469149, 26.4910106, 26.5086811, 26.5540509, 23.53025, 23.61931959, 23.84080923, 26.55872, 23.8385501, 23.605972, 23.7488533, 26.4940049, 26.50265452, 26.50265452, 23.8385501, 23.81429356, 26.5086811, 26.4959354, 26.4910106, 26.4940049, 23.7293586, 26.5162188, 23.7469498, 23.88374878, 24.065167, 23.8346268, 23.8091655, 26.5083906, 23.8091655, 23.8114074, 23.8178692, 24.065167, 23.8276418, 23.8768134, 26.5524062, 23.799557, 26.541235, 23.7293586, 23.8363742, 23.882795, 23.8352253, 26.5083906, 24.887556, 23.71440803, 23.8641457, 23.8066314, 23.8878345, 26.482545, 23.7812657, 23.8316428, 23.8569547, 26.482545, 23.8878345, 23.8915917, 23.7812657, 23.81429356, 26.4928829, 23.8353554, 26.5324052, 23.8779349, 23.8276418, 23.81165412, 23.7764662, 23.8276418, 23.8709465, 22.395861, 26.5030717, 26.5886617, 23.8352253, 26.48788874, 26.5540509, 26.5886617, 23.8066314, 23.8792481, 26.51410659, 23.8414317, 23.8639508, 26.525549, 23.8709465, 26.5369314, 23.7293586, 22.3253, 22.465417, 23.83284605, 23.7488533, 26.49840733, 23.82537587, 23.865362, 23.8569547, 26.4928829, 26.49784, 26.530352, 26.4473367, 24.044611, 26.49840733, 23.7812657, 26.469149, 23.81429356, 26.48788874, 23.8353554, 26.4695022, 26.4834629, 26.5788339, 26.5372393, 23.548083, 26.5788339, 26.5030717, 23.8768134, 23.8259202, 26.4473367, 23.865362, 23.703278, 26.4204885, 26.4834629, 26.4897783, 23.8639508, 23.8779349, 23.61931959, 26.5540509, 23.83284605, 23.82537587, 23.569528, 26.49840733, 23.8641457, 26.4897783, 26.525549, 23.169639, 23.8414317, 26.5788339, 26.5162188, 23.569528, 26.525549, 23.8017158, 26.49784, 26.5324052, 23.83284605];
        const lng = [88.483176, 88.396602, 88.4719455, 88.396669, 90.702222, 90.50365683, 90.58791205, 88.426121, 90.4902048, 90.613944, 90.553965, 88.4721835, 88.37025024, 88.37025024, 90.4902048, 90.55622879, 88.4719455, 88.3880342, 88.396602, 88.4721835, 90.5089105, 88.3886477, 90.5417278, 90.59371233, 89.029556, 90.4883393, 90.5358279, 88.38943675, 90.5358279, 90.5474247, 90.5385196, 89.029556, 90.5030257, 90.5353583, 88.37556671, 90.5448202, 88.387245, 90.5089105, 90.5696117, 90.5764378, 90.5276705, 88.38943675, 91.868, 90.51711917, 90.4105208, 90.5701778, 90.5722163, 88.4748633, 90.5321705, 90.558248, 90.5536643, 88.4748633, 90.5722163, 90.5606133, 90.5321705, 90.55622879, 88.3382535, 90.5554791, 88.3649777, 90.5878684, 90.5030257, 90.41992933, 90.5933341, 90.5030257, 90.5207756, 91.888944, 88.510585, 88.3946118, 90.5276705, 88.36001292, 88.396669, 88.3946118, 90.5701778, 90.553981, 88.48702256, 90.5716043, 90.5646438, 88.3691627, 90.5207756, 88.3659623, 90.5089105, 91.8532, 90.341083, 90.54201074, 90.553965, 88.50178946, 90.52254964, 90.5619884, 90.5536643, 88.3382535, 88.33755, 88.4092735, 88.49846, 90.995778, 88.50178946, 90.5321705, 88.483176, 90.55622879, 88.36001292, 90.5554791, 88.4868529, 88.421677, 88.38756893, 88.393252, 90.47725, 88.38756893, 88.510585, 90.5353583, 90.5530399, 88.49846, 90.5619884, 90.517611, 88.5061948, 88.421677, 88.5119467, 90.5646438, 90.5878684, 90.50365683, 88.396669, 90.54201074, 90.52254964, 90.512389, 88.50178946, 90.4105208, 88.5119467, 88.3691627, 90.237556, 90.5716043, 88.38756893, 88.3886477, 90.512389, 88.3691627, 90.492804, 88.33755, 88.3649777, 90.54201074];
        const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        const marker = [];
        for(let i=0; i<lat.length; i++){
            const m = L.marker([parseFloat(lat[i]), lng[i]]).addTo(map)
            .bindPopup('<a href="#">'+project_id[i]+'</a>');
            marker.push(m);
        }

        function onMapClick(e) {
            popup
                .setLatLng(e.latlng)
                .setContent(`You clicked the map at ${e.latlng.toString()}`)
                .openOn(map);
        }

        map.on('click', onMapClick);

    </script>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="ajax-script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
</body>
</html>