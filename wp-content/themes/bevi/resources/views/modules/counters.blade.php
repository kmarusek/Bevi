<?php
$counters = empty($block['counters']) ? $get_counters : (new App\Controllers\App())->getCounters(collect($block['counters'])->map(function ($counter) {
  return $counter['counter'];
})->toArray());
?>
<counters-component :counters="{{ json_encode($counters) }}" :block="{{ json_encode($block) }}"></counters-component>
