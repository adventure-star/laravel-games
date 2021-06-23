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
                    <div class="mt-40">
                        <h4>Import CSV file for Player Points</h4>
                        <form id="pointuploadform" action="{{route('uploads.point')}}" method="post" enctype="multipart/form-data">
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
            document.getElementById("pointuploadform").submit();
        }
    </script>
@endsection
