<?php
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    include_once 'session.php';
    include 'database.php';
    include ('smtp/PHPMailerAutoload.php');

class user{
    private $db;
    public function __construct(){
        $this->db = new database();
    }
/*----------------------------------------
        user registration start
------------------------------------------*/
    public function userRegistration($data)
    {
        $name   = $data['name'];
        $email  = $data['email'];
        $code = $data['code'];
        $password   = md5($data['password']);
        $verification_status = false;

        $chk_email = $this->emailCheck($email);

        if ($name == "" or $email == "" or $code == "" or $password == "") {
            $mgs = "<div class='alert alert-danger'><strong>Error!</strong> Field must not be Empty</div>";
            return $mgs;
        }

        if (strlen($name)<3) {
            $mgs = "<div class='alert alert-danger'><strong>Error!</strong> User name is too Short!</div>";
            return $mgs;
        }

        /*if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $mgs = "<div class='alert alert-danger'><strong>Error! </strong>The email address is already Exist!</div>";
            return $mgs;
        } */

        if ($chk_email == true) {
            $mgs = "<div class='alert alert-danger'><strong>Error! </strong>The email address is already exists!</div>";
            return $mgs;
        }

        if($this->codeCheck($code)){
            $mgs = "<div class='alert alert-danger'><strong>Error! </strong>The handle is already used!</div>";
            return $mgs;
        }


        $sql = "INSERT INTO users (name, email, code, password) VALUES(:name, :email, :code, :password)";
        $query = $this->db->pdo->prepare($sql);
        $query->bindValue(':name',$name);
        $query->bindValue(':email',$email);
        $query->bindValue(':code',$code);
        $query->bindValue(':password',$password);
        $result = $query->execute();

        if ($result) {
            $conn = mysqli_connect('localhost', 'root', '', 'public_project_monitoring_system');
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $sql = "SELECT * FROM users WHERE email = '$email'";
            $res =  $conn->query($sql);
            $row = $res->fetch_assoc();
            $code = $row['code'];
            $str = $row['code'] . $email . $name;
            $verification_id = hash('md5', $str);
            $sql = "UPDATE users set verification_id='$verification_id' where code='$code'";
            $conn->query($sql);
            $conn->close();

            $mailHtml = "Please confirm your registration by clicking the button bellow: <br><a href='http://localhost/accountAutomationSystem/verification.php?verification_id=$verification_id'><input name='submit' class='btn btn-primary mt-4' type='submit' value='Verify your account'></a>";
            
            if($this->smtp_mailer($email, 'account Verification', $mailHtml) == true)
            {
                $msg = "<div class='alert alert-success'><strong>Success! </strong>We've sent you a confirmation email.</div>";
                return $msg;
            }
            else
            {
                return "Cannot send mail";
            }
        }
        else{
            $msg = "<div class='alert alert-danger'><strong>Error!</strong> Sorry! There have been problem inserting your details!</div>";
            return $msg;
        }
    }

    public function smtp_mailer($to, $subject, $msg){
        $mail = new PHPMailer(); 
        $mail->IsSMTP(); 
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true; 
        $mail->SMTPSecure = 'tls'; 
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587; 
        $mail->IsHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Username = "shiftingsolution35@gmail.com";
        $mail->Password = "fsdqzcialtrtiosw";
        $mail->SetFrom("shiftingsolution35@gmail.com");
        $mail->Subject = $subject;
        $mail->Body = $msg;
        $mail->AddAddress($to);
        $mail->SMTPOptions=array('ssl'=>array(
            'verify_peer'=>false,
            'verify_peer_name'=>false,
            'allow_self_signed'=>false
        ));

        if(!$mail->Send()){
            return false;
        }else{
            return true;
        }
    }

    public function emailCheck($email){
        $sql = "SELECT email FROM users WHERE email = :email";
        $query = $this->db->pdo->prepare($sql);
        $query->bindValue(':email',$email);
        $query->execute();
        if ($query->rowCount()>0) {
            return true;
        }
        else{
            return false;
        }
    }
    
    public function codeCheck($code){
        $sql = "SELECT code FROM users WHERE code = :code";
        $query = $this->db->pdo->prepare($sql);
        $query->bindValue(':code',$code);
        $query->execute();
        if ($query->rowCount()>0) {
            return true;
        }
        else{
            $sql = "SELECT code FROM agencies WHERE code = :code";
            $query = $this->db->pdo->prepare($sql);
            $query->bindValue(':code',$code);
            $query->execute();
            if ($query->rowCount()>0) {
                return true;
            }
            return false;
        }
    }
/*----------------------------------------
        user registration end
------------------------------------------*/

/*----------------------------------------
            user login start
------------------------------------------*/

