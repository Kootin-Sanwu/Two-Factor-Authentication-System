document.addEventListener('DOMContentLoaded', function () {
    const signUpForm = document.querySelector('.sign-up-container form');

    signUpForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent form submission

        // Fetch input values
        const firstName = signUpForm.querySelector('input[name="firstName"]').value.trim();
        const lastName = signUpForm.querySelector('input[name="lastName"]').value.trim();
        const email = signUpForm.querySelector('input[name="email"]').value.trim();
        const password = signUpForm.querySelector('input[name="password"]').value.trim();
        const confirmPassword = signUpForm.querySelector('input[name="confirmPassword"]').value.trim();

        // Basic validation
        if (firstName === '' || lastName === '' || email === '' || password === '' || confirmPassword === '') {
            Swal.fire("Error", "All fields are required.", "error");
            return;
        }

        // Password match validation
        if (password !== confirmPassword) {
            Swal.fire("Error", "Passwords do not match.", "error");
            return;
        }

        // Additional validation logic if needed

        // If all validations pass, you can submit the form
        signUpForm.submit();
    });
});

