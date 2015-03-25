@extends('layouts.default')
@section('title', 'Admin')

@section('main')

<h1>All Admin Users</h1>

@if ($admin_users->count())
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Name</th>
				<th>Email</th>
				<th>Superuser</th>
				<th>&nbsp;</th>
			</tr>
		</thead>

		<tbody>
			@foreach ($admin_users as $admin_user)
				<tr>
					<td>{{{ $admin_user->name }}}</td>
					<td>{{{ $admin_user->email }}}</td>
					<td>{{{ $admin_user->map_superuser() }}}</td>

                    @if (Auth::user()->is_superuser)
                        <td>

                            {{ Form::open(array('style' => 'display: inline-block;', 'method' => 'DELETE', 'route' => array('admin_users.destroy', $admin_user->id), 'onsubmit' => 'return ConfirmDelete()')) }}
                                {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                            {{ Form::close() }}

                            {{ link_to_route('admin_users.edit', 'Edit', array($admin_user->id), array('class' => 'btn btn-info')) }}
                        </td>
                    @endif
				</tr>
			@endforeach
		</tbody>
	</table>

    @if (Auth::user()->is_superuser)
        <p>{{ link_to_route('admin_users.create', 'Add Admin', null, array('class' => 'btn btn-lg btn-success pos_right')) }}</p>
    @endif
@else
	There are no admin_users
@endif

@stop
