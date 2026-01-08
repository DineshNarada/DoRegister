# DoRegister - Advanced User Registration System

A comprehensive WordPress plugin that provides an advanced multi-step user registration system with login, frontend profile view, unified account page, and enhanced security controls.

## Features

### 🔐 Multi-Step Registration Form
- **5-Step Process**: Basic Information, Contact Details, Personal Details, Profile Photo Upload, Review & Confirm
- **AJAX-Powered**: Seamless frontend experience without page reloads
- **Progress Bar**: Visual indicator of registration progress
- **Step Navigation**: Next/Back buttons with validation blocking
- **Auto-Save**: Form data automatically saved to browser localStorage
- **Real-time Validation**: Client-side validation with inline error messages

### 📸 Profile Photo Upload
- **AJAX Upload**: Photos uploaded via AJAX to WordPress Media Library
- **Image Preview**: Instant preview before submission
- **File Validation**: Size limits (2MB) and type restrictions (JPG, PNG, GIF)
- **Media Library Integration**: Photos stored as WordPress attachments

### 🔑 Authentication System
- **Custom Login Form**: Frontend login interface (no wp-login.php dependency)
- **AJAX Login**: Secure login without page redirects
- **Error Handling**: Clear error messages and success redirects
- **Session Management**: Proper WordPress user sessions

### 👤 User Profile Management
- **Frontend Profile View**: Users can view their complete profile
- **Profile Photo Display**: Uploaded photos shown in profile
- **User Meta Display**: All custom fields (phone, country, interests, etc.)
- **Logout Functionality**: Secure logout with redirect

### 📄 Account Page System
- **Unified Account Page**: Single page with login/register/profile functionality
- **Tabbed Interface**: Clean tabs for login and registration options
- **Dynamic Content**: Content changes based on user login status
- **Manual Menu Control**: Full control over navigation menu placement
- **SEO-Friendly**: Dedicated page with proper URL structure

### �️ Security & Access Control
- **Frontend-Only Access**: Regular users can register/login from frontend but cannot access admin dashboard
- **Admin Bar Management**: Admin bar hidden for non-admin users, fully visible for administrators
- **AJAX Functionality**: All frontend AJAX features work without admin access
- **Profile Editing**: Users can still edit their profiles through allowed admin pages- **Administrator Access**: Users with administrator role retain full dashboard access
### 🎨 User Experience
- **Password Strength Meter**: Real-time password strength indication
- **Responsive Design**: Mobile-friendly interface
- **Smooth Transitions**: jQuery-powered step animations
- **Form Validation**: Comprehensive client and server-side validation

## Installation

1. **Download the plugin**
   - Download the `DoRegister` folder to your WordPress plugins directory

2. **Activate the plugin**
   - Go to WordPress Admin > Plugins
   - Find "DoRegister - Registration System"
   - Click "Activate"

3. **Configure shortcodes**
   - Add shortcodes to your pages/posts as needed

## Usage

### Shortcodes

Add these shortcodes to any page or post to display the respective forms:

#### Account Page (Recommended)
```
[doregister_account]
```
Displays a unified account page with:
- **For logged-out users**: Tabbed interface with Login and Register options
- **For logged-in users**: Profile information and logout button
- **Manual menu control**: Create a page with this shortcode and link it to your navigation menu

#### Registration Form
```
[doregister_form]
```
Displays the complete 5-step registration form.

#### Login Form
```
[doregister_login]
```
Displays the custom login form.

#### User Profile
```
[doregister_profile]
```
Displays the user profile (requires user to be logged in).

### Setting Up the Account Page (Recommended Method)

1. **Create a WordPress Page**
   - Go to **WordPress Admin > Pages > Add New**
   - Title: "Account" (or "My Account", "Login", etc.)
   - Add the shortcode: `[doregister_account]`
   - Publish the page

2. **Add to Navigation Menu**
   - Go to **Appearance > Menus**
   - Add your new Account page to the menu
   - Set the menu item text to "Account" or "My Account"
   - Assign the menu to your theme's primary navigation location
   - Save the menu

3. **Result**: Users can now click "Account" in your navigation to access login/register/profile functionality on a dedicated page.

### Security & Access Control

**User Access Levels:**
- **Regular Users**: Can register, login, and view/edit their profiles from the frontend only
- **Administrators**: Retain full access to WordPress dashboard and admin functions
- **Admin Bar**: Hidden for regular users, visible for administrators
- **Dashboard Access**: Blocked for non-admin users, full access for administrators

