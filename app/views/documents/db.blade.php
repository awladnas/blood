
@extends('layouts.default')
@section('title', 'Database Structure')

@section('main')

<h1>Tables</h1>
<p> <a href="#admin_user" >admin user</a>, <a href="#user" >users</a>, <a href="#block_user" >block user</a>, <a href="#documents" >documents</a>, <a href="#request" >request</a>  </p>

<h1 id="admin_user"> Admin User</h1>
<table class="table">
    <thead>
    <th>field name</th>
    <th>type</th>
    <th>function</th>
    </thead>
    <tbody>
    <tr>
        <td>name</td>
        <td>string</td>
        <td colspan="3">user name</td>
    </tr>
    <tr>
        <td>email</td>
        <td>string</td>
        <td colspan="3">user email address</td>
    </tr>
    <tr>
        <td>password</td>
        <td>string</td>
        <td colspan="3">user password</td>
    </tr>
    <tr>
        <td>role</td>
        <td>string</td>
        <td colspan="3">user role (admin/super admin/developer)</td>
    </tr>
    </tbody>
</table>

<h1 id="user"> User</h1>
<table class="table">
    <thead>
    <th>field name</th>
    <th>type</th>
    <th>function</th>
    </thead>
    <tbody>
    <tr>
        <td>Mobile_no</td>
        <td>string</td>
        <td colspan="3">user's mobile number</td>
    </tr>
    <tr>
        <td>email</td>
        <td>string</td>
        <td colspan="3">user email address</td>
    </tr>
    <tr>
        <td>password</td>
        <td>string</td>
        <td colspan="3">user password</td>
    </tr>
    <tr>
        <td>is_active</td>
        <td>boolean</td>
        <td colspan="3">user active(1) or in active(0)</td>
    </tr>
    <tr>
        <td>is_confirm</td>
        <td>boolean</td>
        <td colspan="3">user confirm(1) or not confirm(0)</td>
    </tr>
    <tr>
        <td>api_token</td>
        <td>string</td>
        <td colspan="3">user's api token</td>
    </tr>
    <tr>
        <td>valid_until</td>
        <td>string</td>
        <td colspan="3">user's api token valid date</td>
    </tr>
    <tr>
        <td>out_of_req</td>
        <td>boolean</td>
        <td colspan="3">user is available for request (1) or not (0)</td>
    </tr>
    <tr>
        <td>device_id</td>
        <td>string</td>
        <td colspan="3">user's device id for sending notification</td>
    </tr>
    <tr>
        <td>is_block</td>
        <td>boolean</td>
        <td colspan="3">user block(1) or not block(0)</td>
    </tr>
    <tr>
        <td>confirmation_code</td>
        <td>string</td>
        <td colspan="3">user's account confirmation</td>
    </tr>
    <tr>
        <td>blood_group</td>
        <td>string</td>
        <td colspan="3">user's blood group</td>
    </tr>
    <tr>
        <td>name</td>
        <td>string</td>
        <td colspan="3">user's name</td>
    </tr>
    <tr>
        <td>zone</td>
        <td>string</td>
        <td colspan="3">user's zone</td>
    </tr>
    <tr>
        <td>city</td>
        <td>string</td>
        <td colspan="3">user's city</td>
    </tr>
    <tr>
        <td>country</td>
        <td>string</td>
        <td colspan="3">user's country</td>
    </tr>
    <tr>
        <td>latitude</td>
        <td>string</td>
        <td colspan="3">user's current location latitude</td>
    </tr>
    <tr>
        <td>longitude</td>
        <td>string</td>
        <td colspan="3">user's location longitude</td>
    </tr>
    <tr>
        <td>steps</td>
        <td>integer</td>
        <td colspan="3">user's profile current step</td>
    </tr>
    <tr>
        <td>is_complete</td>
        <td>boolean</td>
        <td colspan="3">user profile is complete or not</td>
    </tr>

    </tbody>
</table>

<h1 id="block_user"> Block Users</h1>
<table class="table">
    <thead>
    <th>field name</th>
    <th>type</th>
    <th>function</th>
    </thead>
    <tbody>
    <tr>
        <td>name</td>
        <td>string</td>
        <td colspan="3">user name</td>
    </tr>
    <tr>
        <td>blocked_by</td>
        <td>integer</td>
        <td colspan="3">user id who blocked user </td>
    </tr>
    <tr>
        <td>blocked_user</td>
        <td>integer</td>
        <td colspan="3">user id who have been blocked by blocked_by</td>
    </tr>
    </tbody>
</table>

<h1 id="documents">Documents</h1>
<table class="table">
    <thead>
    <th>field name</th>
    <th>type</th>
    <th>function</th>
    </thead>
    <tbody>
    <tr>
        <td>url</td>
        <td>string</td>
        <td colspan="3">url for the request</td>
    </tr>
    <tr>
        <td>input_format</td>
        <td>text</td>
        <td colspan="3">Input structure</td>
    </tr>
    <tr>
        <td>output</td>
        <td>text</td>
        <td colspan="3">Output format</td>
    </tr>
    <tr>
        <td>api_version</td>
        <td>string</td>
        <td colspan="3">version of the called API</td>
    </tr>
    <tr>
        <td>request_method</td>
        <td>string</td>
        <td colspan="3">Method for this API call(GET|POST|PUT|DELETE)</td>
    </tr>
    <tr>
        <td>description</td>
        <td>text</td>
        <td colspan="3">Description for this API call</td>
    </tr>
    </tbody>
</table>


<h1 id="request">Requests</h1>
<table class="table">
    <thead>
    <th>field name</th>
    <th>type</th>
    <th>function</th>
    </thead>
    <tbody>
    <tr>
        <td>user_id</td>
        <td>integer</td>
        <td colspan="3">user who created this request</td>
    </tr>
    <tr>
        <td>area</td>
        <td>string</td>
        <td colspan="3">area of the user where user has made request</td>
    </tr>
    <tr>
        <td>content</td>
        <td>text</td>
        <td colspan="3">user message</td>
    </tr>
    <tr>
        <td>blood_group</td>
        <td>string</td>
        <td colspan="3">requested blood group</td>
    </tr>
    <tr>
        <td>request_type</td>
        <td>string</td>
        <td colspan="3">type of the request(OFFER|REQUEST)</td>
    </tr>
    <tr>
        <td>status</td>
        <td>boolean</td>
        <td colspan="3">Status of the request(ACTIVE(0) | INACTIVE(1))</td>
    </tr>
    </tbody>
</table>
@stop