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
                <!-- Fixtures Table -->
                <p>{{__('common.game')}}: <strong>{{App\Model\Game::find($gameid)->name}}</strong></p>
                <br />
                <div class="table-responsive fixtures-table">
                    <table class="table">
                        <tr>
                            <th>{{__('admin.roundno')}}</th>
                            <th>{{__('common.state')}}</th>
                            <th>{{__('common.edit')}}</th>
                            <th>{{__('common.remove')}}</th>
                        </tr>
                        @if(isset($rounds) && count($rounds) > 0)
                            @foreach($rounds as $key => $item)
                                <tr>
                                    <td>{{$item["roundno"]}}</td>
                                    <td>{{$item["state"] == 0 ? __('common.not_opened') : ($item["state"] == 1 ? __('common.active') : __('common.expired'))}}</td>
                                    <td>
                                        <a href="{{route('rounds.edit', $item['id'])}}" class="btn btn-success-rgba"><i class="fa fa-edit"></i></a>
                                    </td>
                                    <td>
                                        <a class="btn btn-success-rgba" data-toggle="modal" data-target="#deletemodal{{$item['id']}}"><i class="fa fa-remove"></i></a>
                                    </td>
                                </tr>
                                <div class="modal fade" id="deletemodal{{$item['id']}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="vertical-alignment-helper">
                                        <div class="modal-dialog vertical-align-center">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                
                                                    </button>
                                                     <h4 class="modal-title" id="myModalLabel">Sofa League</h4>
                                
                                                </div>
                                                <div class="modal-body">
                                                    <p class="font-24">{{__('admin.remove_round_warning')}}</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary float-left" data-dismiss="modal">{{__('common.no')}}</button>
                                                    <button type="button" class="btn btn-danger" onclick="deleteRound({{$item['id']}})">{{__('common.yes')}}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </table>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-xs-12">
                        <p class="text-left-center py-4">
                            <a href="{{route('games')}}" class="underline text-primary text-xl-right">{{__('admin.go_to_game_list')}}</a>
                        </p>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <p class="text-right-center py-4">
                            <a href="{{route('rounds.new', $gameid)}}" class="underline text-primary text-xl-right">{{__('admin.add_round_to_game')}}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Fixtures Area End -->

@endsection

@section('scripts')
    <script>
        function deleteRound(id) {

            $.ajax({
                method: "post",
                url: "{{route('rounds.delete')}}",
                headers: {
                    'X-CSRF-TOKEN': '<?= csrf_token() ?>'
                },

                data : JSON.stringify({id : id}),
                datatype: 'JSON',
                contentType: 'application/json',

                async: true,
                success: function (data) {
                    if(data) {
                        window.location = "{{route('rounds', $gameid)}}";
                    }
                },
                error: function () {
                    console.log("error");
                }
            });
        }

    </script>

@endsection