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
                    <form id="submit-form" action="{{route('teams.new.save')}}" method="post">
                        @csrf
                        <h4>New Team</h4>
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <div class="w-100 maxwidth-200 mx-auto">
                                    <h4>Select Game</h4>
                                    <select class="normal-component maxwidth-200" name="gameid" onchange="getResultsByGameId(this)">
                                        <option disabled selected>Select Game!</option>
                                        @if(isset($games) && count($games) > 0)
                                            @foreach($games as $key => $item)
                                                <option value={{$item['id']}} @if(isset($game) && $game == $item['id']) selected @endif>{{$item['name']}} ({{$item['state'] == 0 ? "Not Opened" : ($item['state'] == 1 ? "Active" : "Expired")}})</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3 col-xs-12">
                                <div class="w-100 maxwidth-200 mx-auto">
                                    <p class="player-label">Long Name</p>
                                    <input type="text" name="longname" value="{{old('longname')}}" required/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3 col-xs-12">
                                <div class="w-100 maxwidth-200 mx-auto">
                                    <p class="player-label">Short Name</p>
                                    <input type="text" name="shortname" value="{{old('shortname')}}" required/>
                                </div>
                            </div>
                        </div>
                        <input type="submit" value="Submit">
                    </form>
                    <div class="mt-40">
                        <h4>Import CSV file for Abbs</h4>
                        <form id="playeruploadform" action="{{route('uploads.abbs')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="w-100 maxwidth-200 mx-auto">
                                        <p class="player-label">Game</p>
                                        <select name="gameid" required>
                                            <option disabled selected>Select Game!</option>
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
                                Import
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
    </script>

@endsection
