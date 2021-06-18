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
                <!-- Fixtures Table -->
                <div class="table-responsive fixtures-table">
                    <table class="table">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                        </tr>
                        @if(isset($games) && count($games) > 0)
                            @foreach($games as $key => $game)
                                @if(Auth::user())
                                    @if($game->joined == 0)
                                        <tr class="cursor-pointer hover-highlight" data-toggle="modal" data-target="#joinmodal{{$game['id']}}">
                                            <td>{{$key + 1}}</td>
                                            <td>{{$game['name']}}</td>
                                        </tr>
                                        @if(Auth::user())
                                            <div class="modal fade" id="joinmodal{{$game['id']}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="vertical-alignment-helper">
                                                    <div class="modal-dialog vertical-align-center">
                                                        <form action="{{route('games.register')}}" method="post">
                                                            @csrf
                                                            <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                                    
                                                                        </button>
                                                                        <h4 class="modal-title" id="myModalLabel">Sofa League</h4>
                                                    
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p class="font-24">Do you want to join to <strong>{{$game['name']}}</strong>?</p>
                                                                        <p>Please Insert Your Team Name!</p>
                                                                        <div class="row">
                                                                            <div class="maxwidth-200 mx-auto">
                                                                                <input type="text" class="hidden" name="gameid" value="{{$game['id']}}" />
                                                                                <input type="text" class="teamname" name="teamname" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-primary float-left" data-dismiss="modal">No</button>
                                                                        <button type="submit" class="btn btn-danger" onclick="join({{$game['id']}})">Yes</button>
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