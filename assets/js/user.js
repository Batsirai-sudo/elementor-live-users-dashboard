import http from './http.js'
export default (() => {
    const getUsers = () => {
       return http.get('users');
    };

    const getUser = (id) => {
        return http.get('user', id);
    };

    return {
        getUsers,
        getUser
    };
})();
