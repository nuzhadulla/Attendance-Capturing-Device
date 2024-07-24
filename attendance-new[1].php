
<?php
date_default_timezone_set('Asia/Kolkata');

$api_key = "481e91d6-0ed2-4329-a4a9-29cd64014ad0";
if (!isset($_REQUEST['api_key']) || $_REQUEST['api_key'] != $api_key) {
    die("invalid api_key");
}

//Database
const HOSTNAME = "localhost";
const USERNAME = "uocsyhn2www_attendance";
const PASSWORD = "z=oX4#)MJ3h";
const DATABASE = "uocsyhn2www_attendance";

$conn = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function inputHistory($content, $file_name)
{
    $file_path = '/home/uocsyhn2www/public_html/attendance/logs' . '/' . $file_name . '.txt';
    $fh = fopen($file_path, 'a+');
    $str_content = "No inputs received";

    if (strlen($content) > 0) {
        $str_content = $content;
    }
    $stringData = "\n" . date('Y-m-d H:i:s') . "|| " . $str_content;
    fwrite($fh, stripslashes($stringData));
    fclose($fh);
}

$attendance = file_get_contents('php://input');
inputHistory($attendance, 'attendance');
inputHistory("GET:" . json_encode($_GET), 'attendance');
inputHistory("POST:" . json_encode($_POST), 'attendance');

if (isset($_REQUEST)) {
    $json = json_encode($_REQUEST);
    $created_at = date('Y-m-d H:i:s');
    $updated_at = date('Y-m-d H:i:s');

    $query = "INSERT INTO student_logs(`record`, `created_at`, `updated_at`) VALUES ('$json', '$created_at', '$updated_at')";
    $create_data = mysqli_query($conn, $query);
    if ($create_data) {
        echo 'Attendance log captured.';
    } else {
        echo 'Somthing error.';
    }
}

$target_dir = "uploads/";
foreach($_FILES ?? [] as $n => $file) {
    $target_file = $target_dir . time() . $n . basename($file["name"]);
    move_uploaded_file($file["tmp_name"], $target_file);
}
?>
