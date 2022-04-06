<?php
$counters = empty($block['counters']) ? $get_counters : (new App\Controllers\App())->getCounters(collect($block['counters'])->map(function ($counter) {
    return $counter['counter'];
})->toArray());
?>
<machine-comparison :counters="{{ json_encode($counters) }}" :block="{{ json_encode($block) }}"></machine-comparison>
