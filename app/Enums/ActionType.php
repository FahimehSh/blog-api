<?php

namespace App\Enums;

enum ActionType: string
{
    case LIKE = 'like';
    case BOOKMARK = 'bookmark';
}
