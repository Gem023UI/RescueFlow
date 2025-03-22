<?php
session_start();
include("../includes/config.php");

// Display session messages (if any)
if (isset($_SESSION['message'])) {
    echo "<p>{$_SESSION['message']}</p>";
    unset($_SESSION['message']);
}

// Handle login form submission
if (isset($_POST['submit'])) {
    $uname = trim($_POST['uname']); // Username (email) from form
    $pass = trim($_POST['password']); // Password from form

    // Query the Personnel table
    $sql = "SELECT PersonnelID, FirstName, LastName, Email, RoleID, Password FROM Personnel WHERE Email = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $uname);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) === 1) {
            mysqli_stmt_bind_result($stmt, $personnelID, $firstName, $lastName, $email, $roleID, $hashedPassword);
            mysqli_stmt_fetch($stmt);

            // Verify the password
            if (password_verify($pass, $hashedPassword)) {
                // Store user data in session
                $_SESSION['username'] = $firstName . " " . $lastName;
                $_SESSION['PersonnelID'] = $personnelID; // Ensure this matches AttendanceButton.php
                $_SESSION['role'] = $roleID;
                $_SESSION['email'] = $email;

                // Redirect to dashboard
                header("Location: ../dashboard/RescueFlowIndex.php");
                exit();
            } else {
                $_SESSION['message'] = 'Wrong username or password';
            }
        } else {
            $_SESSION['message'] = 'Wrong username or password';
        }

        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['message'] = 'Database error';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="LoginRegister.css">
    <title>BFP - Login / Register</title>
</head>
<body>
    <div class="container" id="container">
        <div class="form-container sign-up">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <h1>LOGIN</h1>
                <!-- Username input -->
                <div class="input-box">
                    <input type="text" name="uname" placeholder="Email" required />
                    <input type="password" name="password" placeholder="Password" required />
                </div>
                <button type="submit" class="btn" name="submit">LOGIN</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form action="RegisterStore.php" method="POST">
                <h1>REGISTER</h1>
                <!-- Name and Email inputs -->
                <div class="input-box">
                    <input type="text" class="form-control" id="uname" name="uname" placeholder="First Name" required />
                    <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" required />
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required />
                </div>
                <!-- Password inputs -->
                <div class="input-box">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required />
                    <input type="password" class="form-control" id="password2" name="confirmPass" placeholder="Confirm Password" required />
                </div>
                <!-- Submit button -->
                <button type="submit" class="btn" name="submit">REGISTER</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>GREETINGS!</h1>
                    <div class="hotlines">
                        <p>BFP NCR - Taguig City Station 1</p>
                        <p>HOTLINE</p>
                        <p>HOTLINE</p>
                        <p>HOTLINE</p>
                    </div>
                    <button class="hidden" id="login">Not Registered Yet?</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <div class="social-icons">
                        <h1>WELCOME!</h1>
                        <p>Stay Updated with BFP Social Media Platforms</p>
                        <a href="facebook" class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0,0,256,256" width="20px" height="20px" fill-rule="nonzero"><g fill="#ffffff" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(5.12,5.12)"><path d="M25,3c-12.15,0 -22,9.85 -22,22c0,11.03 8.125,20.137 18.712,21.728v-15.897h-5.443v-5.783h5.443v-3.848c0,-6.371 3.104,-9.168 8.399,-9.168c2.536,0 3.877,0.188 4.512,0.274v5.048h-3.612c-2.248,0 -3.033,2.131 -3.033,4.533v3.161h6.588l-0.894,5.783h-5.694v15.944c10.738,-1.457 19.022,-10.638 19.022,-21.775c0,-12.15 -9.85,-22 -22,-22z"></path></g></g></svg>
                        </a>
                        <a href="instagram" class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0,0,256,256" width="20px" height="20px" fill-rule="nonzero"><g fill="#ffffff" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(8.53333,8.53333)"><path d="M9.99805,3c-3.859,0 -6.99805,3.14195 -6.99805,7.00195v10c0,3.859 3.14195,6.99805 7.00195,6.99805h10c3.859,0 6.99805,-3.14195 6.99805,-7.00195v-10c0,-3.859 -3.14195,-6.99805 -7.00195,-6.99805zM22,7c0.552,0 1,0.448 1,1c0,0.552 -0.448,1 -1,1c-0.552,0 -1,-0.448 -1,-1c0,-0.552 0.448,-1 1,-1zM15,9c3.309,0 6,2.691 6,6c0,3.309 -2.691,6 -6,6c-3.309,0 -6,-2.691 -6,-6c0,-3.309 2.691,-6 6,-6zM15,11c-2.20914,0 -4,1.79086 -4,4c0,2.20914 1.79086,4 4,4c2.20914,0 4,-1.79086 4,-4c0,-2.20914 -1.79086,-4 -4,-4z"></path></g></g></svg>
                        </a>
                        <a href="google" class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 32 32" width="20px" height="20px" fill="#ffffff"><path d="M 16.003906 14.0625 L 16.003906 18.265625 L 21.992188 18.265625 C 21.210938 20.8125 19.082031 22.636719 16.003906 22.636719 C 12.339844 22.636719 9.367188 19.664063 9.367188 16 C 9.367188 12.335938 12.335938 9.363281 16.003906 9.363281 C 17.652344 9.363281 19.15625 9.96875 20.316406 10.964844 L 23.410156 7.867188 C 21.457031 6.085938 18.855469 5 16.003906 5 C 9.925781 5 5 9.925781 5 16 C 5 22.074219 9.925781 27 16.003906 27 C 25.238281 27 27.277344 18.363281 26.371094 14.078125 Z"/></svg>
                        </a>
                        <a href="twitter" class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0,0,256,256" width="20px" height="20px" fill-rule="nonzero"><g fill="#ffffff" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(5.12,5.12)"><path d="M11,4c-3.866,0 -7,3.134 -7,7v28c0,3.866 3.134,7 7,7h28c3.866,0 7,-3.134 7,-7v-28c0,-3.866 -3.134,-7 -7,-7zM13.08594,13h7.9375l5.63672,8.00977l6.83984,-8.00977h2.5l-8.21094,9.61328l10.125,14.38672h-7.93555l-6.54102,-9.29297l-7.9375,9.29297h-2.5l9.30859,-10.89648zM16.91406,15l14.10742,20h3.06445l-14.10742,-20z"></path></g></g></svg>
                        </a>
                    </div>
                    <button class="hidden" id="register">Have an Account?</button>
                </div>
            </div>
        </div>
    </div>
    <script src="LoginRegister.js"></script>
</body>
</html>