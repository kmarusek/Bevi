<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class Page extends Controller
{
    /**
     * Return Page title and hero from ACF
     *
     * @return array
     */
    public function hero()
    {
        return [
          'pageTitle' => get_the_title(),
        ];
    }
}
