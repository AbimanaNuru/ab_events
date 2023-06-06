<?php
include_once('config.php');
session_start();
include "sessionexpired.php";


$product_id = $_POST["product_id"];
$result = mysqli_query($connection, "SELECT * FROM  ab_events_material where ab_events_material_id  = '$product_id' ");
?>
<?php
while ($row = mysqli_fetch_array($result)) {
    $qty = $row['ab_events_material_available_qty'];

?>

        <option value="<?php echo $qty; ?>">Available: [<?php echo $qty; ?>]</option>

<?php
 
}
?>