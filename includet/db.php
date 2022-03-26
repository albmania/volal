 <?php
$servername = "localhost";
$username = "root";
$password = "";
$dbtable = "volal";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbtable);
mysqli_set_charset($conn, "utf8");
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
//echo "Connected successfully";
?> 