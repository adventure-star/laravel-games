@extends('layouts.app')

@section('content')

<!-- Profile Area Start -->
<div id="fixtures-area" class="fixtures-area section pb-120 pt-120">
    <div class="container">
        @if($user)
            <div class="col-md-10 col-md-offset-1 col-xs-12 text-center">
                <!-- Fixtures Table -->
                <div class="table-responsive fixtures-table">
                    <table class="table">
                        <tr>
                            <td>{{__('common.fullname')}}</td>
                            <td>{{$user['fullname']}}</td>
                        </tr>
                        <tr>
                            <td>{{__('common.username')}}</td>
                            <td>{{$user['username']}}</td>
                        </tr>
                        <tr>
                            <td>{{__('common.email')}}</td>
                            <td>{{$user['email']}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
<!-- Profile Area End -->

@endsection