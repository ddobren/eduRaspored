function parseJwt (token) {
    var base64Url = token.split('.')[1];
    var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    var jsonPayload = decodeURIComponent(window.atob(base64).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));

    return JSON.parse(jsonPayload);
}

console.log(parseJwt("eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MTEsInVzZXJuYW1lIjoibHVrYSIsImVtYWlsIjoibHVrYTIzQGdtYWlsLmNvbSIsImV4cCI6MTcwNzczMjAwNH0.bp2ZWkMrR-B9Ju1yIeGDJ8Ua5f3EULwqrl2l3-RA3Yk"));




