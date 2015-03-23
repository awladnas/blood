<div class='container'>
    <div class='row'>
        @extends('layouts.scaffold')
        <style>
            .pos_left {
                position: relative;
                left: 0px;
            }

            .pos_right {
                position: relative;
                left: 20px;
            }
        </style>
        <h3>Api Url Lists:</h3>
        @section('main')
            @foreach($documents as $index => $document)
                <h4 class="pos_left"><li><a href="/admin/documents/{{$document->id}}"> {{ $document->url }}</a></li></h4>
                 <p class="pos_right">{{ $document->description }}</p>
            @endforeach
        @stop
    </div>
</div>