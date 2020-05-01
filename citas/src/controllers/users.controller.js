const usersCtrl = {};

const passport = require('passport');

const User = require('../models/User');

usersCtrl.renderSignUpForm = (req, res) => {
    res.render('users/signup');
};

usersCtrl.signup = async (req, res) => {
    const errors = [];
    const { name, email, password, confirm_password } = req.body;
    if (password != confirm_password) {
        errors.push({text: 'Passwords do not match.'});
    }
    if (password.lenght < 4) {
        errors.push({text: 'Passwords must be at least 4 characters.'});
    }
    if (errors.length > 0){
        res.render('users/signup', {
            errors,
            name,
            email,
            password,
            confirm_password
        });
    } else {
       const emailUser = await User.findOne({email: email});
       if (emailUser) {
           req.flash('error_msg', 'This email is already in use.');
           res.redirect('/users/signup');
       } else {
           const newUser = new User({ name, email, password });
           newUser.password = await newUser.encryptPassword(password);
           await newUser.save();
           req.flash('success_msg', 'You have been registered successfully');
           res.redirect('/users/signin');
       }
    }
    //res.send('received');
    res.redirect('/users/signup');
};

usersCtrl.renderSignInForm = (req, res) => {
    res.render('users/signin');
};

usersCtrl.signin = passport.authenticate('local', {
    failureRedirect: '/users/signin',
    successRedirect: '/citas',
    failureFlash: true
});


usersCtrl.logout = (req, res) => {
    req.logout();
    req.flash('success_msg', 'Your session has been closed successfully.');
    res.redirect('/users/signin');
};

module.exports = usersCtrl;