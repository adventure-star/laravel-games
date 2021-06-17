<?php

namespace App\Http\Controllers;

use App\Model\Answer;
use App\Model\Fixture;
use App\Model\Game;
use App\Model\Joined;
use App\Model\Player;
use App\Model\Point;
use App\Model\QInput;
use App\Model\Question;
use App\Model\RealTeam;
use App\Model\Round;
use App\Model\Team;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDO;
use SimpleXLSX;
use Illuminate\Support\Facades\Validator;

class CommonController extends Controller
{
    public function index() {
        return view('index');
    }
    public function about() {
        return view('origin.about');
    }
    public function blog() {
        return view('origin.blog');
    }
    public function blogdetails() {
        return view('origin.blog-details');
    }
    public function cart() {
        return view('origin.cart');
    }
    public function checkout() {
        return view('origin.checkout');
    }
    public function contact() {
        return view('origin.contact');
    }

    public function pointtable() {
        return view('origin.point-table');
    }
    public function productdetails() {
        return view('origin.product-details');
    }
    public function shop() {
        return view('origin.shop');
    }
    public function team() {
        return view('origin.team');
    }
    public function wishlist() {
        return view('origin.wishlist');
    }

    public function submit() {

        $rounds = Round::where("ended", "=", 1)->get();

        return view('guest.submit', compact('rounds'));

    }

    public function rule() {
        return view('guest.rule');
    }