    public function getLoginUser($code,$password){
        $sql = "SELECT * FROM users WHERE code = :code AND password = :password LIMIT 1";
        $query = $this->db->pdo->prepare($sql);
        $query->bindValue(':code',$code);
        $query->bindValue(':password',$password);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result;
    }
    
    public function getLoginAgency($code,$password){
        $sql = "SELECT * FROM agencies WHERE code = :code AND password = :password LIMIT 1";
        $query = $this->db->pdo->prepare($sql);
        $query->bindValue(':code',$code);
        $query->bindValue(':password',$password);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result;
    }

    public function userLogin($data){
        $code  = $data['code'];
        $password   = md5($data['password']);
        $conn = mysqli_connect('localhost', 'root', '', 'public_project_monitoring_system');
        $chk_code = $this->codeCheck($code);

        if ($password == "" or $code == "") {
            $mgs = "<div class='alert alert-danger'><strong>Error! </strong>Field must not be Empty</div>";
            return $mgs;
        }

        $result = $this->getLoginUser($code, $password);
        if ($result) {
            $sql = "SELECT * FROM users WHERE code = '$code'";
            $res =  $conn->query($sql);
            $row = $res->fetch_assoc();
            $verification_status = $row['verification_status'];
            if ($verification_status == false) {
                $mgs = "<div class='alert alert-danger'><strong>Error!</strong> Email is not verified yet.</div>";
                return $mgs;
            }
            session::init();
            session::set("login", true);
            session::set("code", $result->code);
            session::set("role", "citizen");
            session::set("name", $result->name);
            session::set("loginmgs", "<div class='alert alert-success'><strong>Success!</strong> You are Logged In.</div>");
            header("Location: citizenIndex.php");
        }
        else{
            $result = $this->getLoginAgency($code, $password);
            if ($result) {
                session::init();
                session::set("login", true);
                session::set("code", $result->code);
                session::set("role", $result->type);
                session::set("name", $result->name);
                session::set("loginmgs", "<div class='alert alert-success'><strong>Success</strong> You are LoggedIn.</div>");
                if($result->type == "EXEC")
                    header("Location: executiveAgencyIndex.php");
                else
                    header("Location: govAgencyIndex.php");
            }
            else
            {
                $mgs = "<div class='alert alert-danger'><strong>Error!</strong> Handle or Password is invalid!</div>";
                return $mgs;
            }
        }
    }
/*----------------------------------------
            user login End
------------------------------------------*/


    public function getuserdata(){
        $sql = "SELECT * FROM users ORDER BY code ASC";
        $query = $this->db->pdo->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }
    public function getuserbyCode($code){
        $sql = "SELECT * FROM users  WHERE code = :code LIMIT 1";
        $query = $this->db->pdo->prepare($sql);
        $query->bindValue(':code',$code);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result;
    }

    public function updateUserData($code, $data){
        $name   = $data['name'];
        $code = $data['code'];

        if ($name == "" or $code == "") {
            $mgs = "<div class='alert alert-danger'><strong>Error!</strong>Field must not be Empty</div>";
            return $mgs;
        }

        $sql = "UPDATE  users set 
                    name    = :name,
                WHERE code    = :code";

        $query = $this->db->pdo->prepare($sql);

        $query->bindValue(':name',$name);
        $query->bindValue(':code',$code);
        $result = $query->execute();
        if ($result) {
            session::set('name', $name);
            $mgs = "<div class='alert alert-success'><strong>Success</strong> User data updated seccessfully.</div>";
            return $mgs;
        }
        else{
            $mgs = "<div class='alert alert-danger'><strong>Error!</strong>Sorry! User data not updated!</div>";
            return $mgs;
        }
    }
    //----------Admin Verification Delete----------
    public function updateAdminVerificationDelete($code){
        $sql = "DELETE FROM users WHERE code= :code LIMIT 1";
        $query = $this->db->pdo->prepare($sql);
        $query->bindValue(':code',$code);
        $result = $query->execute();
        if ($result) {
            $mgs = "<div class='alert alert-success'><strong>Success</strong> User data delete seccessfully.</div>";
            return $mgs;
        }
        else{
            $mgs = "<div class='alert alert-danger'><strong>Error!</strong>Sorry! User data not delete!</div>";
            return $mgs;
        }
    }
}

?>