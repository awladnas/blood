@extends('layouts.scaffold')

@section('main')

<div class="row">
    <div class="col-md-10 col-md-offset-2">
        <h1>Create Document</h1>

        @if ($errors->any())
        	<div class="alert alert-danger">
        	    <ul>
                    {{ implode('', $errors->all('<li class="error">:message</li>')) }}
                </ul>
        	</div>
        @endif
    </div>
</div>

{{ Form::open(array('route' => 'admin.documents.store', 'class' => 'form-horizontal', 'method' => 'post')) }}

        <div class="form-group">
            {{ Form::label('url', 'Url:', array('class'=>'col-md-2 control-label')) }}
            <div class="col-sm-10">
              {{ Form::text('url', Input::old('url'), array('class'=>'form-control', 'placeholder'=>'Url')) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('input format', 'Input format:', array('class'=>'col-md-2 control-label')) }}
            <div class="col-sm-10">
              {{ Form::textarea('input_format', Input::old('input_format'), array('class'=>'form-control', 'placeholder'=>'Input format')) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('output_format', 'Output format:', array('class'=>'col-md-2 control-label')) }}
            <div class="col-sm-10">
              {{ Form::textarea('output_format', Input::old('output_format'), array('class'=>'form-control', 'placeholder'=>'Output format')) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('api_version', 'Api version:', array('class'=>'col-md-2 control-label')) }}
            <div class="col-sm-10">
              {{ Form::text('api_version', Input::old('api_version'), array('class'=>'form-control', 'placeholder'=>'Api version')) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('description', 'Description:', array('class'=>'col-md-2 control-label')) }}
            <div class="col-sm-10">
                {{ Form::text('description', Input::old('description'), array('class'=>'form-control', 'placeholder'=>'Description')) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('request_method', 'Request method:', array('class'=>'col-md-2 control-label')) }}
            <div class="col-sm-10">
                {{ Form::select('request_method', array('GET' => 'GET', 'POST' => 'POST'))  }}
            </div>
        </div>


<div class="form-group">
    <label class="col-sm-2 control-label">&nbsp;</label>
    <div class="col-sm-10">
      {{ Form::submit('Create', array('class' => 'btn btn-lg btn-primary')) }}
    </div>
</div>

{{ Form::close() }}

@stop


