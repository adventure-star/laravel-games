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
                        <h4>{{__('admin.new_team')}}</h4>
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <div class="w-100 maxwidth-200 mx-auto">
                                    <h4>{{__('common.select_game')}}</h4>
                                    <select class="normal-component maxwidth-200" name="gameid" onchange="getResultsByGameId(this)">
                                        <option disabled selected>{{__('common.select_game')}}!</option>
                                        @if(isset($games) && count($games) > 0)
                                            @foreach($games as $key => $item)
                                                <option value={{$item['id']}} @if(isset($game) && $game == $item['id']) selected @endif>{{$item['name']}} ({{$item['state'] == 0 ? __('common.not_opened') : ($item['state'] == 1 ? __('common.active') : __('common.expired'))}})</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3 col-xs-12">
                                <div class="w-100 maxwidth-200 mx-auto">
                                    <p class="player-label">{{__('common.name')}}</p>
                                    <input type="text" name="longname" value="{{old('longname')}}" required/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3 col-xs-12">
                                <div class="w-100 maxwidth-200 mx-auto">
                                    <p class="player-label">{{__('common.short')}}</p>
                                    <input type="text" name="shortname" value="{{old('shortname')}}" required/>
                                </div>
                            </div>
                        </div>
                        <input type="submit" value="Submit">
                    </form>
                    <div class="mt-40">
                        <h4>{{__('admin.import_csv_abb')}}</h4>
                        <form id="playeruploadform" action="{{route('uploads.abbs')}}" method="post" enctype="multipart/form-data">
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
    </script>

@endsection
