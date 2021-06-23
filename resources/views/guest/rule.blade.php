@extends('layouts.app')

@section('content')

<!-- Page Banner Area Start -->
<div id="page-banner-area" class="page-banner-area section">
    <div class="container">
    </div>
</div>
<!-- Page Banner Area End -->

<!-- Profile Area Start -->
<div id="fixtures-area" class="fixtures-area section pb-120 pt-120">
    <div class="container-fluid">
        <div class="col-md-10 col-md-offset-1 col-xs-12 text-center">
            <div class="panel-group" id="payment-accordion">
                <div class="panel panel-default">
                    <h4 class="panel-title bg-white"><a data-toggle="collapse" data-parent="#payment-accordion" href="#payment-1">rules</a></h4>
                    <div id="payment-1" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <p class="text-left">
                                <br />
                                The game is based on Tour de France, taking place from 26 June to 18 July.<br /><br />
                                The game is divided in 4 rounds:<br />
                                -	Round 1: Stage 1-4, Deadline 26 June, 12:10 CET.<br />
                                -	Round 2: Stage 5-9, Deadline 30 June, 12:15 CET.<br />
                                -	Round 3: Stage 10-15, Deadline 6 July, 13:05 CET.<br />
                                -	Round 4: Stage 16-21, Deadline 13 July, 13:05 CET.<br /><br />
                                For each round you pick a Team of 8 Riders (2 Cat A, 2 Cat B & 4 Cat C) and make a guess for 3-6 questions concerning this specific round.<br />
                                It is not allowed to choose the same rider twice. 
                            </p>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <h4 class="panel-title bg-white"><a class="collapsed" data-toggle="collapse" data-parent="#payment-accordion" href="#payment-2">points</a></h4>
                    <div id="payment-2" class="panel-collapse collapse">
                        <div class="panel-body">
                            
                                <br />
                                <p class="text-left">
                                    <span class="rule-subheader">Team</span>
                                </p>
                            <p class="text-left">Stage positions is the only way to get points.</p>

                                <p class="text-left">
                                <i><strong>State position							(Points)</strong></i><br />
                                1			(65)<br />
                                2			(55)<br />
                                3			(50)<br />
                                4			(45)<br />
                                5			(40)<br />
                                6			(36)<br />
                                7			(32)<br />
                                8			(29)<br />
                                9			(26)<br />
                                10			(23)<br />
                                11			(20)<br />
                                12			(19)<br />
                                13			(18)<br />
                                14			(17)<br />
                                15			(16)<br />
                                16			(15)<br />
                                17			(14)<br />
                                18			(13)<br />
                                19			(12)<br />
                                20			(11)<br />
                                21			(10)<br />
                                22			(9)<br />
                                23			(8)<br />
                                24			(7)<br />
                                25			(6)<br />
                                26			(5)<br />
                                27			(4)<br />
                                28			(3)<br />
                                29			(2)<br />
                                30			(1)<br />
                                
                                Man of the Match (Awarded by Uefa)			(2)<br /><br />
                                <i><strong>Data will be collected from Sofascore.com</strong></i><br />

                                <span class="rule-subheader">Questions</span><br />
                                Points given will vary, depending on the number of options.<br />
                                Points to be given will be shown at the end of each question.<br />
                                Eg.<br />
                                In which match will be scored most goals (7, 4, 2)?<br />
                                You will get 7 points if you selected the match, where most goals were scored, 4 points for second most and 2 points for 3rd.<br /><br />
                                <i><strong>If more than one correct answer:</strong></i><br />
                                2 matches, identical highest goals scored: Both gets 6 points (7 + 4)/2 round up<br />
                                3 matches: all get 4 points (7 + 4 + 2)/3 round up<br />
                                4 matches: all get 4 points (7 + 4 + 2)/4 round up (You cannot get less than the next score)<br />
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Profile Area End -->

@endsection