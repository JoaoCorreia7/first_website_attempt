<div class="blackline"></div>
<div class="container">
    <div class="column">
        <img src="images/left_panel.jpg" width="100%">
    </div>
    <div class="column">
        <img id="login_center_logo" src="images/logo.png">
        <form class="login_form" action="actions/verify_sign_up.php" method="post">
            <label class="login_big_text">Username:</label>
            <?php
                if (!isset($_SESSION['error'])){
                    //no errors or password error
                    echo '<input class="login_input" type="text" name="username" value=""><br><br><br>';
                    echo '<label class="login_big_text">Email:</label>';
                    echo '<input class="login_input" type="email" name="email" value=""><br><br><br>';
                }
                else if ($_SESSION['error'] == 1){
                    //username already exists and email does not
                    echo '<input class="login_input_error" type="text" name="username" value="'.$_SESSION['username'].'"><br>
                    <p class="login_error_text">Username already exists</p><br><br>';
                    echo '<label class="login_big_text">Email:</label>';
                    echo '<input class="login_input" type="email" name="email" value="'.$_SESSION['email'].'"><br><br><br>';
                }
                else if ($_SESSION['error'] == 2){
                    //email already exists and username does not
                    echo '<input class="login_input" type="text" name="username" value="'.$_SESSION['username'].'"><br><br><br>';
                    echo '<label class="login_big_text">Email:</label>';
                    echo '<input class="login_input_error" type="email" name="email" value="'.$_SESSION['email'].'"><br>
                    <p class="login_error_text">Email already exists</p><br><br>';
                }
                else if ($_SESSION['error'] == 3){
                    //both username and email already exist
                    echo '<input class="login_input_error" type="text" name="username" value="'.$_SESSION['username'].'"><br>
                    <p class="login_error_text">Username already exists</p><br><br>';
                    echo '<label class="login_big_text">Email:</label>';
                    echo '<input class="login_input_error" type="email" name="email" value="'.$_SESSION['email'].'"><br>
                    <p class="login_error_text">Email already exists</p><br><br>';
                }
                else if ($_SESSION['error'] == 4){
                    //password and corfirm_password do not match
                    echo '<input class="login_input" type="text" name="username" value="'.$_SESSION['username'].'"><br><br><br>';
                    echo '<label class="login_big_text">Email:</label>';
                    echo '<input class="login_input" type="email" name="email" value="'.$_SESSION['email'].'"><br><br><br>';
                }
                else {
                    //This should never happen, just here for redundancy
                    $_SESSION['error'] = NULL;
                    header("Location: main.php?page=login");
                }
            ?>
            <label class="login_big_text">Password:</label>
            <?php
                if (!isset($_SESSION['error']) || $_SESSION['error'] != 4){
                    //no errors
                    echo '<input class="login_input" type="password" name="password" value=""><br><br><br>';
                    echo '<label class="login_big_text">Confirm Password:</label>';
                    echo '<input class="login_input" type="password" name="confirm_password" value=""><br><br><br>';
                    $_SESSION['error'] = NULL;
                }
                else {
                    //password and corfirm_password do not match
                    echo '<input class="login_input_error" type="password" name="password" value=""><br>
                    <p class="login_error_text">Passwords do not match</p><br><br>';
                    echo '<label class="login_big_text">Confirm Password:</label>';
                    echo '<input class="login_input_error" type="password" name="confirm_password" value=""><br>
                    <p class="login_error_text">Passwords do not match</p><br><br>';
                    $_SESSION['error'] = NULL;
                }
            ?>
            <input class="login_button" type="submit" value="Create account"><br><br>
        </form> 
    </div>
    <div class="column">
        <img src="images/right_panel.jpg" width="100%">
    </div>
</div>