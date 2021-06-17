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
                    <form id="submit-form" action="{{route('players.pointupdate')}}" method="post">
                        @csrf
                        <h4>Edit Player Point</h4>
                        <input class="hidden" name="id" value={{$id}} />
                        <div id="newinputs" class="row">
                            @if(isset($detail))
                                @foreach(json_decode($detail) as $key => $value)
                                    @if($key != 'keypairs')
                                        <div class="col-md-5 col-xs-12">
                                            <div class="w-100 maxwidth-200 mx-auto">
                                                <p class="player-label">Title</p>
                                                <input type="text" onkeyup="changeName(this)" value="{{json_decode(json_decode($detail)->keypairs)->$key}}" required />
                                            </div>
                                        </div>
                                        <div class="col-md-5 col-xs-12">
                                            <div class="w-100 maxwidth-200 mx-auto">
                                                <p class="player-label">Content</p>
                                                <input type="text" value="{{$value}}" name="{{json_decode(json_decode($detail)->keypairs)->$key}}" required />
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <div class="w-100 mx-auto d-flex align-items-center">
                                                <button type="button w-100" class="close" onclick="removeItem(this)">
                                                    <span aria-hidden="true">&times;</span>
                                                    <span class="sr-only">Close</span>
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        <hr />
                        <div class="row">
                            <input type="button" onclick="addInput()" value="Add New Input" />
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3 col-xs-12">
                                <div class="w-100 maxwidth-200 mx-auto">
                                    <p class="player-label">Point Total</p>
                                    <input type="number" name="total" @if(isset($total)) value="{{$total}}" @else value="{{old('total')}}" @endif required/>
                                </div>
                            </div>
                        </div>
                        <input type="submit" value="Submit">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Contact Area End -->

@endsection

@section('scripts')
    <script>
        function addInput() {

            $("#newinputs").append('<div class="col-md-5 col-xs-12"><div class="w-100 maxwidth-200 mx-auto">'+
                                    '<p class="player-label">Title</p><input type="text" onkeyup="changeName(this)" required />'+
                                '</div></div>'+
                                '<div class="col-md-5 col-xs-12"><div class="w-100 maxwidth-200 mx-auto">'+
                                    '<p class="player-label">Content</p><input type="text" required />'+
                                '</div></div>'+
                                '<div class="col-md-2 col-xs-12"><div class="w-100 mx-auto d-flex align-items-center">'+
                                    '<button type="button w-100" class="close" onclick="removeItem(this)"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'+
                                '</div></div>');

        }
        function removeItem(obj) {
            $(obj).parent().parent().prev().prev().remove();
            $(obj).parent().parent().prev().remove();
            $(obj).parent().parent().remove();
        }
        function changeName(obj) {
            $(obj).parent().parent().next().find("input").attr("name", obj.value)
        }
    </script>
@endsection
