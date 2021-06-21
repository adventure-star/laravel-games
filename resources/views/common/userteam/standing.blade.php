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

                <div class="row">
                    <div class="mt-0 mb-20">
                        <h4>Select Expired Games</h4>
                        <select class="normal-component maxwidth-200" onchange="getResultsByGameId(this)">
                            <option disabled selected>Select Game</option>
                            @if(isset($games) && count($games) > 0)
                                @foreach($games as $key => $item)
                                    <option value={{$item['id']}} @if(isset($game) && $game == $item['id']) selected @endif>{{$item['name']}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                @if(isset($game))

                    <div class="row">
                        <div class="mt-0 mb-20">
                            <h4>Select Round</h4>
                            <select class="normal-component maxwidth-200" onchange="getResultsByRoundId(this)">
                                <option value="all">Total</option>
                                @if(isset($rounds) && count($rounds) > 0)
                                    @foreach($rounds as $key => $item)
                                        <option value={{$item['id']}} @if(isset($round) && $round == $item['id']) selected @endif>{{$item['roundno']}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    @if(isset($round) && $round != 'all')
                        <!-- Fixtures Table -->
                        <div class="table-responsive fixtures-table">
                            <table class="table">
                                <tr>
                                    <th>Team</th>
                                    <th>round</th>
                                    <th>Detail</th>
                                    <th>Point</th>
                                    <th>PointDetail</th>
                                </tr>
                                @if(isset($teams) && count($teams) > 0)
                                    @foreach($teams as $key => $item)
                                        <tr>
                                            <td>{{App\Model\GameUser::where(['userid' => $item['userid'], 'gameid' => $item['gameid']])->first()->teamname}}</td>
                                            <td>{{App\Model\Round::find($item["roundid"])["roundno"] }}</td>
                                            <td class="maxwidth-200">
                                                @if(isset($item->detail))
                                                    @foreach(json_decode($item->detail) as $key=>$value)
                                                        {{$key}} : {{App\Model\Player::find($value)['name'] }}
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>{{$item['point']}}</td>
                                            <td>
                                                @if(!!$item["point"])
                                                    <a class="btn btn-success-rgba" href="{{route('pointdetails', $item['id'])}}"><i class="fa fa-bookmark"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </table>
                        </div>
                    @else
                        <div class="table-responsive fixtures-table">
                            <table class="table">
                                <tr>
                                    <th>Team</th>
                                    <th>Total Points</th>
                                </tr>
                                @if(isset($teams) && count($teams) > 0)
                                    @foreach($teams as $key => $item)
                                        <tr>
                                            <td>{{App\Model\GameUser::where(['userid' => $item['userid'], 'gameid' => $item['gameid']])->first()->teamname}}</td>
                                            <td>{{$item['point']}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </table>
                        </div>
                    @endif

                @endif

                @if(Auth::user())
                    @if(Auth::user()->isadmin == 1)
                    <div class="mt-40">
                        <h4>Calculate Points of UserTeams</h4>
                        <button onclick="calculate()" class="custom-file-upload mt-20">Calculate</button>
                    </div>
                    @endif
                @endif
           
            </div>
        </div>
    </div>
</div>
<!-- Fixtures Area End -->

@endsection

@section('scripts')
    <script>
        function calculate(id) {

            $.ajax({
                method: "post",
                url: "{{route('points.calculate')}}",
                headers: {
                    'X-CSRF-TOKEN': '<?= csrf_token() ?>'
                },

                data : JSON.stringify({id : 1}),
                datatype: 'JSON',
                contentType: 'application/json',

                async: true,
                success: function (data) {
                    if(data) {
                        window.location = "{{route('standing')}}";
                    }
                },
                error: function () {
                    console.log("error");
                }
            });
        }
        function getResultsByGameId(e) {
            window.location.href = "{{route('standing')}}?game=" + e.value;
        }
        function getResultsByRoundId(e) {
            window.location.href = "{{route('standing')}}?round=" + e.value + "&" + "game={{$game}}";
        }
    </script>

@endsection