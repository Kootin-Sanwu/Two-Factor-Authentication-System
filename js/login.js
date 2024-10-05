const container = document.getElementById('container');
const overlayCon = document.getElementById('overlayCon');
const overlayBtn = document.getElementById('overlayBtn');

overlayBtn.addEventListener('click', () => {
    container.classList.toggle('right-panel-active');

    overlayBtn.classList.remove('btnScaled');
    window.requestAnimationFrame(() => {
        overlayBtn.classList.add('btnScaled');
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.querySelector('.sign-in-container form');

    loginForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent form submission

        // Fetch input values
        const email = loginForm.querySelector('input[name="email"]').value.trim();
        const password = loginForm.querySelector('input[name="password"]').value.trim();

        // Basic validation
        if (email === '' || password === '') {
            Swal.fire("Error", "Please enter both email and password.", "error");
            return;
        }

        // Additional validation logic if needed

        // If all validations pass, you can submit the form
        loginForm.submit();
    });
});
