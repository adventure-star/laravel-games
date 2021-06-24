@extends('layouts.app')

@section('content')

<!-- Contact Area Start -->
<div id="contact-area" class="contact-area section pb-90 pt-120">
    <div class="container-fluid">
            <div class="row text-center">
                <h4>Player Points</h4>
                @if(isset($detail))
                    @foreach($detail as $key => $item)
                        @if(!!$item)
                            @if(App\Model\Point::where('roundplayerid', $item)->first())
                                <div class="col-md-6 col-sm-12 mt-10">
                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12 mb-20">
                                            {{App\Model\Player::find(App\Model\RoundPlayer::find($item)->playerid)->team}}, {{App\Model\Player::find(App\Model\RoundPlayer::find($item)->playerid)->name}}, No {{App\Model\RoundPlayer::find($item)->no}}
                                        </div>
                                        <div class="col-sm-12 col-xs-12 mb-30 text-bold">
                                            Point : {{App\Model\Point::where('roundplayerid', $item)->first()->total}} Points
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endforeach
                @endif
            </div>
            <div class="row text-center">
                <h4>Question Points</h4>
                @if(isset($answers))
                    @foreach($answers as $key => $answer)
                        <div class="col-md-6 col-sm-12 mt-20 mb-10">
                            @if(!!$answer)
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12 mb-10">
                                        Question: {{App\Model\Question::find(App\Model\QInput::find($answer["qinput"])->qid)->text }}
                                    </div>
                                    <div class="col-sm-12 col-xs-12 mb-10 text-bold">
                                        {{App\Model\QInput::find($answer["qinput"])->input}} : {{App\Model\QInput::find($answer["qinput"])->point}}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        {{-- </div> --}}
    </div>
</div>
<!-- Contact Area End -->

@endsection
