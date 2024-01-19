import UserModule from './user.js'

UserModule.getUsers().then((users) => {
    console.log(users)
});

UserModule.getUser('ergerg').then((user) => {
    console.log(user)
});