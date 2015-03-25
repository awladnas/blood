@extends('layouts.default')
@section('title', 'Api Documents')

@section('main')

<h1>All Documents</h1>

@if (Auth::user()->is_superuser)
    <p>{{ link_to_route('admin.documents.create', 'New Document', null, array('class' => 'btn btn-lg btn-success pos_right')) }}</p>
@endif

@if ($documents->count())
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Url</th>
				<th>Description</th>
				<th>Request_method</th>
				<th>&nbsp;</th>
			</tr>
		</thead>

		<tbody>
			@foreach ($documents as $document)
				<tr>
					<td>{{link_to_route('admin.documents.show', $document->url , array($document->id))}}</td>
					<td>{{{ $document->description }}}</td>
					<td>{{{ $document->request_method }}}</td>
                    <td />
                    <td />
                    <td>
                        @if (Auth::user()->is_superuser)
                            {{ Form::open(array('style' => 'display: inline-block;', 'method' => 'DELETE', 'route' => array('admin.documents.destroy', $document->id), 'onsubmit' => 'return ConfirmDelete()')) }}
                                {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                            {{ Form::close() }}
                            {{ link_to_route('admin.documents.edit', 'Edit', array($document->id), array('class' => 'btn btn-info')) }}
                        @endif

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
