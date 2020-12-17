@extends('layouts.app')

@section('content')
@while(have_posts()) @php the_post() @endphp
<?php
  $category = get_the_category(); 
  ?>
  <page-hero :block="{{ json_encode(get_field('hero', get_option('page_for_posts'))) }}" :category="{{ json_encode($category[0]->name) }}"></page-hero>
  <news-listing class="pt-20" category-id="{{ json_encode($category[0]->cat_ID) }}" :categories="{{ json_encode($all_categories) }}" />
@endwhile
@endsection