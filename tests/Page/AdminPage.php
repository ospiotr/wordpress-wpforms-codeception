<?php

namespace Tests\Page;

class AdminPage
{
    protected $I;

    public function __construct($I)
    {
        $this->I = $I;
    }
    
    public function goToAdminAddNewPost()
    {
        $this->I->amOnAdminPage('/post-new.php');
    }
    
    public function goToAdminAllPosts()
    {
        $this->I->amOnAdminPage('/edit.php');
    }
}
