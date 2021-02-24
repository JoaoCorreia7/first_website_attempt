<div class="blackline"></div>
<div class="container">
    <div class="column">
        <img src="images/left_panel.jpg" width="100%">
    </div>
    <div class="column">
        <img id="login_center_logo" src="images/logo.png">
        <form class="login_form" action="actions/verify_login.php" method="post">
            <label class="login_big_text">Username:</label>
            <?php
                if (!isset($_SESSION['error'])){
                    echo '<input class="login_input" type="text" name="username" value=""><br><br><br>';
                    echo '<label class="login_big_text">Password:</label>';
                    echo '<input class="login_input" type="password" name="password" value=""><br><br><br>';
                }
                else if ($_SESSION['error'] == 1){
                    //wrong username
                    echo '<input class="login_input_error" type="text" name="username" value="'.$_SESSION['username'].'"><br>
                    <p class="login_error_text">Invalid Username</p><br><br>';
                    echo '<label class="login_big_text">Password:</label>';
                    echo '<input class="login_input" type="password" name="password" value=""><br><br><br>';
                    $_SESSION['error'] = NULL;
                }
                else if ($_SESSION['error'] == 2){
                    //wrong password
                    echo '<input class="login_input" type="text" name="username" value="'.$_SESSION['username'].'"><br><br><br>';
                    echo '<label class="login_big_text">Password:</label>';
                    echo '<input class="login_input_error" type="password" name="password" value=""><br>
                    <p class="login_error_text">Invalid Password</p><br><br>';
                    $_SESSION['error'] = NULL;
                }
                else {
                    //This should never happen, just here for redundancy
                    $_SESSION['error'] = NULL;
                    header("Location: main.php?page=login");
                }
            ?>
            <input class="login_button" type="submit" value="Login"><br>
            <br>
        </form> 
        <p class="login_small_text"> New here? <a style="color: black;" href="main.php?page=sign_up">Create an account</a></p>
    </div>
    <div class="column">
        <img src="images/right_panel.jpg" width="100%">
    </div>
</div>