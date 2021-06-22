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
    <div class="container-fluid">
        <div class="row text-center">
            <h3>Game: {{App\Model\Game::find($id)->name}}</h3>
            @if(Auth::user())
                <h4>Your Team: {{App\Model\GameUser::where(['userid' => Auth::user()->id, 'gameid' => $id])->first()->teamname}}</h4>
            @endif
            <div class="col-md-7 col-sm-12">
                <div class="row">
      <!-- Contact Form -->
                    <div class="col-sm-10 col-sm-offset-1 col-xs-12 mb-30">
                        <div class="submit-form">
                            <form id="submit-form" action="{{route('submitsave')}}" method="post">
                                @csrf
                                <input type="text" class="hidden" name="gameid" value="{{$id}}" />
                                <div class="row">
                                    <h4>Round</h4>
                                    <div class="w-100 maxwidth-400 mx-auto px-14">
                                        <select name="roundid" onchange="getResultsByRoundId(this)" required>
                                            <option disabled selected>Select Round</option>
                                            @if(isset($rounds) && count($rounds) > 0)
                                                @foreach($rounds as $item)
                                                    <option value={{$item['id']}} @if(isset($round) && $round == $item['id']) selected @endif >{{$item['roundno']}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                @if($round)
                                    <h4>Team</h4>
                                    @if(isset($details))
                                        @foreach($details as $key => $detail)
                                            @for($i = 0 ; $i < number_format($detail) ; $i ++)
                                                <div class="row">
                                                    <div class="col-md-6 col-md-offset-3 col-xs-12">
                                                        <div class="w-100 maxwidth-200 mx-auto">
                                                            <p class="player-label">{{ucfirst($category)}} : {{$key}}</p>
                                                            <select name="{{$key}}{{$i + 1}}" required>
                                                                <option disabled selected>Select Player</option>
                                                                @if(isset($players) && count($players) > 0)
                                                                    @foreach($players as $key1 => $player)
                                                                        @if($player->$category == $key)
                                                                            <option value="{{$player['id']}}" @if(isset($olddetail)) @if(isset($olddetail[$key.($i + 1)])) @if( $olddetail[$key.($i + 1)] == $player['id']) selected @endif @endif @endif>{{App\Model\RealTeam::where('longname', $player['team'])->first() ? App\Model\RealTeam::where('longname', $player['team'])->first()->shortname : $player['team'] }}, {{$player['name']}} ({{$player['no']}})</option>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endfor
                                        @endforeach
                                    @endif
                                    <h4>Question</h4>
                                    <div id="questionarea">
                                        @if(isset($questions) && count($questions) > 0)
                                            @foreach($questions as $key => $question)
                                                <div class='row'>
                                                    <div class='col-sm-6 col-xs-12 text-right-left pb-0-20 pl-0-vw'>
                                                        <p class='player-label mt-10 mb-0-10'>
                                                            {{$question['number']}})  {{$question['text']}}
                                                        </p>
                                                    </div>
                                                    <div class='col-sm-6 col-xs-12'>
                                                        <div class='w-100 maxwidth-250 mx-auto'>
                                                            <select name='question_{{$question['id']}}'>
                                                                <option disabled selected>Select Answer</option>
                                                                @if(isset($question->qinputs))
                                                                    @foreach($question->qinputs as $key=>$qinput)
                                                                        <option value="{{$qinput['id']}}" @if(Auth::user()) @if(App\Model\Answer::where(['userid' => Auth::user()->id, 'gameid' => $id, 'roundid' => $round, 'questionid' => $question['id'], 'qinputid' => $qinput['id']])->first()) selected @endif @endif>{{$qinput['input']}}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    @if(Auth::user())
                                        <input type="submit" value="Submit">
                                    @else
                                        <h5>You have to Login to submit team</h5>
                                    @endif
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5 col-sm-12">
                <div class="row">
                    
                    <div class="col-md-10 col-md-offset-1 col-xs-12 text-center">
                        <!-- Fixtures Table -->
                        <h4>Teams</h4>
                        <div class="table-responsive fixtures-table maxwidth-700 mx-auto mt-25">
                            <table id="fixturetable" class="table">
                                <tr>
                                    <th>LongName</th>
                                    <th>ShortName</th>
                                </tr>
                                @if(isset($teams) && count($teams) > 0)
                                    @foreach($teams as $key => $item)
                                        <tr>
                                            <td>{{$item["longname"]}}</td>
                                            <td>{{$item["shortname"]}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Contact Area End -->

@endsection

@section('scripts')

<script>
    function getResultsByRoundId(e) {
        window.location.href = "{{route('submit', $id)}}?round=" + e.value;
    }
</script>
    
@endsection