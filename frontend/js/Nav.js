import { isUserLoggedIn } from "./userLoggedIn.js";
import { parseJwt } from "./decodeUser.js";

// ...

const loggedIn = isUserLoggedIn();

const actionLinks = document.querySelector(".__action-links");

if (loggedIn) {
    actionLinks.innerHTML =
        `
        <div class="navbar-nav mx-auto d-flex justify-content-center text-center">
            <a class="nav-item nav-link" href="/">Rasporedi</a>
            <a class="nav-item nav-link" href="/dashboard.html">Dashboard</a>
        </div>
    `;
} else {
    actionLinks.innerHTML =
        `
        <div class="navbar-nav mx-auto d-flex justify-content-center text-center">
            <a class="nav-item nav-link" href="/">Rasporedi</a>
        </div>
    `;
}

const userSectionDiv = document.querySelector(".__user-section");

if (loggedIn) {
    const userToken = sessionStorage.getItem("token");
    const parsedUserToken = parseJwt(userToken);

    userSectionDiv.innerHTML =
        `
        <div class="text-center mt-3">
        <div class="dropdown">
            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                ${parsedUserToken.username}
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li><a class="dropdown-item text-danger" href="#" id="logoutBtn">Odjava</a></li>
            </ul>
        </div>
    </div>    
    `;

    // ...
    const logoutBtn = document.getElementById("logoutBtn");
    logoutBtn.addEventListener("click", async function (e) {
        e.preventDefault();

        const bearerToken = sessionStorage.getItem('token');

        try {
            const response = await axios.options('http://192.168.1.106/api/v1/logout', {
                headers: {
                    'Authorization': `Bearer ${bearerToken}`,
                },
            });

            if (response.status === 200) {
                sessionStorage.removeItem('token');

                window.location.href = '/login.html';
            } else {
                console.error('Logout request failed:', response.status, response.statusText);
            }
        } catch (error) {
            console.error('Error during logout request:', error);
        }
    });
    // ...

} else {
    userSectionDiv.innerHTML =
        `
    <div class="text-center mt-3">
        <a href="/login.html" class="btn btn-sm btn-primary">
            Prijava
        </a>
    </div>
    `;
}

// ...