@extends('layouts.app')

@section('content')

<!-- Contact Area Start -->
<div id="contact-area" class="contact-area section pb-90 pt-120">
    <div class="container">
        <div class="row text-center">
            <!-- Contact Form -->
            <div class="col-sm-10 col-sm-offset-1 col-xs-12 mb-30">
                <div class="submit-form">
                    <form id="submit-form" action="{{route('questions.update')}}" method="post">
                        @csrf
                        <h4>{{__('admin.edit_question')}}</h4>
                        <input id="questionid" class="hidden" name="id" value={{$id}} />
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3 col-xs-12">
                                <div class="w-100 maxwidth-200 mx-auto">
                                    <p class="player-label">{{__('common.number')}}</p>
                                    <input type="number" name="number" @if(isset($question)) value="{{$question["number"]}}" @endif />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <div class="w-100 mx-auto">
                                    <p class="player-label">{{__('common.text')}}</p>
                                    <input type="text" name="text" @if(isset($question)) value="{{$question["text"]}}" @endif />
                                </div>
                            </div>
                        </div>
                        <input type="submit" value="Save">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Contact Area End -->

@endsection
