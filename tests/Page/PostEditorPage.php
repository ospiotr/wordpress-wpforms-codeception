<?php

namespace Tests\Page;

class PostEditorPage
{
    protected $I;

    public function __construct($I)
    {
        $this->I = $I;
    }

    /**
     * Workaround to close the Gutenberg welcome guide modal using custom JavaScript.
     */
    public function disableGutenbergModal()
    {
        $this->I->executeJS('wp.data.select("core/edit-post").isFeatureActive("welcomeGuide") && wp.data.dispatch("core/edit-post").toggleFeature("welcomeGuide");');
    }

    public function enterPostTitle($title)
    {
        $this->I->waitForElement("//iframe[contains(@name, 'editor-canvas')]");
        $this->I->switchToIFrame("//iframe[contains(@name, 'editor-canvas')]");
        $this->I->fillField("//h1[contains(@class, 'wp-block-post-title')]", $title);
        $this->I->switchToIFrame();
    }

    public function enterPostContent($content)
    {
        $this->I->switchToIFrame("//iframe[contains(@name, 'editor-canvas')]");
        $this->I->click("//p[contains(@class, 'block-editor-default-block-appender__content')]");
        $this->I->fillField("//p[contains(@class, 'block-editor-rich-text__editable')]", $content);
        $this->I->switchToIFrame();
    }

    public function publishPost()
    {
        $this->I->click('button.editor-post-publish-button__button');
        $this->I->waitForElement('div.editor-post-publish-panel__prepublish');
        $this->I->click('button.editor-post-publish-button');
    }

    public function publishPage()
    {
        $this->I->click('button.editor-post-publish-button__button');
        $this->I->waitForElement('div.editor-post-publish-panel__prepublish', '10');
        $this->I->click("//div[@class='editor-post-publish-panel__header']//div[@class='editor-post-publish-panel__header-publish-button']//button");
    }

    public function getPostUrl()
    {
        $this->I->waitForElement('div.post-publish-panel__postpublish-post-address');
        return $this->I->grabValueFrom("//div[contains(@class, 'post-publish-panel__postpublish-post-address')]//input[@type='text']");
    }

    public function getPageUrl()
    {
        $this->I->waitForElement('div.post-publish-panel__postpublish-post-address', '10');
        return $this->I->grabValueFrom("//div[contains(@class, 'post-publish-panel__postpublish-post-address')]//input[@type='text']");
    }
}
