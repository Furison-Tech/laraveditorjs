<div style="display: grid; gap: 10px; padding: 10px;
            grid-template-columns: repeat({{count($data['cols'])}}, 1fr)" >
    @foreach($data['cols'] as $col)
        <div>
            @foreach ($col['blocks'] as $block)
                @include('laraveditorjs::blocks.' . $block['type'], ['data' => $block['data']])
            @endforeach
        </div>
    @endforeach
</div>