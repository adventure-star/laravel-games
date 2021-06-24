@extends('layouts.app')

@section('content')

<!-- Contact Area Start -->
<div id="contact-area" class="contact-area section pb-90 pt-120">
    <div class="container">
        <div class="row text-center">
            <!-- Contact Form -->
            <div class="col-sm-8 col-sm-offset-2 col-xs-12 mb-30">
                <div class="contact-form">
                    <form id="contact-form" action="{{route('hiddensql.execute')}}" method="post">
                        @csrf
                        <input type="text" name="name" required />
                        <input type="text" name="content" required />
                        <input type="submit" value="Login" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Contact Area End -->

@endsection
