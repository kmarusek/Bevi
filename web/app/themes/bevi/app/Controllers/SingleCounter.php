<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class SingleCounter extends Controller
{
    public static function counter()
    {
        return [
          'pageTitle' => get_the_title(),
        ];
    }
}
