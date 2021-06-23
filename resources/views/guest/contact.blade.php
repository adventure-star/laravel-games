@extends('layouts.app')

@section('content')

<!-- Page Banner Area Start -->
<div id="page-banner-area" class="page-banner-area section">
    <div class="container">
    </div>
</div>
<!-- Page Banner Area End -->

<!-- Contact Area Start -->
<div id="contact-area" class="contact-area section pb-90 pt-120">
    <div class="container">
        <div class="row">
            <!-- Contact Info -->
            <div class="col-md-4 col-sm-5 col-xs-12 mb-30">
                <div class="contact-info">
                    <h4>Contact details</h4>
                    <p><span>email: </span> <a href="#">contact@sofaleague.com</a></p>
                    <p><span>website: </span> <a href="#">sofaleague.com</a></p>
                </div>
            </div>
            <!-- Contact Form -->
            <div class="col-md-8 col-sm-7 col-xs-12 mb-30">
                <div class="contact-form">
                    <h4>Get In Touch</h4>
                    <form id="contact-form" action="{{route('contact.send')}}" method="post">
                        @csrf
                        <input type="text" name="name" value="{{old('name')}}" placeholder="{{__('common.name')}}">
                        <input type="email" name="email" value="{{old('email')}}" placeholder="{{__('common.email')}}">
                        <input type="text" name="subject" value="{{old('subject')}}" placeholder="{{__('common.subject')}}">
                        <textarea name="message" value="{{old('message')}}" placeholder="{{__('common.message')}}"></textarea>
                        <input type="submit" value="send">
                    </form>
                </div>
            </div>
            @error('error')
                {{-- {{print_r($message)}} --}}
            @enderror
        </div>
    </div>
</div>
<!-- Contact Area End -->

@endsection