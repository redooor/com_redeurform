# After Installing

Create a menu item → New → Redeu Contact Form to expose the frontend form
Go to Components → Redeu Contact Form → Settings and fill in:

Receiving Email — where submissions land
Send-as Email — the From address (optional)
Email Template — body with {name}, {email}, {phone}, {message} placeholders
reCAPTCHA Site Key + Secret Key — from Google reCAPTCHA Admin (leave blank to disable)

View submissions at Components → Redeu Contact Form → Submissions — select rows and use the Delete toolbar button to remove them

# Admin Configuration Requirements

For the captcha to appear on your form, the site administrator must:

1. Enable the Plugin: Go to Extensions → Plugins, find and enable CAPTCHA - reCAPTCHA.
2. Enter Keys: Input the Google Site Key and Secret Key into the plugin settings.
3. Set as Default: Go to System → Global Configuration → Site tab and set Default Captcha to "CAPTCHA - reCAPTCHA". 
