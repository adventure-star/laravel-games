@extends('layouts.app')

@section('content')

<!-- Fixtures Area Start -->
<div id="fixtures-area" class="fixtures-area section pb-120 pt-120">
    <div class="container">
        <div class="row">
            
            <div class="col-md-10 col-md-offset-1 col-xs-12 text-center">
                <!-- Fixtures Table -->
                <div class="table-responsive fixtures-table">
                    <table class="table">
                        <tr>
                            <th>{{__('common.fullname')}}</th>
                            <th>{{__('common.username')}}</th>
                            <th>{{__('common.email')}}</th>
                            <th>{{__('admin.marketing')}}</th>
                            <th>{{__('admin.paid')}}</th>
                            <th>{{__('common.remove')}}</th>
                        </tr>
                        @if(isset($users) && count($users) > 0)
                            @foreach($users as $key => $item)
                                <tr>
                                    <td>{{$item["fullname"]}}</td>
                                    <td>{{$item["displayname"]}}</td>
                                    <td>{{$item["email"]}}</td>
                                    <td>{{$item["ismarketing"] == 1 ? __('common.yes') : __('common.no') }}</td>
                                    <td>
                                        <input type="checkbox" class="cursor-pointer" @if($item["ispaid"] == 1) checked @endif onchange="changepaidstate({{$item['id']}})" />
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
                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{__('common.close')}}</span>
                                
                                                    </button>
                                                    <h4 class="modal-title" id="myModalLabel">Sofa League</h4>
                                
                                                </div>
                                                <div class="modal-body">
                                                    <p class="font-24">{{__('admin.remove_user_warning')}}</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary float-left" data-dismiss="modal">{{__('common.no')}}</button>
                                                    <button type="button" class="btn btn-danger" onclick="deleteUser({{$item['id']}})">{{__('common.yes')}}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
        function deleteUser(id) {

            $.ajax({
                method: "post",
                url: "{{route('users.delete')}}",
                headers: {
                    'X-CSRF-TOKEN': '<?= csrf_token() ?>'
                },

                data : JSON.stringify({id : id}),
                datatype: 'JSON',
                contentType: 'application/json',

                async: true,
                success: function (data) {
                    if(data) {
                        window.location = "{{route('users')}}";
                    }
                },
                error: function () {
                    console.log("error");
                }
            });
        }
        function changepaidstate(id) {
            console.log("id-----", id);
            $.ajax({
                method: "post",
                url: "{{route('users.paid')}}",
                headers: {
                    'X-CSRF-TOKEN': '<?= csrf_token() ?>'
                },

                data : JSON.stringify({id : id}),
                datatype: 'JSON',
                contentType: 'application/json',

                async: true,
                success: function (data) {
                    if(data) {
                        window.location = "{{route('users')}}";
                    }
                },
                error: function () {
                    console.log("error");
                }
            });

        }
    </script>

@endsection