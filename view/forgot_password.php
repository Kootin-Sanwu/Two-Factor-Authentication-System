<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css"> <!-- Change this to your login.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>OTP Verification</title>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <form action="../actions/forgot_password.php" method="POST" class="email-form">
                <h1>Email</h1>
                <p>Type in your email address</p>

                <div class="infield">
                    <input type="email" placeholder="Email" name="email" required>
                    <label>Email</label>
                </div>

                <!-- Hidden input to send the message to verify_otp.php -->
                <input type="hidden" name="message" value="Forgot Password">

                <button type="submit">Verify</button>
            </form>
        </div>
    </div>

    <script src="../js/verify_otp.js" defer></script>
</body>

</html>
