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
                    <form id="submit-form" action="{{route('games.settings.save')}}" method="post">
                        @csrf
                        <h4>Game Settings : {{App\Model\Game::find($id)->name}}</h4>
                        <input class="hidden" type="text" name="gameid" value="{{$id}}" />
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3 col-xs-12">
                                <div class="w-100 maxwidth-200 mx-auto">
                                    <p class="player-label">Selective Item</p>
                                    <input type="text" name="item" @if(isset($item)) value="{{$item}}" @endif required />
                                </div>
                            </div>
                        </div>
                        <h5 class="mt-20">Item Types and Numbers</h5>
                        <hr />
                        <div id="newinputs" class="row">
                            @if(isset($game['other']))
                                @if(isset(json_decode($game['other'])->details))
                                    @foreach(json_decode(json_decode($game['other'])->details) as $key => $value)
                                        <div class="col-md-5 col-xs-12">
                                            <div class="w-100 maxwidth-200 mx-auto">
                                                <p class="player-label">Value</p>
                                                <input type="text" onkeyup="changeName(this)" value="{{$key}}" required />
                                            </div>
                                        </div>
                                        <div class="col-md-5 col-xs-12">
                                            <div class="w-100 maxwidth-200 mx-auto">
                                                <p class="player-label">Number</p>
                                                <input type="text" value="{{$value}}" name="{{$key}}" required />
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <div class="w-100 mx-auto d-flex align-items-center">
                                                <button type="button w-100" class="close" onclick="removeItem(this)">
                                                    <span aria-hidden="true">&times;</span>
                                                    <span class="sr-only">Close</span>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            @endif
                        </div>
                        <div class="row">
                            <input type="button" onclick="addInput()" value="Add New Item Type" />
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
        function upload() {
            document.getElementById("playeruploadform").submit();
        }
        function addInput() {

            $("#newinputs").append('<div class="col-md-5 col-xs-12"><div class="w-100 maxwidth-200 mx-auto">'+
                                    '<p class="player-label">Value</p><input type="text" onkeyup="changeName(this)" required />'+
                                '</div></div>'+
                                '<div class="col-md-5 col-xs-12"><div class="w-100 maxwidth-200 mx-auto">'+
                                    '<p class="player-label">Number</p><input type="text" required />'+
                                '</div></div>'+
                                '<div class="col-md-2 col-xs-12"><div class="w-100 mx-auto d-flex align-items-center">'+
                                    '<button type="button w-100" class="close" onclick="removeItem(this)"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'+
                                '</div></div>');

        }
        function changeName(obj) {
            $(obj).parent().parent().next().find("input").attr("name", obj.value)
        }
        function getRoundsByGameId(e) {
            window.location.href = "{{route('players.new')}}?game=" + e.value;
        }
        function removeItem(obj) {
            $(obj).parent().parent().prev().prev().remove();
            $(obj).parent().parent().prev().remove();
            $(obj).parent().parent().remove();
        }
    
    </script>
@endsection