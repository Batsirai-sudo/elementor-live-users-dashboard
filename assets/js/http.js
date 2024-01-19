// http.js
export default (() => {
    const baseURL = 'http://localhost:8080'; // Replace with your actual base URL

    const performRequest = (url, method = 'GET', data = null) => {
        const absoluteURL = `${baseURL}/${url}`;

        const options = {
            method,
            headers: {
                'Content-Type': 'application/json',
            },
            body: data ? JSON.stringify(data) : null,
        };

        return fetch(absoluteURL, options)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .catch(error => {
                console.error('Error performing request:', error.message);
                throw error;
            });
    };

    const get = (endpoint, id = null) => {
        const url = id ? `${endpoint}/${id}` : endpoint;

        return performRequest(url);
    };

    const update = (endpoint, data) => {
        return performRequest(endpoint, 'PUT', data);
    };

    return {
        get,
        update
    };
})();