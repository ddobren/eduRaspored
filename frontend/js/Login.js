import { isUserLoggedIn } from "./userLoggedIn.js";

// ...

const loggedIn = isUserLoggedIn();

if (loggedIn) {
    window.location.href = '/dashboard.html';
}

// ...

const loginForm = document.getElementById('loginForm');
const errorMessageDiv = document.getElementById('error-message');

loginForm.addEventListener('submit', async function (event) {
    event.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    if (!email.trim() || !password.trim()) {
        showErrorMessage('Korisničko ime i lozinka su obavezni.');
        return;
    }

    const apiUrl = `http://192.168.1.106/api/v1/login/${email}/${password}`;

    try {
        const response = await axios.get(apiUrl);

        const data = response.data;
        if (data.token) {
            sessionStorage.setItem('token', data.token);

            window.location.href = '/dashboard.html';
        } else {
            showErrorMessage(`${data.error}`);
        }
    } catch (error) {
        if (error.response && error.response.status === 401) {
            showErrorMessage('Pogrešno korisničko ime ili lozinka. Molimo pokušajte ponovno.');
        } else {
            showErrorMessage(`Greška prilikom zahtjeva za prijavu. Molimo pokušajte ponovno. ${error.message}`);
        }

        console.error(error.message);
    }
});

function showErrorMessage(message) {
    errorMessageDiv.textContent = message;
    errorMessageDiv.classList.remove('d-none');
}
