function isUserLoggedIn() {
    const token = sessionStorage.getItem('token');

    if (token) {
        return true;
    } else {
        return false;
    }
}


export { isUserLoggedIn };