    public function submitdata(Request $request)
    {

        $goalkeepers = Player::where("position", "=", "G")->where("round", "=", $request->round)->get();
        $defender1 = Player::where("position", "=", "D1")->where("round", "=", $request->round)->get();
        $defender2 = Player::where("position", "=", "D2")->where("round", "=", $request->round)->get();
        $midfielder1 = Player::where("position", "=", "M1")->where("round", "=", $request->round)->get();
        $midfielder2 = Player::where("position", "=", "M2")->where("round", "=", $request->round)->get();
        $forward1 = Player::where("position", "=", "F1")->where("round", "=", $request->round)->get();
        $forward2 = Player::where("position", "=", "F2")->where("round", "=", $request->round)->get();

        // foreach($goalkeepers as $key=>$value) {
        //     if($key == 'team') {
        //         $goalkeepers->$key = RealTeam::find($value)->name;
        //     }
        // }

        $questions = Question::where("round", "=", $request->round)->get();

        foreach($questions as $question) {
            $question["qinputs"] = QInput::where("qid", "=", $question['id'])->get();
        }

        $fixtures = Fixture::where("round", "=", $request->round)->get();

        $teams = RealTeam::all();

        $data = array(
            'g'=>$goalkeepers, 
            'd1'=>$defender1, 
            'd2'=>$defender2, 
            'm1'=>$midfielder1, 
            'm2'=>$midfielder2, 
            'f1'=>$forward1, 
            'f2'=>$forward2, 
            'questions'=>$questions, 
            'fixtures'=>$fixtures,
            'teams'=>$teams
        );

        $olddata = Team::where('jid', '=', Auth::id())->where('round', '=', $request->round)->get();

        $oldanswers = Answer::where('jid', '=', Auth::id())->where('round', '=', $request->round)->get();

        if(count($olddata) != 0) {
            $data["old"] = $olddata;
        }
        if(count($oldanswers) != 0) {
            $data["oldanswers"] = $oldanswers;
        }

        return response()->json($data, 200);

    }
    public function getteamname(Request $request) {
        $record = RealTeam::find($request->id);

        $data['name'] = $record ? $record->name : 0;

        return response()->json($data, 200);
    }
    public function submitsave(Request $request) {

        $validator = Validator::make($request->all(),
        [
            'g' => 'required|string',
            'd1' => 'required|string',
            'd2' => 'required|string',
            'm1' => 'required|string',
            'm2' => 'required|string',
            'f1' => 'required|string',
            'f2' => 'required|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $record = Team::where("jid", "=", Auth::user()->id)->where("round", "=", $request->round)->first();

        if($record) {
            $data = ([
                'g' => $request->g,
                'd1' => $request->d1,
                'd2' => $request->d2,
                'm1' => $request->m1,
                'm2' => $request->m2,
                'f1' => $request->f1,
                'f2' => $request->f2
            ]);

            Team::where('id', $record->id)->update($data);

        } else {
            $new = new Team();
            $new->jid = Auth::user()->id;
            $new->round = $request->round;
            $new->g = $request->g;
            $new->d1 = $request->d1;
            $new->d2 = $request->d2;
            $new->m1 = $request->m1;
            $new->m2 = $request->m2;
            $new->f1 = $request->f1;
            $new->f2 = $request->f2;
            $new->save();
        }

        $pattern = '/^question_/';

        foreach($request->all() as $key=>$value) {

            if(preg_match($pattern, $key)) {

                print_r(number_format(substr($key, 9)));

                $record = Answer::where('jid', '=', Auth::user()->id)->where('round', '=', $request->round)->where('question', '=', number_format(substr($key, 9)))->first();
                if($record) {
                    $data = ([
                            'qinput' => $value
                        ]);
                        
                    Answer::where('id', $record->id)->update($data);
                } else {
                    $newanswer = new Answer();
                    $newanswer->jid = Auth::user()->id;
                    $newanswer->round = $request->round;
                    $newanswer->question = number_format(substr($key, 9));
                    $newanswer->qinput = $value;
                    $newanswer->save();
                }

            }

        }

        return redirect()->route('userteams');

    }
    public function profile() {

        $user = Auth::user();
        return view('guest.profile', compact('user'));
    }
    public function standing(Request $request) {

        $queryround = $request->query('round');

        $rounds = Round::where('state', '=', 2)->get();

        if(!!$request->query('round') && $request->query('round') !== 'all') {

            $teams = Team::leftJoin('results', 'teams.id', '=', 'results.team')
            ->selectRaw('teams.*, results.point as point')
            ->where('teams.round', $queryround)
            ->groupBy('teams.id')
            ->orderBy('point', 'desc')
            ->get();

        } else {
            $teams = Team::leftJoin('results', 'teams.id', '=', 'results.team')
            ->selectRaw('teams.userid, sum(results.point) as point')
            ->groupBy('teams.userid')
            ->orderBy('point', 'desc')
            ->get();

        }

        if(!!$queryround) {
            return view('common.userteam.standing', compact('rounds', 'teams', 'queryround'));
        } else {
            return view('common.userteam.standing', compact('rounds', 'teams'));
        }
        
    }

    public function groupstanding(Request $request) {

        $queryround = $request->query('round');

        $rounds = Round::where('ended', '=', 2)->get();

        if(!!$request->query('round') && $request->query('round') !== 'all') {

            $teams = Team::leftJoin('results', 'teams.id', '=', 'results.team')
            ->selectRaw('teams.*, results.point as point')
            ->where('teams.round', $queryround)
            ->leftJoin('users', 'teams.jid', '=', 'users.id')
            ->selectRaw('teams.*, users.ispaid as ispaid')
            ->where('ispaid', 1)
            ->groupBy('teams.id')
            ->orderBy('point', 'desc')
            ->get();

        } else {

            $teams = Team::leftJoin('results', 'teams.id', '=', 'results.team')
            ->selectRaw('teams.jid, sum(results.point) as point')
            ->leftJoin('users', 'teams.jid', '=', 'users.id')
            ->selectRaw('teams.*, users.ispaid as ispaid')
            ->where('ispaid', 1)
            ->groupBy('teams.jid')
            ->orderBy('point', 'desc')
            ->get();

        }

        if(!!$queryround) {
            return view('common.userteam.groupstanding', compact('rounds', 'teams', 'queryround'));
        } else {
            return view('common.userteam.groupstanding', compact('rounds', 'teams'));
        }
        
    }

    public function userteams(Request $request) {

        if(Auth::user()->isadmin == 1) {
            $teams = Team::all();
        } else {
            $teams = Team::where('jid', Auth::user()->id)->orderBy('round', 'asc')->get();
        }

        return view('common.userteam.list', compact('teams'));

    }

    public function pointdetails($id) {

        $team = Team::find($id);

        $g = Point::where('playerid', '=', $team['g'])->first();
        $d1 = Point::where('playerid', '=', $team['d1'])->first();
        $d2 = Point::where('playerid', '=', $team['d2'])->first();
        $m1 = Point::where('playerid', '=', $team['m1'])->first();
        $m2 = Point::where('playerid', '=', $team['m2'])->first();
        $f1 = Point::where('playerid', '=', $team['f1'])->first();
        $f2 = Point::where('playerid', '=', $team['f2'])->first();

        $answers = Answer::where(['jid' => $team['jid'], 'round' => $team['round']])->get();

        $detail = [
            'g' => $g,
            'd1' => $d1,
            'd2' => $d2,
            'm1' => $m1,
            'm2' => $m2,
            'f1' => $f1,
            'f2' => $f2,
        ];

        return view('common.userteam.pointdetail', compact('detail', 'answers'));

    }


    // public function test() {

    //     if ( $xlsx = SimpleXLSX::parse('uploads/points.xlsx') ) {
    //         if($xlsx->rows(1)) {
    //             dd($xlsx->rows(1));
    //         } else {
    //             echo $xlsx->toHTML();
    //         }
    //     } else {
    //         echo SimpleXLSX::parseError();
    //     }
    // }

    public function opengames() {
        $games = Game::where(['active' => 1, 'state'=> 1])->get();
        return view('common.game.open', compact('games'));
    }
    public function gamecalendar() {
        $games = Game::where(['active' => 1])->get();
        return view('common.game.open', compact('games'));
    }
    public function endedgames() {
        $games = Game::where(['active' => 1, 'state'=> 2])->get();
        return view('common.game.open', compact('games'));
    }


}
