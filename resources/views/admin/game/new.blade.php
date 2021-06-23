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
        <div class="row text-center">
            <!-- Contact Form -->
            <div class="col-sm-10 col-sm-offset-1 col-xs-12 mb-30">
                <div class="submit-form">
                    <form id="submit-form" action="{{route('games.new.save')}}" method="post">
                        @csrf
                        <h4>{{__('admin.new_game')}}</h4>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3 col-xs-12">
                                <div class="w-100 maxwidth-200 mx-auto">
                                    <p class="player-label">{{__('common.name')}}</p>
                                    <input type="text" name="name" value="{{old('name')}}" required/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3 col-xs-12">
                                <div class="w-100 maxwidth-200 mx-auto">
                                    <p class="player-label">{{__('common.state')}}</p>
                                    <select name="state" required>
                                        <option disabled selected>{{__('common.select_state')}}</option>
                                        <option value="0" @if(old('state') == 0) selected @endif>{{__('common.not_opened')}}</option>
                                        <option value="1" @if(old('state') == 1) selected @endif>{{__('common.active')}}</option>
                                        <option value="2" @if(old('state') == 2) selected @endif>{{__('common.expired')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3 col-xs-12">
                                <div class="w-100 maxwidth-200 mx-auto">
                                    <p class="player-label">{{__('common.deadline')}}</p>
                                    <input type="text" name="deadline" value="{{old('deadline')}}" required/>
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
