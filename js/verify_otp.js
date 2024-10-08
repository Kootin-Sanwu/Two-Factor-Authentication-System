
document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get('msg');
    const message2 = urlParams.get('msg2');

    // Define an array of valid messages that should trigger SweetAlert
    const validMessages = [
        "Password updated successfully.",
        "OTP verified successfully.",
        "Incorrect OTP. Please try again.",
        "Password updated successfully.",
        "OTP expired. Please try again.",
        "Invalid email or password.",
        "Email already exists. Please use a different email.",
        "Registration failed. Please try again later.",
        "Successfully registered. Kindly check your email for the OTP.",
        "Successfully signed in. Kindly check your email for the OTP.",
        "Passwords do not match.",
        "A code has been sent to your email address",
        "Email not found.",
        "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one digit, and one special character.",
    ];

    // Define an array of valid messages that should trigger SweetAlert
    const validMessages2 = [
        "A code has been sent to your email address",
    ];

    // Ensure SweetAlert2 is only called for valid messages
    if (message && validMessages.includes(message)) {
        Swal.fire({
            title: "Notice",
            text: message,
            icon: "info",    // Using "info" icon to match the context
            confirmButtonText: 'OK' // Customizable text for the button
        });
    }

    // Ensure SweetAlert2 is only called for valid messages
    if (message2 && validMessages2.includes(message2)) {
        Swal.fire({
            title: "Notice",
            text: message2,
            icon: "info",    // Using "info" icon to match the context
            confirmButtonText: 'OK' // Customizable text for the button
        });
    }
});

// Function to move to the next input when a digit is entered
function moveToNext(input) {
    if (input.value && input.nextElementSibling) {
        input.nextElementSibling.focus();
    }
}

// Adding 'keydown' event listeners to handle backspace navigation
document.querySelectorAll('.otp-card-inputs input').forEach((input) => {
    input.addEventListener('keydown', (event) => {
        if (event.key === 'Backspace' && !input.value && input.previousElementSibling) {
            input.previousElementSibling.focus();
        }
    });
});

