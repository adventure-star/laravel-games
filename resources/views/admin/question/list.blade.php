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
                        <h4>{{__('common.select_game')}}</h4>
                        <select class="normal-component maxwidth-200" onchange="getResultsByGameId(this)">
                            <option disabled selected>{{__('common.select_game')}}</option>
                            @if(isset($games) && count($games) > 0)
                                @foreach($games as $key => $item)
                                    <option value={{$item['id']}} @if(isset($game) && $game == $item['id']) selected @endif>{{$item['name']}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <!-- Fixtures Table -->
                <div class="table-responsive fixtures-table">
                    <table class="table">
                        <tr>
                            <th>{{__('common.round')}}</th>
                            <th>{{__('common.number')}}</th>
                            <th>{{__('common.text')}}</th>
                            <th>{{__('common.answers')}}</th>
                            <th>{{__('common.edit')}}</th>
                        </tr>
                        @if(isset($questions) && count($questions) > 0)
                            @foreach($questions as $key => $item)
                                @foreach($item as $key => $el)
                                    <tr>
                                        @if($key == 0)
                                            <td rowspan={{count($item)}}>{{App\Model\Round::find($el["roundid"])->roundno}}</td>
                                        @endif
                                        <td>{{$el["number"]}}</td>
                                        <td>{{$el["text"]}}</td>
                                        <td>
                                            <a href="{{route('questions.answers', $el['id'])}}" class="btn btn-success-rgba"><i class="fa fa-bookmark"></i></a>
                                        </td>
                                        @if($key == 0)
                                            <td rowspan={{count($item)}}>
                                                <a href="{{route('questions.round.edit', $el['roundid'])}}" class="btn btn-success-rgba"><i class="fa fa-edit"></i></a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endforeach
                        @endif
                        @if(isset($roundwithoutquestions) && count($roundwithoutquestions) > 0)
                            @foreach($roundwithoutquestions as $key => $item)
                                <tr>
                                    <td>{{App\Model\Round::find($item)->roundno}}</td>
                                    <td colspan="3">{{__('admin.no_questions')}}</td>
                                    <td>
                                        <a href="{{route('questions.round.edit', $item)}}" class="btn btn-success-rgba"><i class="fa fa-edit"></i></a>
                                    </td>
                                </tr>
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
        function getResultsByGameId(e) {
            window.location.href = "{{route('questions')}}?game=" + e.value;
        }
    </script>
@endsection