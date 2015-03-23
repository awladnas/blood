@extends('layouts.scaffold')

@section('main')

<h1>All Admin_users</h1>

<p>{{ link_to_route('admin_users.create', 'Add New Admin_user', null, array('class' => 'btn btn-lg btn-success')) }}</p>

@if ($admin_users->count())
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Name</th>
				<th>Email</th>
				<th>Password</th>
				<th>Is_superuser</th>
				<th>&nbsp;</th>
			</tr>
		</thead>

		<tbody>
			@foreach ($admin_users as $admin_user)
				<tr>
					<td>{{{ $admin_user->name }}}</td>
					<td>{{{ $admin_user->email }}}</td>
					<td>{{{ $admin_user->password }}}</td>
					<td>{{{ $admin_user->is_superuser }}}</td>
                    <td>
                        {{ Form::open(array('style' => 'display: inline-block;', 'method' => 'DELETE', 'route' => array('admin_users.destroy', $admin_user->id))) }}
                            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                        {{ link_to_route('admin_users.edit', 'Edit', array($admin_user->id), array('class' => 'btn btn-info')) }}
                    </td>
				</tr>
			@endforeach
		</tbody>
	</table>
@else
	There are no admin_users
@endif

@stop