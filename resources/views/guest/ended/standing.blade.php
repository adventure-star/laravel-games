@extends('layouts.app')

@section('content')

<!-- Fixtures Area Start -->
<div id="fixtures-area" class="fixtures-area section pb-120 pt-120">
    <div class="container">
        <div class="row">
            
            <div class="col-md-12 col-xs-12 text-center">

            <h4 class="mb-20">{{App\Model\Game::find($id)->name}}</h4>

                <div class="table-responsive fixtures-table">
                    <table class="table">
                        <tr>
                            <th>{{__('common.team')}}</th>
                            <th>{{__('common.total_points')}}</th>
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
