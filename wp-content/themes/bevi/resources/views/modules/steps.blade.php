<div class="container py-20 xl:max-w-6xl">
  <h1 class="my-4 heading-two text-center">
    {{ $block['title'] }}
  </h1>
  <div class="max-w-2xl text-lg mx-auto pb-12 text-center">
    {!! $block['content'] !!}
  </div>
  <div class="lg:flex -m-3">
    @foreach ($block['steps'] as $key => $step)
      <div class="flex-1 p-3">
        <div class="flex">
          <img
            src="{{ $step['image']['url'] }}"
            width="{{ $step['image']['width'] }}"
            height="{{ $step['image']['height'] }}"
            alt="{{ $step['image']['alt'] }}"
            loading="lazy"
            class="w-24 h-24"
          >
          <div class="flex-1 pl-6">
            <h2 class="font-medium text-2xl mb-2">
              Step {{ $key + 1 }}
            </h2>
            <div>
              {!! $step['content'] !!}
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>
