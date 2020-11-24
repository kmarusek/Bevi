<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class Index extends Controller
{

    public function hero()
    {
      return [
        'title' => 'Latest News',
        'show_bubbles' => true,
        'wave' => '3',
      ];
    }
}
