@extends('layouts.app')

@section('content')
@while(have_posts()) @php the_post() @endphp
<?php
  $category = get_the_category(); 
  ?>
<news-listing category-id="{{ json_encode($category[0]->cat_ID) }}" :categories="{{ json_encode($all_categories) }}" />
@endwhile
@endsection