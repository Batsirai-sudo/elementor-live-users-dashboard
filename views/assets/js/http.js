export default (() => {
    const baseURL = 'http://localhost:8080';
    const performRequest = (url, method = 'GET', data = null) => {
        const absoluteURL = `${baseURL}/${url}`;

        // Get the token from the cookie
        const token = getCookie('token');
        const headers = {
            'Content-Type': 'application/json',
        };

        // Include the token in the X-Token header if available
        if (token) {
            headers['X-Token'] = token;
        }
        const options = {
            method,
            headers,
            body: data ? JSON.stringify(data) : null,
        };

        return fetch(absoluteURL, options)
            .then(response => {

                console.log(response);

                if (response.status === 401 && !window.location.pathname.endsWith("index.html")) {
                    window.location.href = 'index.html';
                }

                return response;
            })
            .then(response => response.json())
            .catch(error => {
                console.error('Error performing request:', error.message);
                throw error;
            });
    };

    // Function to get a cookie value by name
    const getCookie = (name) => {
        const cookies = document.cookie.split(';').map(cookie => cookie.trim());
        for (const cookie of cookies) {
            const [cookieName, cookieValue] = cookie.split('=');
            if (cookieName === name) {
                return cookieValue;
            }
        }
        return null;
    };

    const get = (endpoint, id = null) => {
        const url = id ? `${endpoint}/${id}` : endpoint;

        return performRequest(url);
    };

    const update = (endpoint, data) => {
        return performRequest(endpoint, 'PUT', data);
    };

    const post = (endpoint, data) => {
        return performRequest(endpoint, 'POST', data);
    };

    return {
        get,
        update,
        post
    };
})();