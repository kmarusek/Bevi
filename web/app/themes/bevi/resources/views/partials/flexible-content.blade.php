@foreach($flexible_content->content_blocks as $block)
  @include('modules.'.$block['acf_fc_layout'], array('data' => $block))
@endforeach
