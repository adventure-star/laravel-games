@extends('layouts.app')

@section('content')

<!-- Fixtures Area Start -->
<div id="fixtures-area" class="fixtures-area section pb-120 pt-120">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12 text-center">
                <!-- Fixtures Table -->
                <div class="table-responsive fixtures-table">
                    <table class="table">
                        <tr>
                            <th>{{__('common.no_number')}}</th>
                            <th>{{__('common.name')}}</th>
                            <th>@if(Auth::user()) {{__('common.state')}} @else {{__('common.coming')}} @endif</th>
                            <th>{{__('common.deadline')}}</th>
                        </tr>
                        @if(isset($games) && count($games) > 0)
                            @foreach($games as $key => $game)
                                @if(Auth::user())
                                    @if($game->joined == 0)
                                        @if(App\Model\GameUser::where(['gameid' => $game['id'], 'userid' => Auth::user()->id])->first())
                                            <tr class="cursor-pointer hover-highlight" onclick="gosubmitpage({{$game['id']}})">
                                                <td>{{$key + 1}}</td>
                                                <td>{{$game['name']}}</td>
                                                <td>Team Created</td>
                                                <td>{{$game['deadline']}}</td>
                                            </tr>
                                        @else
                                            <tr class="cursor-pointer hover-highlight" data-toggle="modal" data-target="#joinmodal{{$game['id']}}">
                                                <td>{{$key + 1}}</td>
                                                <td>{{$game['name']}}</td>
                                                <td>Join now</td>
                                                <td>{{$game['deadline']}}</td>
                                            </tr>
                                        @endif
                                        @if(Auth::user())
                                            <div class="modal fade" id="joinmodal{{$game['id']}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="vertical-alignment-helper">
                                                    <div class="modal-dialog vertical-align-center">
                                                        <form action="{{route('games.register')}}" method="post">
                                                            @csrf
                                                            <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{__('common.close')}}</span>
                                                    
                                                                        </button>
                                                                        <h4 class="modal-title" id="myModalLabel">Sofa League</h4>
                                                    
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p class="font-24">{{__('common.do_you_want_to_join_to')}} <strong>{{$game['name']}}</strong>?</p>
                                                                        <p>{{__('common.enter_teamname')}}!</p>
                                                                        <div class="row">
                                                                            <div class="maxwidth-200 mx-auto">
                                                                                <input type="text" class="hidden" name="gameid" value="{{$game['id']}}" />
                                                                                <input type="text" class="teamname" name="teamname" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-primary float-left" data-dismiss="modal">{{__('common.no')}}</button>
                                                                        <button type="submit" class="btn btn-danger" onclick="join({{$game['id']}})">{{__('common.yes')}}</button>
                                                                    </div>
                                                            </div>
                                                        </form>
        
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @else
                                    <tr class="cursor-pointer hover-highlight" onclick="gosubmitpage({{$game['id']}})">
                                        <td>{{$key + 1}}</td>
                                        <td>{{$game['name']}}</td>
                                        <td>{{json_decode($game['other'])->coming}}</td>
                                        <td>{{$game['deadline']}}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Fixtures Area End -->

@endsection

@section('scripts')
    <script>
        function gosubmitpage(id) {
            window.location.href = "/submit/" + id;
        }
    </script>

@endsection
