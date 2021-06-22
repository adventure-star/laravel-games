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
            
            <div class="col-md-10 col-md-offset-1 col-xs-12 text-center">

                <div class="row">
                    <div class="mt-0 mb-20">
                        <h4>Select Game</h4>
                        <select class="normal-component maxwidth-200" onchange="getResultsByGameId(this)">
                            <option value="all">Total</option>
                            @if(isset($games) && count($games) > 0)
                                @foreach($games as $key => $item)
                                    <option value={{$item['id']}} @if(isset($game) && $game == $item['id']) selected @endif>{{$item['name']}} ({{$item['state'] == 0 ? "Not Opened" : ($item['state'] == 1 ? "Active" : "Expired")}})</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

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

                <!-- Fixtures Table -->
                <div class="table-responsive fixtures-table">
                    <table class="table">
                        <tr>
                            <th>ID</th>
                            <th>Team</th>
                            <th>Name</th>
                            <th>No</th>
                            <th>Edit</th>
                            <th>Point</th>
                            <th>New/Edit</th>
                            <th>Remove</th>
                        </tr>
                        @if(isset($players) && count($players) > 0)
                            @foreach($players as $key => $item)
                                <tr>
                                    <td>{{App\Model\Player::find($item["playerid"])->playerid}}</td>
                                    <td>{{$item["team"]}}</td>
                                    <td>{{$item["name"]}}</td>
                                    <td>{{$item["no"]}}</td>
                                    <td>
                                        <a href="{{route('players.edit', $item['id'])}}" class="btn btn-success-rgba"><i class="fa fa-edit"></i></a>
                                    </td>
                                    <td>{{App\Model\Point::where('roundplayerid', $item["id"])->first() ? App\Model\Point::where('roundplayerid', $item["id"])->first()->total : ""}}</td>
                                    <td>
                                        <a href="{{route('players.pointedit', $item['id'])}}" class="btn btn-success-rgba"><i class="fa fa-edit"></i></a>
                                    </td>
                                    <td>
                                        <a class="btn btn-success-rgba" data-toggle="modal" data-target="#deletemodal{{$item['id']}}"><i class="fa fa-remove"></i></a>
                                    </td>
                                </tr>
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
                                                    <p class="font-24">Do you want to remove this player?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary float-left" data-dismiss="modal">No</button>
                                                    <button type="button" class="btn btn-danger" onclick="deletePlayer({{$item['id']}})">Yes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </table>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-xs-12">
                        <p class="text-left-center py-4">
                            <a href="{{route('points')}}" class="underline text-primary text-xl-right">Import Points</a>
                        </p>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <p class="text-right-center py-4">
                            <a href="{{route('players.new')}}" class="underline text-primary text-xl-right">Add New Player</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
<!-- Fixtures Area End -->

@endsection

@section('scripts')
    <script>
        function deletePlayer(id) {

            $.ajax({
                method: "post",
                url: "{{route('players.delete')}}",
                headers: {
                    'X-CSRF-TOKEN': '<?= csrf_token() ?>'
                },

                data : JSON.stringify({id : id}),
                datatype: 'JSON',
                contentType: 'application/json',

                async: true,
                success: function (data) {
                    if(data) {
                        window.location = "{{route('players')}}";
                    }
                },
                error: function () {
                    console.log("error");
                }
            });
        }
        function getResultsByGameId(e) {
            window.location.href = "{{route('players')}}?game=" + e.value;
        }
        function getResultsByRoundId(e) {
            window.location.href = "{{route('players')}}?round=" + e.value + "&" + "game={{$game}}";
        }
    </script>

@endsection