**Allowed Admin Access for Users:**
- Profile editing page (`/wp-admin/profile.php`)
- AJAX requests for plugin functionality
- WordPress AJAX endpoint (`admin-ajax.php`)

### Admin Interface

Access the admin interface:
- Go to **Users > DoRegister Users**
- View and manage all registered users
- Edit user meta fields
- View profile photos

## Requirements

- **WordPress**: 5.0 or higher
- **PHP**: 7.2 or higher
- **jQuery**: Included with WordPress
- **File Uploads**: Enabled on server
- **Media Library**: WordPress Media Library must be functional

## File Structure

```
DoRegister/
├── do-register.php              # Main plugin file
├── uninstall.php                # Uninstall script
├── includes/
│   ├── class-plugin.php         # Bootstrap class
│   ├── class-assets.php         # Asset management
│   ├── class-ajax.php           # AJAX handlers
│   ├── class-registration.php   # Registration logic
│   ├── class-login.php          # Login logic
│   ├── class-profile.php        # Profile logic
│   └── admin-user-management.php # Admin interface
├── templates/
│   ├── registration-form.php    # Registration form template
│   ├── login-form.php           # Login form template
│   └── profile-view.php         # Profile view template
├── assets/
│   ├── css/
│   │   ├── style.css            # Plugin styles
│   │   ├── login.css            # Login form styles
│   │   └── account.css          # Account page styles
│   └── js/
│       ├── registration.js      # Registration JavaScript
│       ├── login.js             # Login JavaScript
│       └── account.js           # Account page JavaScript
└── README.md                    # This file
```

## Security Features

- **Nonce Verification**: All AJAX requests protected with WordPress nonces
- **Input Sanitization**: All user inputs sanitized and validated
- **File Upload Security**: Strict file type and size validation
- **CSRF Protection**: Form submissions protected against cross-site request forgery
- **Admin Access Control**: Non-administrators cannot access /wp-admin dashboard
- **Admin Bar Visibility**: Admin bar hidden for non-admin users, visible for administrators
- **AJAX Access**: AJAX functionality remains available for frontend features
- **WordPress Standards**: Follows all WordPress security best practices

## Customization

### Styling
Modify `assets/css/style.css` to customize the appearance of forms and interface elements.

### JavaScript
Extend functionality by modifying:
- `assets/js/registration.js` - Registration form behavior
- `assets/js/login.js` - Login form behavior

### Templates
Customize form HTML by editing templates in the `templates/` directory.

## Hooks and Filters

### Actions
- `doregister_user_registered` - Fires after successful user registration
- `doregister_user_logged_in` - Fires after successful login
- `doregister_photo_uploaded` - Fires after photo upload

### Filters
- `doregister_registration_fields` - Modify registration form fields
- `doregister_user_meta_fields` - Modify saved user meta fields
- `doregister_upload_limits` - Modify file upload limits

## Troubleshooting

### Common Issues

**Form not loading**
- Ensure jQuery is enabled in WordPress
- Check browser console for JavaScript errors
- Verify shortcodes are properly added

**Photo upload failing**
- Check file permissions on wp-content/uploads
- Verify PHP upload limits in php.ini
- Ensure Media Library is functional

**AJAX requests failing**
- Check WordPress admin-ajax.php accessibility
- Verify nonces are properly generated
- Check server error logs

### Debug Mode
Enable WordPress debug mode to see detailed error messages:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

## Changelog

### Version 1.1.0
- Added unified account page shortcode `[doregister_account]`
- Implemented manual menu control system
- Replaced popup modal with dedicated page interface
- Added tabbed login/register interface
- Enhanced responsive design for account pages
- Improved user experience with page-based navigation
- Added admin access restrictions for non-admin users
- Implemented admin bar visibility control
- Enhanced security with frontend-only user access

### Version 1.0.0
- Initial release
- Multi-step registration form
- AJAX photo upload
- Custom login interface
- Frontend profile view
- Admin user management
- Password strength meter
- Responsive design

## License

This plugin is licensed under the GPL v2 or later.

```
DoRegister - Advanced User Registration System
Copyright (C) 2025, Dinesh Narada

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
```

## Support

For support, bug reports, or feature requests:
- Create an issue on GitHub
- Check the WordPress.org support forums
- Review the documentation in `LEARN.MD`

## Contributing

Contributions are welcome! Please:
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## Credits

Developed by Dinesh Narada
- Plugin architecture and core functionality
- AJAX implementation
- Security hardening
- WordPress integration# DoRegister
