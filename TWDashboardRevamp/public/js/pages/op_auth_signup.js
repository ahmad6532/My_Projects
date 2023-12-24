/*
 *  Document   : op_auth_signup.js
 *  Author     : pixelcave
 *  Description: Custom JS code used in Sign Up Page
 */

class pageAuthSignUp {
    /*
     * Init Sign Up Form Validation, for more examples you can check out https://github.com/jzaefferer/jquery-validation
     *
     */
    static initValidation() {
        // Load default options for jQuery Validation plugin
        Dashmix.helpers('jq-validation');

        // Init Form Validation
        jQuery('.js-validation-signup').validate({
            rules: {
                'first_name': {
                    required: true,
                    minlength: 3
                },
                'last_name': {
                    required: true,
                    minlength: 3
                },
                'phone_number': {
                    required: true,
                    minlength: 10
                },
                'email': {
                    required: true,
                    emailWithDot: true
                },
                'signup-password': {
                    required: true,
                    minlength: 6
                },
                'signup-password-confirm': {
                    required: true,
                    equalTo: '#signup-password'
                },
                'vendor_promocode': {
                    required: true,
                    minlength: 6
                },
                'signup-terms': {
                    required: true
                }
            },
            messages: {
                'first_name': {
                    required: 'Please enter your first name',
                    minlength: 'Your username must consist of at least 3 characters'
                },
                'last_name': {
                    required: 'Please enter your last name',
                    minlength: 'Your Last Name must consist of at least 3 characters'
                },
                'phone_number': {
                    required: 'Please enter your phone number',
                    minlength: 'Your phone must consist of at least 10 characters'
                },
                'email': 'Please enter a valid email address',
                'signup-password': {
                    required: 'Please provide a password',
                    minlength: 'Your password must be at least 6 characters long'
                },
                'signup-password-confirm': {
                    required: 'Please provide a password',
                    minlength: 'Your password must be at least 6 characters long',
                    equalTo: 'Please enter the same password'
                },
                'vendor_promocode': {
                    required: 'Please enter a promocode',
                    minlength: 'Your promocode must consist of at least 6 characters'
                },
                'signup-terms': 'You must agree to the service terms!'
            }
        });
    }

    /*
     * Init functionality
     *
     */
    static init() {
        this.initValidation();
    }
}

// Initialize when page loads
Dashmix.onLoad(pageAuthSignUp.init());
