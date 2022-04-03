<?php
require_once("class.php");
$api = new Invoice();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="description" content="API Wrapper" />
  <meta charset="utf-8">
  <title>API Wrapper</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Invoice creation</h1>
    <form action="submit.php" method="post">
        <label for="kunde">Kunde: </label>
        <select id="kunde" name="kunde">
            <?php
            $kunde = $api->Get("Customers", true);
            foreach ($kunde as $key => $value){
                echo "<option value ='" . $key . "'>" . $value . "</option>";
            }
            ?>
        </select>
        <br>
        <label for="Payment">Payment terms: </label>
        <select id="Payment" name="Payment">
            <?php
            $Payment = $api->Get("Payment terms", true);
            foreach ($Payment as $key => $value){
                echo "<option value ='" . $key . "'>" . $value . "</option>";
            }
            ?>
        </select>
        <br>
        <label for="Vat">Vat zones: </label>
        <select id="Vat" name="Vat">
            <?php
            $Vat = $api->Get("Vat zones", true);
            foreach ($Vat as $key => $value){
                echo "<option value ='" . $key . "'>" . $value . "</option>";
            }
            ?>
        </select>
        <br>
        <label for="Layouts">Layouts: </label>
        <select id="Layouts" name="Layouts">
            <?php
            $Layout = $api->Get("Layouts", true);
            foreach ($Layout as $key => $value){
                echo "<option value ='" . $key . "'>" . $value . "</option>";
            }
            ?>
        </select>
        <br>
        <label for="Products">Products: </label>
        <select id="Products" name="Products">
            <?php
            $Product = $api->Get("Products", true);
            foreach ($Product as $key => $value){
                echo "<option value ='" . $key . "'>" . $value . "</option>";
            }
            ?>
        </select>
        <br>
        <label for="Units">Units: </label>
        <select id="Units" name="Units">
            <?php
            $Unit = $api->Get("Units", true);
            foreach ($Unit as $key => $value){
                echo "<option value ='" . $key . "'>" . $value . "</option>";
            }
            ?>
        </select>
        <br>
        <label for="price">Price: </label>
        <input id="price" type="number" name="price">
        <br>
        <label for="antal">Antal: </label>
        <input id="antal" type="number" name="antal">
        <br>
        <label for="desc">Description: </label>
        <input id="desc" type="text" name="desc">
        <br>
        <input type="submit">
    </form>
</body>
</html>