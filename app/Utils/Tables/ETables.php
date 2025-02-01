<?php

namespace App\Utils\Tables;

enum ETables: string
{
    case USER = 'users';
    case USER_PROFILE = 'user_profiles';
    case PRODUCT = 'products';
    case CATEGORY = 'categories';
    case CATEGORY_PRODUCT = 'category_products';
}
