<?php
$productId = NULL;
$product = NULL;

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $productId = intval($_GET['id']);
    $product = getProductById($productId);
}

if ($product != NULL) {
?>

<div class="fifty_percent_column">
    <?php
        echo '<img class="product_description_left_images" src="images/' .$product['image1']. '" alt=" ' .$product['name']. '">';
        echo '<img class="product_description_right_images" src="images/' .$product['image2']. '" alt=" ' .$product['name']. '">';
        echo '<img class="product_description_left_images" src="images/' .$product['image3']. '" alt=" ' .$product['name']. '">';
        echo '<img class="product_description_right_images" src="images/' .$product['image4']. '" alt=" ' .$product['name']. '">';
    ?>
</div>

<div class="product_description_text">
<?php if(isset ($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1){ ?>

    <form action="actions/save_product_changes.php" method="post">
        <textarea class="description_edit_input" name="description"><?php echo $product['description'];?></textarea> 
        <div class="blackline" style="height:1px"></div><br>
        <label>Price: $</label>
        <input type="text" class="price_edit_input" name="price" pattern="^[0-9]+(\.[0-9]{1,2})?$" value="<?php echo $product['price'];?>"><br>
        <label>Stock: </label>
        <input type="number" class="price_edit_input" name="stock" min="0" value="<?php echo $product['stock'];?>"><br>
        <input class="save_changes_button" type="submit" value="Save changes">
        <input type="hidden" name="id" value="<?php echo $productId;?>">
    </form>

<?php } else { ?>

    <?php echo nl2br($product['description']); ?><br><br>
    <div class="blackline" style="height:1px"></div><br>
    <p>Price: $<?php echo ''.$product['price'].' /deck'; ?></p>
    <form action="actions/add_to_cart.php" method="post">
    <?php if ($product['stock'] > 0){ ?>
        <label>Quantity:</label>
        <input type="number" id="quantity" name="quantity" min="1" max="<?php echo $product['stock'] ?>" value="1"><br>
        <input id="add_to_cart" type="image" src="images/add_to_cart.png" alt="Submit Form">
        <input type="hidden" name="id" value="<?php echo $productId ?>">
    <?php } 
    else {
        echo '<label style="color: red;">Out of Stock</label>';
    }    
    ?>
    </form>
<?php } ?>
</div>

<?php
} 
else {
    echo '<h3>Product does not exist</h3>';
}

if (isset($_SESSION['changes']) && $_SESSION['changes'] == true){
    $_SESSION['changes'] = NULL;
    alert("Changes made successfully");
}
?>
