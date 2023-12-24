/*
 *  Document   : op_auth_signin.js
 *  Author     : pixelcave
 *  Description: Custom JS code used in Sign In Page
 */

class pageAuthSignIn {
  /*
   * Init Sign In Form Validation, for more examples you can check out https://github.com/jzaefferer/jquery-validation
   *
   */
  static initValidation() {
    // Load default options for jQuery Validation plugin
    Dashmix.helpers('jq-validation');

    // Init Form Validation
    jQuery('.js-validation-signin').validate({
      rules: {
        'email': {
            required: true,
            emailWithDot: true
        },
        'password': {
          required: true,
          minlength: 5
        }
      },
      messages: {
        'email': 'Please enter a valid email address',
        'password': {
          required: 'Please provide a password',
          minlength: 'Your password must be at least 5 characters long'
        }
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
Dashmix.onLoad(pageAuthSignIn.init());
