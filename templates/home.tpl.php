<img src="images/home_main.jpg" width="100%">
<div class="container">
    <div class="column">
        <img class="color_change" src="images/home1.jpg">
        <img class="color_change" src="images/home2.jpg">
    </div>
    <div class="column">
        <img class="color_change" src="images/home3.jpg">
        <img class="color_change" src="images/home4.jpg">
    </div>
    <div class="column">
        <img class="color_change" src="images/home5.jpg">
        <img class="color_change" src="images/home6.jpg">
    </div>
</div> 
<?php
    if (isset($_SESSION['new_account']) && $_SESSION['new_account'] == true){
        alert("Account created successfully. Welcome!");
        $_SESSION['new_account'] = false;
    }
    else;
?>