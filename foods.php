<?php include('partials-front/menu.php'); ?>

<!-- fOOD sEARCH Section Starts Here -->
<section class="food-search text-center">
    <div class="container">

        <form action="<?php echo SITEURL; ?>food-search.php" method="POST">
            <input type="search" name="search" placeholder="Search for Food.." required>
            <input type="submit" name="submit" value="Search" class="btn btn-primary">
        </form>

    </div>
</section>
<!-- fOOD sEARCH Section Ends Here -->



<!-- fOOD MEnu Section Starts Here -->
<section class="food-menu">
    <div class="container">
        <h2 class="text-center">Food Menu</h2>

        <?php 
                //Display Foods that are Active
                $sql = "SELECT * FROM tbl_food WHERE active='Yes'";

                //Execute the Query
                $res=mysqli_query($conn, $sql);

                //Count Rows
                $count = mysqli_num_rows($res);

                //CHeck whether the foods are availalable or not
                if($count>0)
                {
                    //Foods Available
                    while($row=mysqli_fetch_assoc($res))
                    {
                        //Get the Values
                        $id = $row['id'];
                        $title = $row['title'];
                        $description = $row['description'];
                        $price = $row['price'];
                        $image_name = $row['image_name'];
                        ?>

        <div class="food-menu-box">
            <div class="food-menu-img">
                <?php 
                                    //CHeck whether image available or not
                                    if($image_name=="")
                                    {
                                        //Image not Available
                                        echo "<div class='error'>Image not Available.</div>";
                                    }
                                    else
                                    {
                                        //Image Available
                                        ?>
                <img width="100" height="90" src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>"
                    alt="Image not available Currently" class="img-responsive img-curve">
                <?php
                                    }
                                ?>

            </div>

            <div class="food-menu-desc">
                <form method="post" action="cart.php?action=add&pid=<?php echo $row["id"]; ?>">
                    <h4><?php echo $title; ?></h4>
                    <p class="food-price">&#8377 <?php echo $price; ?></p>
                    <p class="food-detail">
                        <?php echo $description; ?>
                    </p>
                    <br>
                    <div class="cart-action">

                        <form action="">
                            <input type="number" id="points"  class="product-quantity" name="quantity" step="1"
                             value="1" style="width: 40px">
                            
                        </form>
               
                        <input type="submit" class="btn btn-primary" value="Add to Cart" class="btnAddAction" />
                    </div>
                </form>

            </div>
        </div>

        <?php
                    }
                }
                else
                {
                    //Food not Available
                    echo "<div class='error'>Food not found.</div>";
                }
            ?>

        <div class="clearfix"></div>

    </div>

</section>
<!-- fOOD Menu Section Ends Here -->

<?php include('partials-front/footer.php'); ?>