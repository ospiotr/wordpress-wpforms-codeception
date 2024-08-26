<?php

namespace Tests\Acceptance;

use Tests\Page\AdminPage;
use Tests\Page\PostEditorPage;
use Tests\Page\PostsListPage;
use Tests\Page\FrontendPostPage;
use Tests\Util\PageUtils;

class CreateContactFormAndSendMessageCest
{
    protected $contactFormPageUrl;

    public function CreateContactFormAndSendMessage($I)
    {
        $postEditorPage = new PostEditorPage($I);

        $I->loginAsAdmin();
        $I->amOnPluginsPage();
        $I->activatePlugin('wpforms-lite');
        $I->click("//li[@id='toplevel_page_wpforms-overview']");
        $I->click("//li[@id='toplevel_page_wpforms-overview']//li//a[text()='Settings']");
        $I->click("//div[@id='wpforms-settings']//ul//li//a[text()='Validation']");

        $newValidationMessageForRequiredField = 'Field required. This notice is added automatically for testing purposes';
        $I->fillField('input#wpforms-setting-validation-required', $newValidationMessageForRequiredField);
        $I->click("//button[@type='submit' and @name='wpforms-settings-submit']");
        $updatedMessage = $I->grabValueFrom('input#wpforms-setting-validation-required');
        $I->assertEquals($newValidationMessageForRequiredField, $updatedMessage);

        $this->createContactForm($I, "Test Contact Form");
        $this->embedContactFormInNewPage($I, 'Test Contact Form Page');

        $I->waitForElement('button.editor-post-url__panel-toggle');
        $postEditorPage->publishPage();
        $this->contactFormPageUrl = $postEditorPage->getPageUrl();
        $I->logOut(true);
        $I->amOnUrl($this->contactFormPageUrl);
        $I->waitForElement('button.wpforms-submit');

        // First try: required field is empty and email address is incorrect
        $this->fillAndSubmitContactForm($I, null, "West", "contact2example.com", 'This is a sample message used for testing purposes.');

        // Assert the validation messages
        $I->see($newValidationMessageForRequiredField, 'em.wpforms-error');
        $I->see('Please enter a valid email address.', 'em.wpforms-error');

        // Second try: valid test data
        $this->fillAndSubmitContactForm($I, "Adam", "West", "contact@example.com", 'This is a sample message used for testing purposes.');
        $I->waitForElement('div.wpforms-confirmation-scroll', '10');
        $I->see('Thanks for contacting us! We will be in touch with you shortly.');

    }

    protected function createContactForm($I, $formName)
    {
        $I->waitForElement("//li[@id='toplevel_page_wpforms-overview']//li//a[text()='Add New']");
        $I->click("//li[@id='toplevel_page_wpforms-overview']//li//a[text()='Add New']");
        $I->waitForElement('div.wpforms-toolbar');
        $I->wait('2');
        $this->hideWPFormsWelcomeModal($I);
        $I->waitForElement('input#wpforms-setup-name');
        $I->fillField('input#wpforms-setup-name', $formName);
        $I->moveMouseOver('div#wpforms-template-simple-contact-form-template');
        $I->click("//div[@id='wpforms-template-simple-contact-form-template']//div//a[@data-slug='simple-contact-form-template']");
        $I->waitForElement('button#wpforms-save');
        $I->click('button#wpforms-save');
    }

    protected function embedContactFormInNewPage($I, $pageTitle)
    {
        $I->waitForElement('button#wpforms-embed');
        $this->hideWPFormsWelcomeModal($I);
        $I->click('button#wpforms-embed');
        $I->waitForElement('div#wpforms-admin-form-embed-wizard');
        $I->click("//div[@id='wpforms-admin-form-embed-wizard-section-btns']/button[@data-action='create-page']");
        $I->fillField('input#wpforms-admin-form-embed-wizard-new-page-title', $pageTitle);
        $I->waitForElement("//div[@id='wpforms-admin-form-embed-wizard-section-go']//button[@class='wpforms-admin-popup-btn']");
        $I->click("//div[@id='wpforms-admin-form-embed-wizard-section-go']//button[@class='wpforms-admin-popup-btn']");
    }

    protected function fillAndSubmitContactForm($I, $firstName = '', $lastName = '', $email = '', $message = '')
    {
        if ($firstName) {
            $I->fillField('input.wpforms-field-name-first', $firstName);
        }
        if ($lastName) {
            $I->fillField('input.wpforms-field-name-last', $lastName);
        }
        $I->waitForElement("//input[@type='email']", '2');
        $I->fillField("//input[@type='email']", $email);
        $I->fillField('textarea.wpforms-field-medium', $message);
        $I->wait('2');
        $I->click('button.wpforms-submit');
    }

    protected function hideWPFormsWelcomeModal($I)
    {
        $I->executeJS("
            var elem = document.querySelector('.wpforms-challenge-popup-container');
            if (elem) {
                elem.style.display = 'none';    
            }
        ");
    }

    function _after($I)
    {
        $I->amGoingTo('Deactivate WPForms plugin');
        $I->loginAsAdmin();
        $I->amOnPluginsPage();
        $I->deactivatePlugin('wpforms-lite');
    }
}
