@extends('layouts.scaffold')

@section('main')

<div class="row">
    <div class="col-md-10 col-md-offset-2">
        <h1>Edit Document</h1>

        @if ($errors->any())
        	<div class="alert alert-danger">
        	    <ul>
                    {{ implode('', $errors->all('<li class="error">:message</li>')) }}
                </ul>
        	</div>
        @endif
    </div>
</div>

{{ Form::model($document, array('class' => 'form-horizontal', 'method' => 'PATCH', 'route' => array('admin.documents.update', $document->id))) }}

        <div class="form-group">
            {{ Form::label('url', 'Url:', array('class'=>'col-md-2 control-label')) }}
            <div class="col-sm-10">
              {{ Form::text('url', Input::old('url'), array('class'=>'form-control', 'placeholder'=>'Url')) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('input_format', 'Input_format:', array('class'=>'col-md-2 control-label')) }}
            <div class="col-sm-10">
              {{ Form::textarea('input_format', Input::old('input_format'), array('class'=>'form-control', 'placeholder'=>'Input_format')) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('output_format', 'Output_format:', array('class'=>'col-md-2 control-label')) }}
            <div class="col-sm-10">
              {{ Form::textarea('output_format', Input::old('output_format'), array('class'=>'form-control', 'placeholder'=>'Output_format')) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('api_version', 'Api_version:', array('class'=>'col-md-2 control-label')) }}
            <div class="col-sm-10">
              {{ Form::text('api_version', Input::old('api_version'), array('class'=>'form-control', 'placeholder'=>'Api_version')) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('description', 'Description:', array('class'=>'col-md-2 control-label')) }}
            <div class="col-sm-10">
                {{ Form::text('description', Input::old('description'), array('class'=>'form-control', 'placeholder'=>'Description')) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('request_method', 'Request_method:', array('class'=>'col-md-2 control-label')) }}
            <div class="col-sm-10">
                {{ Form::select('request_method', array('GET' => 'GET', 'POST' => 'POST'), Input::old('request_method')) }}
            </div>
        </div>


<div class="form-group">
    <label class="col-sm-2 control-label">&nbsp;</label>
    <div class="col-sm-10">
      {{ Form::submit('Update', array('class' => 'btn btn-lg btn-primary')) }}
      {{ link_to_route('admin.documents.show', 'Cancel', $document->id, array('class' => 'btn btn-lg btn-default')) }}
    </div>
</div>

{{ Form::close() }}

@stop