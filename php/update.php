<?php
    if (isset($_POST['verify'])) {
        $details = $_SESSION['user'];
        $verifyPassword = hash("sha512", $_POST['verifyPassword']);
        $user_id = $details['id'];

        $sql = "SELECT * FROM users where id = '$user_id' AND password = '$verifyPassword'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 1) {

            $first_name = $_POST['fname'];
            $last_name = $_POST['lname'];
            $email = $_POST['email'];
            $contact_num = $_POST['cnum'];
            $sql = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', email = '$email', contact_num = $contact_num WHERE id = '$user_id'";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $sql = "SELECT * from users where id='$user_id'";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) == 1) {

                    //session_start();
                    $row = mysqli_fetch_array($result);
                    unset($_SESSION['login']);
                    unset($_SESSION['user']);
                    $_SESSION['login'] = true;
                    $_SESSION['user'] = $row;
                    //$details = $_SESSION['user'];
                    //header("location: index.php");
                } else {}
                //echo $details['first_name'];
                echo '<script type="text/javascript">
                alert("Update Successful.");
                reload_page();
                </script>';

            }
            //header("location: index.php");
        } else {
            echo "<h2>Invalid username or password</h2>";
        }
    }

    if(isset($_POST['change_pass'])) {
        $old_pass = hash("sha512", $_POST['old_password']);
        $unhashed_pword1 = $_POST['new_password1'];
        $new_pass1 = hash("sha512", $_POST['new_password1']);
        $new_pass2 = hash("sha512", $_POST['new_password2']);
        //$new_pass1 = $_POST['new_password1'];
        //$new_pass2 = $_POST['new_password2'];

        $details = $_SESSION['user'];
        function showError($err_msg) {
            echo "<script type='text/javascript'>",
            "showAlert('$err_msg');",
            "</script>";
        }
        if ($old_pass != $details['password']) {
            //$err_msg = "Entered old password is incorrect.";
            //showError($err_msg);

            echo '<script type="text/javascript">
                alert("Incorrect old password");
                reload_page();
                </script>';
        }
        else if ($new_pass1 != $new_pass2) {
            //$err_msg = "New password and Re-Enter new password should be the same.";
            //showError($err_msg);
            echo '<script type="text/javascript">
                alert("passwords do not match");
                reload_page();
                </script>';
        } else if(strlen($unhashed_pword1) < 4) {
            // tell the user something went wrong
            //echo $alert_trg;
            //$err_msg = "Password should be at least 4 characters long.";
            //showError($err_msg);
            echo '<script type="text/javascript">
            alert("Password should be at least 4 characters");
            reload_page();
            </script>';
        } else {
            $user_id = $details['id'];
            $sql = "UPDATE users SET password='$new_pass1' WHERE id='$user_id'";

            $result = mysqli_query($conn, $sql);
            if ($result) {
                echo "<script type='text/javascript'>",
                "showSuccess();",
                "</script>";
            } else {
                echo "<p>Oops! Something went wrong.</p>";
            }
        }
    }
?>