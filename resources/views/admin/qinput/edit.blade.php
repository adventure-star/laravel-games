@extends('layouts.app')

@section('content')

<!-- Contact Area Start -->
<div id="contact-area" class="contact-area section pb-90 pt-120">
    <div class="container">
        <div class="row text-center">
            <!-- Contact Form -->
            <div class="col-sm-10 col-sm-offset-1 col-xs-12 mb-30">
                <div class="submit-form">
                    <form id="submit-form" action="{{route('qinputs.update')}}" method="post">
                        @csrf
                        <h4>{{__('admin.edit_answer')}}</h4>
                        <input class="hidden" name="id" value={{$id}} />
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <div class="w-100 maxwidth-200 mx-auto">
                                    <p class="player-label">{{__('common.content')}}</p>
                                    <input type="text" name="input" @if(isset($qinput)) value="{{$qinput["input"]}}"@endif />
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="w-100 maxwidth-200 mx-auto">
                                    <p class="player-label">{{__('common.point')}}</p>
                                    <input type="number" name="point" @if(isset($qinput)) value="{{$qinput["point"]}}"@endif />
                                </div>
                            </div>
                        </div>
                        <input type="submit" value="Submit">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Contact Area End -->

@endsection
