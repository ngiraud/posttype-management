<?php

namespace NGiraud\PostType\Interfaces;


interface PostType
{
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;

    public function ruleStatus();
}