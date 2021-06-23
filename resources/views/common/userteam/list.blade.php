@extends('layouts.app')

@section('content')

<!-- Page Banner Area Start -->
<div id="page-banner-area" class="page-banner-area section">
    <div class="container">
    </div>
</div>
<!-- Page Banner Area End -->

<!-- Fixtures Area Start -->
<div id="fixtures-area" class="fixtures-area section pb-120 pt-120">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12 text-center">

                @if(Auth::user()->isadmin == 1)

                    <div class="row">
                        <div class="mt-0 mb-20">
                            <h4>{{__('common.select_game')}}</h4>
                            <select class="normal-component maxwidth-200" onchange="getResultsByGameId(this)">
                                <option disabled selected>{{__('common.select_game')}}</option>
                                @if(isset($games) && count($games) > 0)
                                    @foreach($games as $key => $item)
                                        <option value={{$item['gameid']}} @if(isset($game) && $game == $item['gameid']) selected @endif>{{App\Model\Game::find($item['gameid'])->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                @endif

                @if($game)

                    @if(Auth::user()->isadmin == 1)

                        <div class="row">
                            <div class="mt-0 mb-20">
                                <h4>{{__('common.select_round')}}</h4>
                                <select class="normal-component maxwidth-200" onchange="getResultsByRoundId(this)">
                                    <option value="all">{{__('common.total')}}</option>
                                    @if(isset($rounds) && count($rounds) > 0)
                                        @foreach($rounds as $key => $item)
                                            <option value={{$item['id']}} @if(isset($round) && $round == $item['id']) selected @endif>{{$item['roundno']}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                    @endif

                    <!-- Fixtures Table -->
                    <div class="table-responsive fixtures-table">
                        <table class="table">
                            <tr>
                                <th>{{__('common.team')}}</th>
                                <th>{{__('common.round')}}</th>
                                <th>{{__('common.detail')}}</th>
                                @if(Auth::user()->isadmin == 1)
                                    <th>{{__('common.remove')}}</th>
                                @endif
                            </tr>
                            @if(isset($teams) && count($teams) > 0)
                                @foreach($teams as $key => $item)
                                    <tr>
                                        @if(Auth::user()->isadmin == 1)
                                            <td>{{App\Model\GameUser::where(['userid' => $item["userid"], 'gameid' => $item['gameid']])->first()->teamname}}</td>
                                        @else
                                            @if($key == 0)
                                                <td rowspan="{{count(App\Model\Team::where(['userid' => Auth::user()->id, 'gameid' => $item['gameid']])->get())}}">{{App\Model\GameUser::where(['userid' => $item["userid"], 'gameid' => $item['gameid']])->first()->teamname}}</td>
                                            @endif
                                        @endif
                                        <td>{{App\Model\Round::find($item["roundid"])["roundno"]}}</td>
                                        <td class="maxwidth-200">
                                            @if(isset($item->detail))
                                                @foreach(json_decode($item->detail) as $key=>$value)
                                                    {{$key}} : {{App\Model\Player::find(App\Model\RoundPlayer::find($value)['playerid'])->name}}
                                                @endforeach
                                            @endif
                                        </td>
                                        @if(Auth::user()->isadmin == 1)
                                            <td>
                                                <a class="btn btn-success-rgba" data-toggle="modal" data-target="#deletemodal{{$item['id']}}"><i class="fa fa-remove"></i></a>
                                            </td>
                                        @endif
                                    </tr>
                                    @if(Auth::user()->isadmin == 1)
                                        <div class="modal fade" id="deletemodal{{$item['id']}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="vertical-alignment-helper">
                                                <div class="modal-dialog vertical-align-center">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                        
                                                            </button>
                                                            <h4 class="modal-title" id="myModalLabel">Sofa League</h4>
                                        
                                                        </div>
                                                        <div class="modal-body">
                                                            <p class="font-24">{{__('common.remove_userteam_warning')}}</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-primary float-left" data-dismiss="modal">{{__('common.no')}}</button>
                                                            <button type="button" class="btn btn-danger" onclick="deleteUserTeam({{$item['id']}})">{{__('common.yes')}}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </table>
                    </div>

                @endif
            </div>
        </div>
    </div>
</div>
<!-- Fixtures Area End -->

@endsection

@section('scripts')
    <script>
        function deleteUserTeam(id) {

            $.ajax({
                method: "post",
                url: "{{route('userteams.delete')}}",
                headers: {
                    'X-CSRF-TOKEN': '<?= csrf_token() ?>'
                },

                data : JSON.stringify({id : id}),
                datatype: 'JSON',
                contentType: 'application/json',

                async: true,
                success: function (data) {
                    if(data) {
                        window.location = "{{route('userteams')}}";
                    }
                },
                error: function () {
                    console.log("error");
                }
            });
        }
        function getResultsByGameId(e) {
            window.location.href = "{{route('userteams')}}?game=" + e.value;
        }
        function getResultsByRoundId(e) {
            window.location.href = "{{route('userteams')}}?round=" + e.value + "&" + "game={{$game}}";
        }
    </script>

@endsection