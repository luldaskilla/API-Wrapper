<?php
require_once("class.php");
$api = new Invoice();

//Fintering the different values from $_POST
$kunde = filter_input(INPUT_POST, 'kunde');
$payment = filter_input(INPUT_POST, 'Payment');
$vat = filter_input(INPUT_POST, 'Vat');
$layout = filter_input(INPUT_POST, 'Layouts');
$product = filter_input(INPUT_POST, 'Products');
$unit = filter_input(INPUT_POST, 'Units');

if (!isset($_POST['price'])){
    $price = 350;
} else {
    $price = filter_input(INPUT_POST, 'price');
}

if (!isset($_POST['antal'])){
    $antal = 10;
} else {
    $antal = filter_input(INPUT_POST, 'antal');
}

if (!isset($_POST['desc'])){
    $desc = "Lorem Ipsum";
} else {
    $desc = filter_input(INPUT_POST, 'desc');
}

//getting the recipient name from the id and storing it
$recipient = $api->get("Customers", true, "$kunde");
$name = $recipient['name'];

//Call the Prep command, to get the json text as the data.
$data = $api->PrepData($kunde, $vat, $payment, $layout, $name, $unit, $product, $price, $antal, $desc);

//Store the response
$response = $api->PostDraft($data);

//Return messages
if (!empty($response)){
    header("Content-type: application/pdf");
    $pdf = $api->pdf($response);
    echo $pdf;
} else {
    echo "<h2>Der skete en fejl</h2>";
}