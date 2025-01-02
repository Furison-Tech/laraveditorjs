@foreach ($blocks as $block)
    @include('laraveditorjs::blocks.' . $block['type'], ['data' => $block['data']])
@endforeach