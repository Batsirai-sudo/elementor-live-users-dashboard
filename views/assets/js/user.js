import http from './http.js'
export default (() => {
    const getUsers = () => {
       return http.get('users');
    };

    const getUser = (id) => {
        return http.get('user', id);
    };

    const polling = () => {
        return http.get('polling');
    };

    const authenticate = ($data) => {
        return http.post('login', $data)
    }

    return {
        getUsers,
        getUser,
        authenticate,
        polling,
    };
})();
