@extends('layouts.app')

@section('content')

<!-- Contact Area Start -->
<div id="contact-area" class="contact-area section pb-90 pt-120">
    <div class="container">
        <div class="row text-center">
            <!-- Contact Form -->
            <div class="col-sm-10 col-sm-offset-1 col-xs-12 mb-30">
                <div class="submit-form">
                    <form id="submit-form" action="{{route('rounds.update')}}" method="post">
                        @csrf
                        <h4>{{__('admin.edit_round')}}</h4>
                        <input class="hidden" name="id" value={{$id}} />
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3 col-xs-12">
                                <div class="w-100 maxwidth-200 mx-auto">
                                    <p class="player-label">{{__('admin.roundno')}}</p>
                                    <input type="text" name="roundno" @if(isset($round)) value="{{$round["roundno"]}}"@endif />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3 col-xs-12">
                                <div class="w-100 maxwidth-200 mx-auto">
                                    <p class="player-label">{{__('common.deadline')}}</p>
                                    <input type="text" name="deadline" @if(isset($round)) value="{{$round["deadline"]}}"@endif />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3 col-xs-12">
                                <div class="w-100 maxwidth-200 mx-auto">
                                    <p class="player-label">{{__('common.state')}}</p>
                                    @if(isset($round))
                                        <select name="state" required>
                                            <option value="0" @if($round["state"] == 0) selected @endif>{{__('common.not_opened')}}</option>
                                            <option value="1" @if($round["state"] == 1) selected @endif>{{__('common.active')}}</option>
                                            <option value="2" @if($round["state"] == 2) selected @endif>{{__('common.expired')}}</option>
                                        </select>
                                    @endif
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
