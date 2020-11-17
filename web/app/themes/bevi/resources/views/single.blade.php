@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp
    <author-block :post-data="{{ json_encode(App::getPostData()) }}" />
  @endwhile
@endsection
