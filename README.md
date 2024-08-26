Hi! My name is Piotr.

This repository contains my automated tests created using Codeception along with [wp-browser library](https://wpbrowser.wptestkit.dev/).

Below you can review the test plan. Feel free to visit [Actions](https://github.com/ospiotr/wordpress-wpforms-codeception/actions) tab and check the workflow runs.

The first automated test focuses on testing the native functionalities of the core WordPress. The second test uses the **WPForms** plugin and verifies certain features, such as saving data to the database and form data validation.

Tests are integrated into GitHub Actions pipeline and can be triggered manually at any moment using GitHub virtual machines. Credentials are stored as GitHub secrets to ensure security.


[![Run Codeception Tests](https://github.com/ospiotr/wordpress-wpforms-codeception/actions/workflows/github-actions-run-codeception-tests.yml/badge.svg)](https://github.com/ospiotr/wordpress-wpforms-codeception/actions/workflows/github-actions-run-codeception-tests.yml)

**Test #1: Publish and Verify Post**

1. Login as Admin.
2. Navigate to Add New Post.
3. Create and enter a unique post title and content.
4. Publish the post and retrieve its URL.
5. Verify the post appears in the admin post list.
6. Check the post is correctly displayed on the frontend.
7. Cleanup: Move the post to Trash.

**Test #2: Create Contact Form using WP Forms, edit settings, check form validation and send test message**

1. Login as Admin.
2. Activate the WPForms plugin.
3. Configure custom validation messages for required fields.
4. Create a new contact form.
5. Embed the form on a new page and publish it.
6. Log out and access the contact form page.
7. Submit the form with invalid data (missing first name and incorrect email).
8. Verify validation errors are displayed.
9. Submit the form with valid data.
10. Verify the success message is displayed.
11. Cleanup: Deactivate the WPForms plugin.



The pipeline is quite straightforward. I was focused on web techniques without any database integration.

**Workflow: Run Codeception Test**

1. Checkout code.
2. Set up PHP 8.3 with required extensions.
3. Set up and run ChromeDriver.
4. Install dependencies with Composer.
5. Build Codeception tests.
6. Run _PublishAndVerifyPostCest_ test.
7. Run _CreateContactFormAndSendMessageCest_ test.
8. Upload test reports.

This workflow automates testing with Codeception on macOS.
