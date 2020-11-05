@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp
    <news-listing :categories="{{ json_encode($all_categories) }}" :posts="{{ json_encode($all_posts) }}" />
  @endwhile
@endsection