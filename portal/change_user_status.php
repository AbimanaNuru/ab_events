<?php
include_once('config.php');
session_start();
include "sessionexpired.php";


session_start();


if (isset($_GET['status'])) {
    $status1 = $_GET['status'];
    $select = mysqli_query($connection, "select * from ab_users where ab_user_id ='$status1'");
    while ($row = mysqli_fetch_object($select)) {
        $status_var = $row->ab_user_status;
        if ($status_var == 'Deactive') {
            $status_state = 'Active';
        } else {
            $status_state = 'Deactive';
        }
        $update = mysqli_query($connection, "update ab_users set ab_user_status='$status_state' where ab_user_id ='$status1'");
        if ($update) {
            header("Location:ab_events_users.php");
        }
    }
?>
<?php
}
?>