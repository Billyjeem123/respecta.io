<?php
ini_set("allow_url_fopen", true);

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);

if(session_id() == '') {
 session_start();
 } else {}

ob_start();
date_default_timezone_set("Africa/Lagos");
define("DB_SERVER", "localhost");
define("DBASE_NAME", 'fireswit_respecta');
define("DBASE_USER", 'root');
define("DBASE_PASS", '');


$connect_error = "We sincerely apologise. We are experiencing connection problems";
$con=mysqli_connect(DB_SERVER,DBASE_USER,DBASE_PASS,DBASE_NAME);
($con)? TRUE : die($connect_error);
$mysqli = mysqli_connect(DB_SERVER,DBASE_USER,DBASE_PASS,DBASE_NAME);
if (mysqli_connect_errno()) {
       die("Unable to connect to the server ".mysqli_connect_error());
}
global $mysqli;



class db{
    private $host = DB_SERVER;
    private $user = DBASE_USER;
    private $pwd = DBASE_PASS;
    private $dbname = DBASE_NAME;

    public function connect(){
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $this->pdo = new PDO($dsn, $this->user, $this->pwd);
        if(!$this->pdo){
    echo "Unable to connect to database";
}
    # DEFAULT FETCH_MODE = FETCH_OBJ
    $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    return $this->pdo;
    }

 
}



define("APPNAME", 'Respecta');
define("APP_MAIL", 'hello@respecta.io');



require('models.php');
