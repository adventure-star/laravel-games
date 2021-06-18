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
                            <th>TeamName</th>
                        </tr>
                        @if(isset($games) && count($games) > 0)
                            @foreach($games as $key => $game)
                                <tr class="cursor-pointer hover-highlight" onclick="gosubmitpage({{$game['gameid']}})">
                                    <td>{{$key + 1}}</td>
                                    <td>{{App\Model\Game::find($game['gameid'])->name}}</td>
                                    <td>{{$game['teamname']}}</td>
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
        function gosubmitpage(id) {
            window.location.href = "/submit/" + id;
        }
    </script>

@endsection