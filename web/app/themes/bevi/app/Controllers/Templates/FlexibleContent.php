<?php

namespace App\Controllers\Templates;

trait FlexibleContent
{
    public function flexibleContent()
    {
        return (object)array(
            'content_blocks' => get_field('flexible_content')
        );
    }
}
