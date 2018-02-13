<?php

namespace NGiraud\PostType\Test;

use NGiraud\PostType\Models\PostType;

class Post extends PostType
{
    protected $table = 'posts';

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}