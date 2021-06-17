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
                <p class="question-title pb-10">Round: @if(isset($id)) {{App\Model\Round::find($id)['roundno']}} @endif</p>
                <div class="table-responsive fixtures-table">
                    <table class="table">
                        <tr>
                            <th>Number</th>
                            <th>Content</th>
                            <th>Edit</th>
                            <th>Remove</th>
                        </tr>
                        <input id="roundid" class="hidden" name="id" value={{$id}} />
                        @if(isset($questions) && count($questions) > 0)
                            @foreach($questions as $key=>$item)
                                <tr>
                                    <td>{{$item["number"]}}</td>
                                    <td>{{$item["text"]}}</td>
                                    <td>
                                        <a href="{{route('questions.edit', $item['id'])}}" class="btn btn-success-rgba"><i class="fa fa-edit"></i></a>
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
                                                    <p class="font-24">Do you want to remove this question?</p>
                                                    <p class="font-14">All data related with Qinputs will be removed.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary float-left" data-dismiss="modal">No</button>
                                                    <button type="button" class="btn btn-danger" onclick="deleteQuestion({{$item['id']}})">Yes</button>
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
                            <a href="{{route('questions', ['game' => App\Model\Round::find($id)->gameid ])}}" class="underline text-primary text-xl-right">All Questions</a>
                        </p>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <p class="text-right-center py-4">
                            <a href="{{route('questions.new', $id)}}" class="underline text-primary text-xl-right">Add New Question to This Round</a>
                        </p>
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
        function deleteQuestion(id) {

            $.ajax({
                method: "post",
                url: "{{route('questions.delete')}}",
                headers: {
                    'X-CSRF-TOKEN': '<?= csrf_token() ?>'
                },

                data : JSON.stringify({id : id}),
                datatype: 'JSON',
                contentType: 'application/json',

                async: true,
                success: function (data) {
                    if(data) {
                        let roundid = $("#roundid").val();
                        window.location = "/questions/round/edit/" + roundid;
                    }
                },
                error: function () {
                    console.log("error");
                }
            });
        }
    </script>

@endsection
