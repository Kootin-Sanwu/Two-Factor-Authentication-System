<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/otp.css">
    <script src="../js/otp.js" defer></script> <!-- Link to the external JS file -->
    <title>OTP Verification</title>
</head>

<body>
    <div class="otp-card">
        <h1>OTP Verification</h1>
        <p>A code has been sent to your email address</p>

        <!-- OTP Form -->
        <form action="../actions/verify_otp.php" method="POST" class="otp-form">
            <div class="otp-card-inputs">
                <input type="text" name="OTP[]" maxlength="1" oninput="moveToNext(this)" autofocus>
                <input type="text" name="OTP[]" maxlength="1" oninput="moveToNext(this)">
                <input type="text" name="OTP[]" maxlength="1" oninput="moveToNext(this)">
                <input type="text" name="OTP[]" maxlength="1" oninput="moveToNext(this)">
                <input type="text" name="OTP[]" maxlength="1" oninput="moveToNext(this)">
                <input type="text" name="OTP[]" maxlength="1" oninput="moveToNext(this)">
            </div>

            <p>Didn't get the OTP? <a href="#" id="resend">Resend</a></p>
            <button type="submit">Verify</button>
        </form>
    </div>
</body>

</html>