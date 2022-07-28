<?php include('partials-front/menu.php');

require_once("config/constants.php");
//echo session_id();

//code for Cart
if (!empty($_GET["action"])) {
    switch ($_GET["action"]) {
            //code for adding product in cart
        case "add":
            if (!empty($_POST["quantity"])) {
                $pid = $_GET["pid"];
                $result = mysqli_query($conn, "SELECT * FROM tbl_food WHERE id='$pid'");
                while ($productByCode = mysqli_fetch_array($result)) {
                    $itemArray = array($productByCode["code"] => array('name' => $productByCode["title"], 'code' => $productByCode["code"], 'quantity' => $_POST["quantity"], 'price' => $productByCode["price"], 'image' => $productByCode["image_name"]));

                    if (!empty($_SESSION["cart_item"])) {
                        if (in_array($productByCode["code"], array_keys($_SESSION["cart_item"]))) {
                            foreach ($_SESSION["cart_item"] as $k => $v) {
                                if ($productByCode["code"] == $k) {
                                    if (empty($_SESSION["cart_item"][$k]["quantity"])) {
                                        $_SESSION["cart_item"][$k]["quantity"] = 0;
                                    }
                                    $_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
                                }
                            }
                        } else {
                            $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"], $itemArray);
                        }
                    } else {
                        $_SESSION["cart_item"] = $itemArray;
                    }
                }
            }
            break;

            // code for removing product from cart
        case "remove":
            if (!empty($_SESSION["cart_item"])) {
                foreach ($_SESSION["cart_item"] as $k => $v) {
                    if ($_GET["code"] == $k)
                        unset($_SESSION["cart_item"][$k]);
                    if (empty($_SESSION["cart_item"]))
                        unset($_SESSION["cart_item"]);
                }
            }
            break;
            // code for if cart is empty
        case "empty":
            unset($_SESSION["cart_item"]);
            break;
    }
}
?>
<HTML>

<HEAD>

    <TITLE>Order Cart</TITLE>
    <link href="cart.css" type="text/css" rel="stylesheet" />
    <link href="css/style.css" type="text/css" rel="stylesheet" />

</HEAD>



<BODY>


    <!-- Cart ---->
    <section class="food-search">
    <div  id="shopping-cart">
        <div class="login-sec">
            <h2 width="100px" class="text-left">Your Cart</h2>
        </div>


        <a id="btnEmpty" href="cart.php?action=empty">Empty Cart</a>

        <?php
        if (isset($_SESSION["cart_item"])) {
            $total_quantity = 0;
            $total_price = 0;

        ?>
            <table class="tbl-cart" cellpadding="50" cellspacing="3">
                <tbody>
                    <tr>
                        <th style="text-align:left;" width="30%">Name</th>
                        <th style="text-align:left;" width="10%">Code</th>
                        <th style="text-align:right;" width="10%">Quantity</th>
                        <th style="text-align:right;" width="10%">Unit Price</th>
                        <th style="text-align:right;" width="10%">Price</th>
                        <th style="text-align:center;" width="2%">Remove</th>
                    </tr>

                    <?php
                    foreach ($_SESSION["cart_item"] as $item) {
                        $item_price = $item["quantity"] * $item["price"];
                    ?>
                        <tr>
                            <td><img src="<?php echo SITEURL; ?>images/food/<?php echo $item["image"]; ?>" class="cart-item-image" /><?php echo $item["name"]; ?>
                            </td>
                            <td><?php echo $item["code"]; ?></td>
                            <td style="text-align:right;"><?php echo $item["quantity"]; ?></td>
                            <td style="text-align:right;"><?php echo "Rs. " . $item["price"]; ?></td>
                            <td style="text-align:right;"><?php echo "Rs. " . number_format($item_price, 2); ?></td>
                            <td style="text-align:center;"><a href="cart.php?action=remove&code=<?php echo $item["code"]; ?>" class="btnRemoveAction"><img src="https://www.pngitem.com/pimgs/m/482-4825300_ai-delete-box-hd-png-download.png" width="13" height="18" alt="Remove Item" /></a></td>
                        </tr>
                    <?php
                        $total_quantity += $item["quantity"];
                        $total_price += ($item["price"] * $item["quantity"]);
                    }
                    ?>

                    <tr>
                        <td colspan="2" align="right">Total:</td>
                        <td align="right"><?php echo $total_quantity; 
                        $_SESSION['total_quantity']=$total_quantity;
                        ?></td>
                        <td align="right" colspan="2"><strong><?php echo "Rs. " . number_format($total_price, 2); ?></strong>
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        <?php
        } else {
            $_SESSION['total_quantity']=0;
        ?>
            <div class="no-records">Your Cart is Empty</div>
        <?php
        }
        ?>

        <form method="post">
            <input style="font-size:16px; cursor: pointer;" type="submit" name="save" id="btnEmpty" value="save cart">
            <br>
            </br>
            <br>
            </br>

            <a style="position: relative;" id="btnEmpty" href="<?php echo SITEURL; ?>order.php">Proceed to Payment</a>
            <?php

            if (isset($_POST["save"])) {
                foreach ($_SESSION["cart_item"] as $item) {
                    $code1 = $item["code"];
                    $qty = $item["quantity"];
                    $price = $item["price"];
                    //$price=$total_price;
                   
                    //date_default_timezone_set('Asia/Calcutta');
                    $id = session_id();
                    //$time = date("l jS F Y h:i:s A");
                   // $_SESSION['time']=$time;
                    $res = mysqli_query($conn, "INSERT INTO tbl_cart SET customer_id='$id', code='$code1', quantity='$qty', price='$price'");

                    // session_save();
                    // $new_session_id = session_create_id();


                }
            }


            ?>
        </form>


    </div>

    

    </section>

</BODY>

