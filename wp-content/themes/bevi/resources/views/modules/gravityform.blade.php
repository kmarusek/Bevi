@php
    $block['the_form'] = do_shortcode('[gravityform id="' . $block['select_form'] . '" title="true"]');
@endphp
<gravity-form :block="{{ json_encode($block) }}"></gravity-form>