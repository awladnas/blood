@extends('layouts.scaffold')

@section('main')

<h1>Show Document</h1>

<p>{{ link_to_route('admin.documents.index', 'Return to All documents', null, array('class'=>'btn btn-lg btn-primary')) }}</p>


    <h3>Url:  <span class="normal"><a href="#">{{{ $document->url }}}</a></span></h3>


    <h3>Description: <span class = "normal">{{{ $document->description }}}</span></h3>


    <h3>Input format: </h3>
    <p><pre>{{{ $document->input_format }}}</pre></p>

    <h3>Output format: </h3>
    <p><pre>{{{ $document->output_format }}}</pre></p>

    <h3>Api version: <span class="normal">{{{ $document->api_version }}}</span></h3>

    <h3>Request method: <span class="normal">{{{ $document->request_method }}}</span></h3>

    @if (Auth::user()->is_superuser)
        <div class="pos_right">
            {{ Form::open(array('style' => 'display: inline-block;', 'method' => 'DELETE', 'route' => array('admin.documents.destroy', $document->id))) }}
            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
            {{ Form::close() }}

        {{ link_to_route('admin.documents.edit', 'Edit', array($document->id), array('class' => 'btn btn-info')) }}
        </div>
    @endif



{{--<table class="table table-striped">--}}
	{{--<thead>--}}
		{{--<tr>--}}
			{{--<th>Url</th>--}}
            {{--<th>Input_format</th>--}}
            {{--<th>Output_format</th>--}}
            {{--<th>Api_version</th>--}}
            {{--<th>Description</th>--}}
            {{--<th>Request_method</th>--}}
		{{--</tr>--}}
	{{--</thead>--}}

	{{--<tbody>--}}
		{{--<tr>--}}
			{{--<td>{{{ $document->url }}}</td>--}}
					{{--<td><pre>{{{ $document->input_format }}}</pre></td>--}}
					{{--<td><pre>{{{ $document->output_format }}}</pre></td>--}}
					{{--<td>{{{ $document->api_version }}}</td>--}}
					{{--<td>{{{ $document->description }}}</td>--}}
					{{--<td>{{{ $document->request_method }}}</td>--}}
                    {{--<td>--}}
                        {{--{{ Form::open(array('style' => 'display: inline-block;', 'method' => 'DELETE', 'route' => array('admin.documents.destroy', $document->id))) }}--}}
                            {{--{{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}--}}
                        {{--{{ Form::close() }}--}}

                    {{--</td>--}}
                    {{--<td>--}}
                        {{--{{ link_to_route('admin.documents.edit', 'Edit', array($document->id), array('class' => 'btn btn-info')) }}--}}
                    {{--</td>--}}
		{{--</tr>--}}
	{{--</tbody>--}}
{{--</table>--}}

@stop
