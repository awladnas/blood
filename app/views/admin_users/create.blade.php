@extends('layouts.default')
@section('title', 'Admin')

@section('main')

<div class="row">
    <div class="col-md-10 col-md-offset-2">
        <h1>Create New Admin</h1>

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
        {{ Form::open(array('route' => 'admin_users.store', 'class' => 'form-horizontal')) }}
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
        {{ Form::submit('Create', array('class' => 'btn btn-lg btn-primary')) }}
        {{ Form::close() }}

    </div>
</div>
@stop


