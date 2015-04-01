@extends('layouts.default')
@section('title', 'Document')

@section('main')

<h1>Document Details</h1>

    <h3>Url:  <span class="normal"><a href="#">{{{ $document->url }}}</a></span></h3>


    <h3>Description: <span class = "normal"><pre>{{{ $document->description }}}</pre></span></h3>


    <h3>Input format: </h3>
    <p><pre>{{{ $document->input_format }}}</pre></p>

    <h3>Output format: </h3>
    <p><pre>{{{ $document->output_format }}}</pre></p>

    <h3>Api version: <span class="normal">{{{ $document->api_version }}}</span></h3>

    <h3>Request method: <span class="normal">{{{ $document->request_method }}}</span></h3>

    {{ link_to_route('admin.documents.index', 'Back', $document->id, array('class' => 'btn btn-primary')) }}
    <div class="pos_right">

        {{ Form::open(array('style' => 'display: inline-block;', 'method' => 'DELETE', 'route' => array('admin.documents.destroy', $document->id), 'onsubmit' => 'return ConfirmDelete()')) }}
        {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
        {{ Form::close() }}

        {{ link_to_route('admin.documents.edit', 'Edit', array($document->id), array('class' => 'btn btn-info')) }}
    </div>


@stop
