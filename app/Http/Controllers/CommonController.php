<?php

namespace App\Http\Controllers;

use App\Model\Answer;
use App\Model\Fixture;
use App\Model\Game;
use App\Model\GameUser;
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

    public function submit(Request $request, $id) {

        $round = $request->query('round');

        $rounds = Round::where(["state" => 1, 'gameid' => $id, 'active' => 1])->get();

        $game = Game::find($id);

        if(!!!$game->other) {
            if(Auth::user()) {
                return redirect()->route('games.joined');
            } else {
                return redirect()->route('games.open');
            }
        }

        $other = json_decode($game->other);

        $category = $other->item;

        $details = json_decode($other->details);

        $players = Player::where(['gameid' => $id, 'roundid' => $round])->get();

        $questions = Question::where(['gameid' => $id, 'roundid' => $round])->get();

        if(!Auth::User()) {
            return view('guest.submit', compact('rounds', 'id', 'category', 'details', 'round', 'players', 'questions'));
        }

        $olddata = Team::where(['userid' => Auth::user()->id, 'gameid' => $id, 'roundid' => $round])->first();

        if($olddata) {
            $olddetail = get_object_vars(json_decode($olddata->detail));
            return view('guest.submit', compact('rounds', 'id', 'category', 'details', 'round', 'players', 'questions', 'olddetail'));
        } else {
            return view('guest.submit', compact('rounds', 'id', 'category', 'details', 'round', 'players', 'questions'));
        }

    }

    public function rule() {
        return view('guest.rule');
    }
    public function getteamname(Request $request) {
        $record = RealTeam::find($request->id);

        $data['name'] = $record ? $record->name : 0;

        return response()->json($data, 200);
    }
    public function submitsave(Request $request) {

        $validator = Validator::make($request->all(),
        [
            'gameid' => 'required|string',
            'roundid' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $record = Team::where(["userid" => Auth::user()->id, "gameid" => $request->gameid, "roundid" => $request->roundid])->first();

        $detail = [];

        $defaults = array('gameid', 'roundid', '_token');
        $detailkeys = array();

        $pattern = '/^question_/';

        foreach($request->all() as $key => $item) {

            if(in_array($key, $detailkeys)) {
                return redirect()->back()->withInput();
            }

            if(!in_array($key, $defaults) && !preg_match($pattern, $key)) {

                $detail[$key] = $item;
            }

            array_push($detailkeys, $key);

        }


        if($record) {
            $data = ([
                'detail' => json_encode($detail),
            ]);

            Team::where('id', $record->id)->update($data);

        } else {
            $new = new Team();
            $new->userid = Auth::user()->id;
            $new->gameid = $request->gameid;
            $new->roundid = $request->roundid;
            $new->detail = json_encode($detail);
            $new->save();
        }

        Answer::where(['userid' => Auth::user()->id, 'gameid' => $request->gameid, 'roundid' => $request->roundid])->delete();

        foreach($request->all() as $key=>$value) {

            if(preg_match($pattern, $key)) {

                $newanswer = new Answer();
                $newanswer->userid = Auth::user()->id;
                $newanswer->gameid = $request->gameid;
                $newanswer->roundid = $request->roundid;
                $newanswer->questionid = number_format(substr($key, 9));
                $newanswer->qinputid = $value;
                $newanswer->save();

            }

        }

        return redirect()->route('games.joined');

    }
    public function profile() {

        $user = Auth::user();
        return view('guest.profile', compact('user'));
    }
    public function standing(Request $request) {

        $game = $request->query('game');
        $round = $request->query('round');

        $games = Game::where('state', 2)->get();


        $rounds = Round::where('state', '=', 2)->get();

        // if(!!$request->query('round') && $request->query('round') !== 'all') {

        //     $teams = Team::leftJoin('results', 'teams.id', '=', 'results.team')
        //     ->selectRaw('teams.*, results.point as point')
        //     ->where('teams.round', $round)
        //     ->groupBy('teams.id')
        //     ->orderBy('point', 'desc')
        //     ->get();

        // } else {
        //     $teams = Team::leftJoin('results', 'teams.id', '=', 'results.team')
        //     ->selectRaw('teams.userid, sum(results.point) as point')
        //     ->groupBy('teams.userid')
        //     ->orderBy('point', 'desc')
        //     ->get();

        // }

        // if(!!$round) {
        //     return view('common.userteam.standing', compact('rounds', 'teams', 'round'));
        // } else {
        //     return view('common.userteam.standing', compact('rounds', 'teams'));
        // }

        return view('common.userteam.standing', compact('rounds', 'games', 'game', 'round'));

        
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

        $game = $request->query('game');
        $round = $request->query('round');

        if(Auth::user()->isadmin == 1) {
            $games = Team::select('gameid')->distinct()->orderBy('roundid', 'asc')->get();
            $teams = Team::where(['gameid' => $game])->orderBy('roundid', 'asc')->get();
        } else {
            $games = Team::where(['userid' => Auth::user()->id])->select('gameid')->distinct()->orderBy('roundid', 'asc')->get();
            $teams = Team::where(['userid' => Auth::user()->id, 'gameid' => $game])->orderBy('roundid', 'asc')->get();
        }

        if(!!$game) {

            $rounds = Round::where('active', 1)->where('gameid', $game)->get();

            if(!!!$round || $round == "all") {
                return view('common.userteam.list', compact('teams', 'games', 'game', 'rounds'));
            } else {
                if(Auth::user()->isadmin == 1) {
                    $teams = Team::where(['gameid' => $game, 'roundid' => $round])->orderBy('roundid', 'asc')->get();
                } else {
                    $teams = Team::where(['gameid' => $game, 'roundid' => $round, 'userid' => Auth::user()->id])->orderBy('roundid', 'asc')->get();
                }
                return view('common.userteam.list', compact('teams', 'games', 'game', 'rounds', 'round'));
            }

        } else {
            return view('common.userteam.list', compact('teams', 'games', 'game'));

        }

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

        if(Auth::user()) {
            foreach($games as $key=>$game) {
                $joined = GameUser::where(['gameid' => $game->id, 'userid' => Auth::user()->id])->get();
                if(count($joined) != 0) {
                    $game['joined'] = 1;
                } else {
                    $game['joined'] = 0;
                }
            }
        }

        return view('guest.game.open', compact('games'));

    }
    public function gamecalendar() {
        $games = Game::where(['active' => 1])->get();
        return view('guest.game.calendar', compact('games'));
    }
    public function endedgames() {
        $games = Game::where(['active' => 1, 'state'=> 2])->get();
        return view('guest.game.ended', compact('games'));
    }

    public function getFinalStanding($id) {

        $teams = [];

        return view('guest.ended.standing', compact('id'));

    }

    public function joinedgames() {

        $games = GameUser::where('userid', Auth::user()->id)->orderBy('gameid', 'asc')->get();

        return view('guest.game.joined', compact('games'));
    }
    public function gameregister(Request $request) {

        $validator = Validator::make($request->all(),
        [
            'gameid' => 'required|numeric',
            'teamname' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $new = new GameUser();

        $new->userid = Auth::user()->id;
        $new->gameid = $request->gameid;
        $new->teamname = $request->teamname;

        $new->save();

        return redirect()->route("games.joined");

    }


}
