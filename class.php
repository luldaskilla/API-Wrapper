<?php

// Creating the class
class Invoice {
    
    private $url;
    private $headers;
    
    function __construct() {
        $this->url = "https://restapi.e-conomic.com/";
        $this->headers = array(
        "X-AppSecretToken: 44qa5IvzXHwA6Ii1iTq69i2rP7cZGapcnJ5r39cR8ww1",
        "X-AgreementGrantToken: V6TSpiFfWEeGyeaqTUQ3pCaJMhXbtrsBZ6dsMZlALvE1",
        "Content-Type: application/json",
        );
    }
    
    //Making the command to get the different data like the Customers, with id to search for specific ones. Made so it doesn't have to be set.
    function Get(string $method, bool $bool = false, $id = "") {
        
        //Using a Switch so I just have 1 function instead of multiple
        switch($method){
            case "Customers":
                $this->url = "https://restapi.e-conomic.com/customers";
                $number = "customerNumber";
                break;
            case "Payment terms":
                $this->url = "https://restapi.e-conomic.com/payment-terms";
                $number = "paymentTermsNumber";
                break;
            case "Vat zones":
                $this->url = "https://restapi.e-conomic.com/vat-zones";
                $number = "vatZoneNumber";
                break;
            case "Layouts":
                $this->url = "https://restapi.e-conomic.com/layouts";
                $number = "layoutNumber";
                break;
            case "Products":
                $this->url = "https://restapi.e-conomic.com/products";
                $number = "productNumber";
                break;
            case "Units":
                $this->url = "https://restapi.e-conomic.com/units";
                $number = "unitNumber";
                break;
            default:
                return false;
                break;
        }
        
        if(!empty($id)){
            $this->url .= "/" . $id;
        }
        
        //setup cURL
        $curl = curl_init($this->url);
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        
        //Execute the session, storing the response, decoding it into an array, then have a check, if true, it stores the id as a key and the name as a value in an array, then returns that. if false, returns everything.
        $response = curl_exec($curl);
        $json = json_decode($response, true);
        curl_close($curl);
        if ($bool == true){
            foreach ($json as $key => $value){
                if (is_array($value) && $key == "collection"){
                    foreach ($value as $subvalue){
                        $array[$subvalue[$number]] = $subvalue["name"];
                    } 
                } 
            }
            if (!empty($id)){
                $array["name"] = $json["name"];
            }
            return $array;
        } else {
            return $json;
        }
    }
    
    //a function to make the data so it is ready to be send. Returns raw json
    function PrepData($kunde, $vat, $payment, $layout, $name, $unit, $product, $price, $antal, $desc){
        
        //converting to ints
        $kunde = (int)$kunde;
        $payment = (int)$payment;
        $vat = (int)$vat;
        $layout = (int)$layout;
        $unit = (int)$unit;
        $product = (int)$product;
        $price = (int)$price;
        $antal = (int)$antal;
        
        //construct the array
        $array["lines"] = array(array("lineNumber" => 1, "sortKey" => 1, "description" => "$desc", "unit" => array("unitNumber" => $unit, "name" => "stk"), "product" => array("productNumber" => "$product"), "quantity" => $antal, "unitNetPrice" => $price, "discountPercentage" => 0, "unitCostPrice" => 0, "totalNetAmount" => -500, "marginInBaseCurrency" => -500, "marginPercentage" => 100));
        $array["date"] = date("Y-m-d");
        $array["currency"] = "DKK";
        $array["paymentTerms"] = array("paymentTermsNumber" => $payment);
        $array["customer"] = array("customerNumber" => $kunde);
        $array["recipient"] = array("name" => $name, "vatZone" => array("vatZoneNumber" => $vat));
        $array["layout"] = array("layoutNumber" => $layout);
        
        //json encode it
        $data_string = json_encode($array);  
        
        //return it
        return $data_string;
    }
    
    //function that posts the data to make an invoice draft
    function PostDraft($data){
        $this->url = "https://restapi.e-conomic.com/invoices/drafts";
        
        //setting up cURL
        $curl = curl_init($this->url);
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        //Attatching the headers
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        
        //Setting it to POST
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        
        //Attatching the data
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); 
        
        //Send the request;
        $response = curl_exec($curl);
        $json = json_decode($response, true);
        $id = $json["draftInvoiceNumber"];
        
        //Catch any errors and store them for exeptions
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);
        if (isset($error_msg)) {
            return $error_msg;
        } else {
            return $id;
        }
    }
    
    function pdf($id){
        $this->url = "https://restapi.e-conomic.com/invoices/drafts/" . $id . "/pdf";
        //setup cURL
        $curl = curl_init($this->url);
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        
        //Execute the session, reurn the response and close the session.
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}