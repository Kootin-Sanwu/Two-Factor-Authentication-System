document.addEventListener('DOMContentLoaded', function () {

    var logoutButton = document.querySelector(".submit button[name=logoutButton]");

    function submitHover(button) {
        button.style.transform = 'translateY(-2px)';
        button.style.backgroundColor = '#232e3a';
        button.style.boxShadow = '5px 5px grey';
    }

    function closeHover(button) {
        button.style.transform = 'translateY(-2px)';
        button.style.backgroundColor = '#red';
        button.style.boxShadow = '5px 5px grey';
    }

    function resetStyles() {
        logoutButton.style.transform = 'translateY(2px)';
        logoutButton.style.backgroundColor = '#232e3a';
        logoutButton.style.boxShadow = '1px 1px grey';
    }

    function closeResetStyles() {
        closeButton.style.transform = 'translateY(2px)';
        closeButton.style.backgroundColor = '#red';
        closeButton.style.boxShadow = '1px 1px grey';
    }

    function applyAddPressedEffect(button) {
        button.style.transform = 'translateY(4px)';
        button.style.backgroundColor = "black";
        button.style.boxShadow = 'none';
    }

    function closePopup() {
        window.location.href = "../admin/managechores.php";
    }

    logoutButton.addEventListener('mouseover', function () {
        submitHover(this);
    });

    logoutButton.addEventListener('mouseout', function () {
        resetStyles();
    });

    logoutButton.addEventListener('mousedown', function () {
        applyAddPressedEffect(this);
    });

    logoutButton.addEventListener('mouseup', function () {
        submitHover(this);
        addChore(this);
    });

    closeButton.addEventListener('mouseover', function () {
        closeHover(this);
    });

    closeButton.addEventListener('mouseout', function () {
        closeResetStyles();
    });

    closeButton.addEventListener('mousedown', function () {
        applyAddPressedEffect(this);
        closePopup(this);
    });

});

function openPopup() {
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('popup').style.display = 'block';
}

