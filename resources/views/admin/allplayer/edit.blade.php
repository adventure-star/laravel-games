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
                    <form id="submit-form" action="{{route('games.players.update')}}" method="post">
                        @csrf
                        <h4>{{__('admin.edit_player')}}</h4>
                        <input class="hidden" name="id" value={{$id}} />
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <div class="w-100 maxwidth-200 mx-auto">
                                    <p class="player-label">ID</p>
                                    <input type="number" name="playerid" @if(isset($player)) value="{{$player["playerid"]}}"@endif />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <div class="w-100 maxwidth-200 mx-auto">
                                    <p class="player-label">{{__('common.team')}}</p>
                                    <input type="text" name="team" @if(isset($player)) value="{{$player["team"]}}"@endif />
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="w-100 maxwidth-200 mx-auto">
                                    <p class="player-label">{{__('common.name')}}</p>
                                    <input type="text" name="name" @if(isset($player)) value="{{$player["name"]}}"@endif />
                                </div>
                            </div>
                        </div>
                        <h5 class="mt-20">{{__('common.additional_inputs')}}</h5>
                        <hr />
                        <div id="newinputs" class="row">
                            @if(isset($player['detail']))
                                @foreach(json_decode($player['detail']) as $key => $value)
                                    <div class="col-md-5 col-xs-12">
                                        <div class="w-100 maxwidth-200 mx-auto">
                                            <p class="player-label">Title</p>
                                            <input type="text" onkeyup="changeName(this)" value="{{$key}}" required />
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-xs-12">
                                        <div class="w-100 maxwidth-200 mx-auto">
                                            <p class="player-label">{{__('common.content')}}</p>
                                            <input type="text" value="{{$value}}" name="{{$key}}" required />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <div class="w-100 mx-auto d-flex align-items-center">
                                            <button type="button w-100" class="close" onclick="removeItem(this)">
                                                <span aria-hidden="true">&times;</span>
                                                <span class="sr-only">{{__('common.close')}}</span>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="row">
                            <input type="button" onclick="addInput()" value="Add New Input" />
                        </div>
                        <hr />
                        <input type="submit" value="Submit">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Contact Area End -->

@endsection

@section('scripts')
    <script>
        function removeItem(obj) {
            $(obj).parent().parent().prev().prev().remove();
            $(obj).parent().parent().prev().remove();
            $(obj).parent().parent().remove();
        }
        function addInput() {

            $("#newinputs").append('<div class="col-md-5 col-xs-12"><div class="w-100 maxwidth-200 mx-auto">'+
                                    '<p class="player-label">Title</p><input type="text" onkeyup="changeName(this)" required />'+
                                '</div></div>'+
                                '<div class="col-md-5 col-xs-12"><div class="w-100 maxwidth-200 mx-auto">'+
                                    '<p class="player-label">Content</p><input type="text" required />'+
                                '</div></div>'+
                                '<div class="col-md-2 col-xs-12"><div class="w-100 mx-auto d-flex align-items-center">'+
                                    '<button type="button w-100" class="close" onclick="removeItem(this)"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'+
                                '</div></div>');

        }

        function changeName(obj) {
            $(obj).parent().parent().next().find("input").attr("name", obj.value)
        }
    </script>
@endsection
