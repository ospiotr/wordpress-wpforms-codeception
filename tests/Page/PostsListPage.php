<?php

namespace Tests\Page;

class PostsListPage
{
    protected $I;

    public function __construct($I)
    {
        $this->I = $I;
    }

    public function seePostOnTheList($postId, $postTitle)
    {
        $xpathForFindingThePost = sprintf("//*[@id='post-%d']//a[contains(text(), '%s')]", $postId, $postTitle);
        $this->I->seeElement($xpathForFindingThePost);
    }

    public function deletePostOnTheList($postId, $postTitle)
    {
        $xpathForPostTitle = sprintf("//a[text()='%s']", $postTitle);
        $this->I->moveMouseOver($xpathForPostTitle);
        $xpathForFindingTheTrashLink = sprintf("//*[name()='a' and contains(@href, 'post.php?post=%d&action=trash')]", $postId);
        $this->I->click($xpathForFindingTheTrashLink);
        $xPathForUndoLinkInSuccessNoticeAfterTrashing = sprintf(
            "//*[name()='a' and contains(@href, 'edit.php?post_type=post&doaction=undo&action=untrash&ids=%d')]",
            $postId
        );
        $this->I->seeElement($xPathForUndoLinkInSuccessNoticeAfterTrashing);
    }
}
