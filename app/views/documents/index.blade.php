@extends('layouts.scaffold')

@section('main')

<h1>All Documents</h1>

<p>{{ link_to_route('admin.documents.create', 'Add New Document', null, array('class' => 'btn btn-lg btn-success')) }}</p>

@if ($documents->count())
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Url</th>
				<th>Input_format</th>
				<th>Output_format</th>
				<th>Api_version</th>
				<th>Description</th>
				<th>Request_method</th>
				<th>&nbsp;</th>
			</tr>
		</thead>

		<tbody>
			@foreach ($documents as $document)
				<tr>
					<td>{{{ $document->url }}}</td>
					<td>{{{ $document->input_format }}}</td>
					<td>{{{ $document->output_format }}}</td>
					<td>{{{ $document->api_version }}}</td>
					<td>{{{ $document->description }}}</td>
					<td>{{{ $document->request_method }}}</td>
                    <td>
                        {{ Form::open(array('style' => 'display: inline-block;', 'method' => 'DELETE', 'route' => array('admin.documents.destroy', $document->id))) }}
                            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </td>
                    <td>
                        {{ link_to_route('admin.documents.edit', 'Edit', array($document->id), array('class' => 'btn btn-info')) }}
                    </td>

                    <td>
                        {{ link_to_route('admin.documents.show', 'Show', array($document->id), array('class' => 'btn btn-info')) }}
                    </td>
				</tr>
			@endforeach
		</tbody>
	</table>
@else
	There are no documents
@endif

@stop
