@extends('layouts.default')
@section('title', 'edit Admin')

@section('main')

<div class="row">
    <div class="col-md-10 col-md-offset-2">
        <h1>Edit Admin User</h1>

        @if ($errors->any())
        	<div class="alert alert-danger">
        	    <ul>
                    {{ implode('', $errors->all('<li class="error">:message</li>')) }}
                </ul>
        	</div>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-md-3 col-md-offset-3">
{{ Form::model($admin_user, array('class' => 'form-horizontal', 'method' => 'PATCH', 'route' => array('admin_users.update', $admin_user->id))) }}

        <div class="form-group">
            {{ Form::label('name', 'Name:') }}
            {{ Form::text('name', Input::old('name'), array('class'=>'form-control', 'placeholder'=>'Name')) }}
        </div>
        <div class="form-group">
            {{ Form::label('email', 'Email:') }}
            {{ Form::text('email', Input::old('email'), array('class'=>'form-control', 'placeholder'=>'Email')) }}
        </div>

        <div class="form-group">
            {{ Form::label('password', 'Password:') }}
            {{ Form::password('password', array('placeholder'=>'Password', 'class'=>'form-control' ) ) }}
        </div>

        <div class="form-group">
            {{ Form::label('is_superuser', 'Superuser:') }}
            {{ Form::checkbox('is_superuser') }}
        </div>



      {{ Form::submit('Update', array('class' => 'btn btn-lg btn-primary')) }}
      {{ link_to_route('admin_users.show', 'Cancel', $admin_user->id, array('class' => 'btn btn-lg btn-default')) }}
      {{ Form::close() }}
    </div>
</div>
@stop