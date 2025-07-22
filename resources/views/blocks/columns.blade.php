<div style="display: grid; gap: 10px; padding: 10px;
            grid-template-columns: repeat({{count($data['cols'])}}, 1fr)" >
    @foreach($data['cols'] as $col)
        @include('blocks.' . $col['type'], ['data' => $col])
    @endforeach
</div>