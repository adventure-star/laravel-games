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

            <h4 class="mb-20">{{App\Model\Game::find($id)->name}}</h4>

                <div class="table-responsive fixtures-table">
                    <table class="table">
                        <tr>
                            <th>Team</th>
                            <th>Total Points</th>
                        </tr>
                        @if(isset($teams) && count($teams) > 0)
                            @foreach($teams as $key => $item)
                                <tr>
                                    <td>{{App\Model\GameUser::where(['userid' => $item["userid"], 'gameid' => $id])->first()->teamname}}</td>
                                    <td>{{$item['point']}}</td>
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
        function calculate(id) {

            $.ajax({
                method: "post",
                url: "{{route('points.calculate')}}",
                headers: {
                    'X-CSRF-TOKEN': '<?= csrf_token() ?>'
                },

                data : JSON.stringify({id : 1}),
                datatype: 'JSON',
                contentType: 'application/json',

                async: true,
                success: function (data) {
                    if(data) {
                        window.location = "{{route('standing')}}";
                    }
                },
                error: function () {
                    console.log("error");
                }
            });
        }
        function getResultsByRoundId(e) {
            window.location.href = "{{route('standing')}}?round=" + e.value;
        }
    </script>

@endsection