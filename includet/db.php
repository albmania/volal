 <?php
$servername = "localhost";
$username = "volalser_sys";
$password = "6Uzd_b98";
$dbtable = "volalser_sys";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbtable);
mysqli_set_charset($conn, "utf8");
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
//echo "Connected successfully";
?> 