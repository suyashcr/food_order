<?php include('partials-front/menu.php'); ?>

<?php

// if(isset($_POST['checkout']))
// {	

// 	 $sql_query = "SELECT * FROM ";
//    $result = mysqli_query($conn, $sql_query);

//    if (mysqli_num_rows($result) > 0) {
//      // output data of each row
//      while($row = mysqli_fetch_assoc($result)) {
//        echo  $row["ID"]. ". Name: " . $row["Name"]. "&nbsp;&nbsp;&nbsp;" . $row["Date_travel"]. "Your Feedback given was: ". $row["Service"]. "<br>";  
//        //&nbsp; is used for spaces as php disregards the spaces provided manually
//      }
//    } else {
//      echo "0 results";
//    }
//    mysqli_close($conn);
// }   
//CHeck whether food id is set or not

//Get the Food id and details of the selected food


//Get the DEtails of the SElected Food

$sid = session_id();

$sql = "SELECT * FROM tbl_cart WHERE customer_id='$sid'";
//  $sql2="SELECT * FROM tbl_food where "
//Execute the Query
$res = mysqli_query($conn, $sql);
//Count the rows

//CHeck whether the data is available or not


// while($row = mysqli_fetch_assoc($res)) {
//   echo  $row["code"]. ". Name: " . $row["quantity"]. "&nbsp;&nbsp;&nbsp;" . $row["id"]. " ". $row["time"]. "<br>";  
//   //&nbsp; is used for spaces as php disregards the spaces provided manually
// }



// else
// {
//     //Redirect to homepage
//     header('location:'.SITEURL);
// }
?>

<!-- fOOD sEARCH Section Starts Here -->

<section class="food-search">
    <div class="container">

        <h2 class="text-center text-white">Payment Details </h2>

        <form action="" method="POST" class="order">
            <fieldset>
                <legend>Order Summary:- </legend>
                <div class="food-menu-desc">
                    <table border="1px" cellpadding="0" cellspacing="3">

                        
                        <tr>
                            <th>S.no </th>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>

                        <?php
                        //$arr=mysqli_fetch_assoc($res);
                        $count = 0;
                        $ttlprice = 0;
                        $total = 0;
                        foreach ($res as $item) {
                            $count++;
                    
                            echo "<tr><td>" . $count  . "</td>";
                            echo "<td>" .  $item["code"] . "</td>";
                            echo "<td>" . $item["quantity"] . "</td>";
                            echo "<td>"." Rs. " . $item["price"] . "</td>";
                            $ttlprice = $ttlprice + ($item["quantity"] * $item["price"]);
                            $total = $item["quantity"] * $item["price"];
                            echo "<td>  Rs. " . $total . "</td></tr>";} 
                            
                            ?>
                
                    </table>
                            <?php echo " <h3>   &nbsp;&nbsp;&nbsp; Total Price:  Rs. ".$ttlprice."</h3>"; ?>
                </div>

            </fieldset>

            <fieldset>
                <legend>Delivery Details</legend>
                <div class="order-label">Full Name</div>
                <input type="text" name="full-name" placeholder="Enter Full Name" class="input-responsive" required>

                <div class="order-label">Phone Number</div>
                <input type="tel" name="contact" placeholder="Enter Phone Number" class="input-responsive" required>

                <div class="order-label">Email</div>
                <input type="email" name="email" placeholder="Enter Email-ID" class="input-responsive" required>

                <div class="order-label">Address</div>
                <textarea name="address" rows="10" placeholder="E.g. Street, City, Country" class="input-responsive"
                    required></textarea>

                <input type="submit" name="submit" value="Confirm Order" class="btn btn-primary">

            </fieldset>
        </form>

        <form method="POST">
            <input style=" position: relative;left: 470px; font-size:16px; cursor: pointer;" type="submit"
                class="btn btn-primary" name="cancel" value="Cancel Order">
        </form>
        <?php
            if (isset($_POST['cancel'])) {
                $sql1 = "DELETE FROM tbl_cart WHERE customer_id ='$sid'";
                $res = mysqli_query($conn, $sql1);
                header('location:'.SITEURL);
                }      
        //CHeck whether submit button is clicked or not
        if (isset($_POST['submit'])) {
            // Get all the details from the form

            $id= $sid;
            
            date_default_timezone_set('Asia/Calcutta');
            $time = date("l jS F Y h:i:s A");
           
            //$order_date = date("Y-m-d h:i:sa"); //Order DAte

            $status = "Ordered";  // Ordered, On Delivery, Delivered, Cancelled
            $customer_name = $_POST['full-name'];
            $customer_contact = $_POST['contact'];
            $customer_email = $_POST['email'];
            $customer_address = $_POST['address'];


            //Save the Order in Database
            //Create SQL to save the data
            $sql2 = "INSERT INTO tbl_order SET 
                        id='$id',
                        order_date='$time',
                        total = $ttlprice,
                        status = '$status',
                        customer_name = '$customer_name',
                        customer_contact = '$customer_contact',
                        customer_email = '$customer_email',
                        customer_address = '$customer_address'";
            // order_date = '$order_date',

            // echo $sql2; 

            //Execute the Query
            $res2 = mysqli_query($conn, $sql2);
            echo "Error: " . mysqli_error($conn);
            // die;
            //Check whether query executed successfully or not
            if ($res2 == true) {
                //Query Executed and Order Saved

                
                $message='Thank you '.$customer_name.' for ordering with Foodie India!! '."\n".'You have successfully ordered the following items from our restaurant - 
                '."\n". 'Order Details:-
                ';
                $message.="\n".'Sr.   CODE   QUANTITY   PRICE     AMOUNT'."\n";
                //$arr=mysqli_fetch_assoc($res);
                $count = 0;
                
                  foreach ($res as $item) {
                    $count++;
                     
                    $message.=$count.".      ".$item['code']."       ".$item['quantity']. "           Rs. " . $item["price"] . "     Rs. " . ($item["quantity"] * $item["price"]) . "\n";

                    
                  }

                  $message.="\n"."Total Transaction Made: Rs. ".$ttlprice."\n";
                  $message.="\n"."Your Email: ".$customer_email."\n";
                  $message.="\n"."Your Ph. Number: ".$customer_contact."\n";
               

                   
                
                
                mail($customer_email,"Order Confirmation",$message,"From: redbyimages@gmail.com");
                $_SESSION['order'] = "<div class='success text-center'>Food Ordered Successfully.</div>";
                

                header('location:' . SITEURL);
            } else {
                //Failed to Save Order

                $_SESSION['order'] = "<div class='error text-center'>Failed to Order Food. </div>";

                header('location:' . SITEURL);
            }
        }

        ?>



    </div>
</section>
<!-- fOOD sEARCH Section Ends Here -->