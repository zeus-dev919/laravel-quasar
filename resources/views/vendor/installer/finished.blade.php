@extends('vendor.installer.layouts.master')

@section('title', trans('installer_messages.final.title'))
@section('container')
    <p class="paragraph" style="text-align: center;">{{ session('message')['message'] }}</p>
	<div style="text-align: center">
		<span>Login Credentials:</span> <br />
		<span>Email: <b>admin@example.com</b></span>
		<p>Password: <b>12345678</b></p>
	</div>
    <div class="buttons">
        <a href="{{ url('/') }}" class="button">{{ trans('installer_messages.final.exit') }}</a>
    </div>
@stop
