<?php

namespace Tests\Page;

class FrontendPostPage
{
    protected $I;

    public function __construct($I)
    {
        $this->I = $I;
    }

    public function seePublishedPostTitleContentFrontend($postTitle, $postContent)
    {
        $this->I->see($postTitle, 'h1.wp-block-post-title');
        $this->I->see($postContent, 'div.entry-content');
    }
}
