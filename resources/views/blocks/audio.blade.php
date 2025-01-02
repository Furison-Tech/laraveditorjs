<audio class="audio @if($data['canDownload']) audio--canDownload @endif" @if(!$data['canDownload']) controlsList="nodownload" @endif controls>
    <source src="{{ $data['file']['url'] }}">
</audio>