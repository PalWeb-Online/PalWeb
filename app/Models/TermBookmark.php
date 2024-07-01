<?php

namespace App\Models;

class TermBookmark extends ModelBookmark
{
    protected $table = 'markable_bookmarks';

    protected $bookmarkModel = Term::class;
}
