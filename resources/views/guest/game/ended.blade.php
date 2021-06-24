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
                        </tr>
                        @if(isset($games) && count($games) > 0)
                            @foreach($games as $key => $game)
                                <tr class="cursor-pointer hover-highlight" onclick="getFinalStanding({{$game['id']}})">
                                    <td>{{$key + 1}}</td>
                                    <td>{{$game['name']}}</td>
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
        function getFinalStanding(id) {
            window.location.href = "/finalstanding/" + id;
        }
    </script>

@endsection