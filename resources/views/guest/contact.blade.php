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
                    <p><span>address: </span> 121 King Street, Melbourne Victoria 3000 Australia</p>
                    <p><span>phone: </span> +61 3 8376 6284</p>
                    <p><span>email: </span> <a href="#">contact@sofaleague.com</a></p>
                    <p><span>skype: </span> <a href="#">suparsport333</a></p>
                    <p><span>facebook: </span> <a href="#">www.facebook.com/suparsport</a></p>
                    <p><span>twitter: </span> <a href="#">www.twitter.com/suparsport</a></p>
                    <p><span>website: </span> <a href="#">sofaleague.com</a></p>
                </div>
            </div>
            <!-- Contact Form -->
            <div class="col-md-8 col-sm-7 col-xs-12 mb-30">
                <div class="contact-form">
                    <h4>Get In Touch</h4>
                    <form id="contact-form" action="{{route('contact.send')}}" method="post">
                        @csrf
                        <input type="text" name="name" value="{{old('name')}}" placeholder="Name">
                        <input type="email" name="email" value="{{old('email')}}" placeholder="Email">
                        <input type="text" name="phone" value="{{old('phone')}}" name="name" placeholder="Phone">
                        <input type="text" name="subject" value="{{old('subject')}}" placeholder="Subject">
                        <textarea name="message" value="{{old('message')}}" placeholder="Message"></textarea>
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