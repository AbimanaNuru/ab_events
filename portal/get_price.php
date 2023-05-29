<?php
include_once('config.php');
session_start();
include "sessionexpired.php";


$product_id = $_POST["product_id"];
$result = mysqli_query($connection, "SELECT * FROM  ab_events_material where ab_events_material_id  = $product_id ");
?>
<?php
while ($row = mysqli_fetch_array($result)) {
    $individually = $row['ab_events_material_individual_price'];
    $corporate = $row['ab_events_material_corporate_price'];
?>

        <option value="<?php echo $individually; ?>">Individual Price:<?php echo $individually; ?></option>
        <option value="<?php echo $corporate; ?>">Corporate Price:<?php echo $corporate; ?></option>

<?php
 
}
?>