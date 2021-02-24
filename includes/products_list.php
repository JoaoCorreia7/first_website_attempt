<?php

$products = array();
dbconn();
$query = "SELECT product_id, 
                name,
                price, 
                image_array[1] AS image1 FROM product ORDER BY product_id";
$result = pg_exec($query);
$row = pg_fetch_assoc($result);

while (isset($row['product_id'])){
    array_push($products, array('product_id' => $row['product_id'], 
                                'name' => $row['name'],  
                                'price' => $row['price'],  
                                'image' => $row['image1']));
    $row = pg_fetch_assoc($result);
}
//var_dump($products);

if (count($products) > 0) {
    $count = 0;
    for ($i = 0; $i < count($products); $i++) {
        if ($count == 0) {
            echo '<div id="products_container">';
        }
        echo '<div class="product_column">';
            echo '<a href="main.php?page=product_detail&id=' .$products[$i]['product_id']. '">';
                echo '<img class="product_image zoom" src="images/' .$products[$i]['image']. '" alt="' .$products[$i]['name']. '">';
            echo '</a>';
            echo '<p class="price">Price: $' .$products[$i]['price']. '</p>';
        echo '</div>';
        $count++;
        if ($count == 3 || $i == count($products)-1) {
            echo '</div>';
            $count = 0;
        }
    }
} else {
    echo '<h3>No products</h3>';
}

?>