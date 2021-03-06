@extends('layouts.app')

@section('content')

<!-- Contact Area Start -->
<div id="contact-area" class="contact-area section pb-90 pt-120">
    <div class="container">
        <div class="row text-center">
            <!-- Contact Form -->
            <div class="col-sm-10 col-sm-offset-1 col-xs-12 mb-30">
                <h3>{{__('admin.add_player_to_round')}}</h3>

                <div class="submit-form">
                    <form id="submit-form" action="{{route('players.new.save')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <div class="w-100 maxwidth-200 mx-auto">
                                    <h4>{{__('common.game')}}</h4>
                                    <select name="gameid" onchange="getResultsByGameId(this)" required>
                                        <option disabled selected>{{__('common.select_game')}}!</option>
                                        @if(isset($games) && count($games) > 0)
                                            @foreach($games as $key=>$item)
                                                <option value={{$item['id']}} @if(isset($game) && $game == $item['id']) selected @endif @if(old('game') == $item['id'])selected @endif>{{$item['name']}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        @if(isset($game))
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="w-100 maxwidth-200 mx-auto">
                                        <h4>{{__('common.select_round')}}</h4>
                                        <select class="normal-component maxwidth-200" name="roundid" onchange="getResultsByRoundId(this)">
                                            <option disabled selected>{{__('common.select_round')}}!</option>
                                            @if(isset($rounds) && count($rounds) > 0)
                                                @foreach($rounds as $key => $item)
                                                    <option value={{$item['id']}} @if(isset($round) && $round == $item['id']) selected @endif>{{$item['roundno']}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="w-100 maxwidth-200 mx-auto">
                                        <h4>{{__('common.select_player')}}</h4>
                                        <select class="normal-component maxwidth-200" name="playerid" required>
                                            <option disabled selected>{{__('common.select_player')}}!</option>
                                            @if(isset($players) && count($players) > 0)
                                                @foreach($players as $key => $item)
                                                    <option value={{$item['id']}} @if(isset($round) && $round == $item['id']) selected @endif>{{$item['team']}}({{$item['name']}})</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div class="w-100 maxwidth-200 mx-auto">
                                        <p class="player-label">{{__('common.category')}}</p>
                                        <input type="text" name="cat" class="titleinput" value="{{old('cat')}}" required />
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="w-100 maxwidth-200 mx-auto">
                                        <p class="player-label">{{__('common.no_number')}}</p>
                                        <input type="text" name="no" value="{{old('no')}}" required />
                                    </div>
                                </div>
                            </div>
                            <h5 class="mt-20">{{__('common.additional_inputs')}}</h5>
                            <hr />
                            <div id="newinputs" class="row">
                            </div>
                            <div class="row">
                                <input type="button" onclick="addInput()" value="Add New Input" />
                            </div>
                            <hr />
                            <input type="submit" value="Submit">
                        @endif
                    </form>
                    <div class="mt-40">
                        <h4>{{__('admin.import_player_csv')}}</h4>
                        <form id="playeruploadform" action="{{route('uploads.roundplayer')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="w-100 maxwidth-200 mx-auto">
                                        <p class="player-label">{{__('common.game')}}</p>
                                        <select name="gameid" required>
                                            <option disabled selected>{{__('common.select_game')}}!</option>
                                            @if(isset($games) && count($games) > 0)
                                                @foreach($games as $key=>$item)
                                                    <option value={{$item['id']}} @if(isset($game) && $game == $item['id']) selected @endif @if(old('game') == $item['id'])selected @endif>{{$item['name']}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <label class="custom-file-upload">
                                <input onchange="upload()" class="hidden" type="file" name="file" />
                                {{__('common.import')}}
                            </label>
                        </form>
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
        function upload() {
            document.getElementById("playeruploadform").submit();
        }
        function addInput() {

            $("#newinputs").append('<div class="col-md-5 col-xs-12"><div class="w-100 maxwidth-200 mx-auto">'+
                                    '<p class="player-label">Title</p><input type="text" onkeyup="changeName(this)" required />'+
                                '</div></div>'+
                                '<div class="col-md-5 col-xs-12"><div class="w-100 maxwidth-200 mx-auto">'+
                                    '<p class="player-label">{{__("common.content")}}</p><input type="text" required />'+
                                '</div></div>'+
                                '<div class="col-md-2 col-xs-12"><div class="w-100 mx-auto d-flex align-items-center">'+
                                    '<button type="button w-100" class="close" onclick="removeItem(this)"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'+
                                '</div></div>');

        }
        function changeName(obj) {
            $(obj).parent().parent().next().find("input").attr("name", obj.value)
        }
        function removeItem(obj) {
            $(obj).parent().parent().prev().prev().remove();
            $(obj).parent().parent().prev().remove();
            $(obj).parent().parent().remove();
        }
        function getResultsByGameId(e) {
            window.location.href = "{{route('players.new')}}?game=" + e.value;
        }
        function getResultsByRoundId(e) {
            window.location.href = "{{route('players.new')}}?round=" + e.value + "&" + "game={{$game}}";
        }
    
    </script>
@endsection
