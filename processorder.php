<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Form</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
</head>

<body>
    <h1>CZ3006 Net Centric Computing</h1>
    <h5>Done by : Derrick Peh (U1621219F)</h5>
    <br>
    <hr>
    <?php

//Retrieve post request data from the form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST["name"];
  $paymentMethod = $_POST["payment"];
    $appleNo = $_POST["apple"];
    $orangeNo = $_POST["orange"];
    $bananaNo = $_POST["banana"];
    $appleCost = $appleNo * 0.69;
    $orangeCost = $orangeNo * 0.59;
    $bananaCost = $bananaNo * 0.39;

    //Calculate subcost without transaction fees
    $subCost = ($appleCost + $orangeCost + $bananaCost);

    //Calculate transaction fees for each type of card
    if ($paymentMethod == "Mastercard") {
      $txnCharge = ($appleCost + $orangeCost + $bananaCost) *1.12/100;
}
    else if ($paymentMethod == "Visa"){
     $txnCharge = ($appleCost + $orangeCost + $bananaCost) *1.32/100;
}
  else{
     $txnCharge = ($appleCost + $orangeCost + $bananaCost) *1.05/100;
}

//Calculate total cost and converting to currency notation
$txnCharge =  bcdiv($txnCharge, 1, 2);
$subCost =   bcdiv($subCost, 1, 2);
$totalCost = ($subCost + $txnCharge);
}

//Get file values 
 $filename = 'order.txt';
    $file = fopen($filename, 'c+');

    $file_contents = file_get_contents($filename);
    $oldAppleNo=0;
    $oldorangeNo=0;
    $oldbananaNo=0;
    if ($file_contents !== ""){
      //Apply regular expressions to match and retrieve quantity of each fruit (is a digit)
      preg_match("/Total number of apples: (\d+)/", $file_contents,  $oldAppleNo);
      preg_match("/Total number of oranges: (\d+)/", $file_contents, $oldorangeNo);
      preg_match("/Total number of bananas: (\d+)/", $file_contents, $oldbananaNo);

            //First array value
      $oldAppleNo = intval(isset($oldAppleNo[1]) ? $oldAppleNo[1] : null);
      $oldorangeNo = intval(isset($oldorangeNo[1]) ? $oldorangeNo[1] : null);
      $oldbananaNo = intval(isset($oldbananaNo[1]) ? $oldbananaNo[1] : null);
    }

    //Seek to 0 to rewrite quantity of fruits
    fseek($file,0);

    //Compute new quantity of each fruit
    $newAppleNo = $oldAppleNo + $appleNo;
    $newOrangeNo = $oldorangeNo + $orangeNo;
    $newBananaNo = $oldbananaNo + $bananaNo;

    //Append string to file
    $str = "Total number of apples: $newAppleNo\r\n";
    $str .= "Total number of oranges: $newOrangeNo\r\n";
    $str .= "Total number of bananas: $newBananaNo";

    //Write to file
    fwrite($file, $str);

    //Close file
    fclose($file);

        ?>
        <!--HTML page to display receipt-->
        <div class="card receipt">
              <h1 style="color:black;">Fruit Store Receipt</h1>
            <h5 style="color:black;">Buyer: <?php print $name; ?></h5>

            <!--Table for receipt-->
            <table class="table table-striped table-light table-hover" border="border" style="border-color:transparent;">
                <br>
                <tr>
                    <th>#</th>
                    <th>Quantity</th>
                    <th>Cost</th>
                </tr>
                <?php if ($appleNo != "0") : ?>
                <tr>
                    <th>Apples:</th>
                    <td>
                        <?php print ("$appleNo"); ?>
                    </td>
                    <td>
                        <?php print ("$$appleCost"); ?>
                    </td>
                </tr>
                  <?php endif; ?>
                   <?php if ($orangeNo != "0") : ?>
                <tr>
                    <th>Oranges:</th>
                    <td>
                        <?php print ("$orangeNo"); ?>
                    </td>
                    <td>
                        <?php print ("$$orangeCost"); ?>
                    </td>
                </tr>
                       <?php endif; ?>
                 <?php if ($bananaNo != "0") : ?>
                <tr>
                    <th>Bananas:</th>
                    <td>
                        <?php print ("$bananaNo"); ?>
                    </td>
                    <td>
                        <?php print ("$$bananaCost"); ?>
                    </td>
                </tr>
                       <?php endif; ?>
                <tr>
                    <th colspan=2>SubTotal:</th>
                    <td>
                        <?php print ("$$subCost"); ?>
                    </td>
                </tr>
                <tr>
                    <th colspan=2>Transaction Fee: (
                        <?php print ("$paymentMethod"); ?>) </th>
                    <td>
                        <?php print ("$$txnCharge"); ?>
                    </td>
                </tr>
                <tr>
                    <th colspan=2>Total:</th>
                    <td>
                        <?php print ("$$totalCost"); ?>
                    </td>
                </tr>
            </table>

        </div>

        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
</body>
</body>

</html>