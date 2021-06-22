<?php

namespace App\Http\Controllers;

use App\Model\Answer;
use App\Model\Fixture;
use App\Model\Game;
use App\Model\Player;
use App\Model\Point;
use App\Model\QInput;
use App\Model\Question;
use App\Model\RealTeam;
use App\Model\Result;
use App\Model\Round;
use App\Model\RoundPlayer;
use App\Model\Team;
use App\User;
use Exception;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleXLSX;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    // Operations For Games

    public function games() {
        
        $games = Game::where('active', 1)->get();
        return view('admin.game.list', compact('games'));
    }
    public function gamenew() {
        return view('admin.game.new');
    }
    public function gameedit($id) {
        
        $game = Game::find($id);
        return view('admin.game.edit', compact('game', 'id'));
    }
    public function gameupdate(Request $request) {

        $validator = Validator::make($request->all(),
        [
            'name' => 'required|string',
            'state' => 'required|string',
        ]);

        $game = Game::where(['name' => $request->name, 'active' => 1])->first();

        if(!!$game && $game->id != $request->id) {
            return redirect()->back()->withInput();
        }

        $data = ([
            'name' => $request->name,
            'state' => $request->state,
            'deadline' => $request->deadline
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $updated = Game::where('id', $request->id)->update($data);

        return redirect()->route("games");

    }
    public function gameadd(Request $request) {

        $validator = Validator::make($request->all(),
        [
            'name' => 'required|string',
            'state' => 'required|string',
        ]);

        $game = Game::where(['name' => $request->name, 'active' => 1])->first();

        if(!!$game) {
            return redirect()->back()->withInput();
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $new = new Game();
        $new->name = $request->name;
        $new->state = $request->state;
        $new->deadline = $request->deadline;
        $new->save();

 
        return redirect()->route("games");

    }
    public function gamedelete(Request $request) {

        $deleted = Game::where('id', $request->id)->update(['active' => 0]);
        return $deleted;

    }
    public function gamesettings($id) {

        $game = Game::find($id);
        $other = Game::find($id)->other;

        if(!!$other) {
            $item = json_decode(Game::find($id)->other)->item;
            return view('admin.game.settings', compact('id', 'game', 'item'));
        } else {
            return view('admin.game.settings', compact('id', 'game'));
        }


    }
    public function gamesettingssave(Request $request) {
        
        $game = Game::find($request->gameid);

        $settings = [];

        $settings['item'] = $request->item;

        $defaults = array('gameid', 'item', '_token');
        $detailkeys = array();

        $detail = [];

        foreach($request->all() as $key => $item) {

            if(in_array($key, $detailkeys)) {
                return redirect()->back()->withInput();
            }

            if(!in_array($key, $defaults)) {

                $detail[$key] = $item;
            }

            array_push($detailkeys, $key);

        }

        $settings['details'] = json_encode($detail);

        Game::where('id', $request->gameid)->update(['other' => json_encode($settings)]);

        return redirect()->route("games");

    }

    // Operations For Rounds

    public function rounds($gameid) {
    
        $rounds = Round::where('active', 1)->where('gameid', $gameid)->get();
        return view('admin.round.list', compact('rounds', 'gameid'));
    }
    public function roundnew($gameid) {
        return view('admin.round.new', compact('gameid'));
    }
    public function roundedit($id) {
        
        $round = Round::find($id);
        return view('admin.round.edit', compact('round', 'id'));
    }
    public function roundupdate(Request $request) {

        $validator = Validator::make($request->all(),
        [
            'roundno' => 'required|string',
            'state' => 'required|string',
        ]);

        $round = Round::where(['gameid' => Round::find($request->id)->gameid, 'roundno' => $request->roundno, 'active' => 1])->first();

        if(!!$round && $round->id != $request->id) {
            return redirect()->back()->withInput();
        }

        $data = ([
            'roundno' => $request->roundno,
            'state' => $request->state,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $updated = Round::where('id', $request->id)->update($data);

        return redirect()->route("rounds", Round::find($request->id)->gameid);

    }
    public function roundadd(Request $request) {

        $validator = Validator::make($request->all(),
        [
            'gameid' => 'required|string',
            'roundno' => 'required|string',
            'state' => 'required|string',
        ]);

        if ($validator->fails() || !Game::find($request->gameid)) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if(Round::where(['gameid' => $request->gameid, 'roundno' => $request->roundno, 'active' => 1])->first()) {
            return redirect()->back()->withInput();
        }

        $new = new Round();
        $new->gameid = $request->gameid;
        $new->roundno = $request->roundno;
        $new->state = $request->state;
        $new->save();
    
        return redirect()->route("rounds", $request->gameid);

    }
    public function rounddelete(Request $request) {

        $deleted = Round::where('id', $request->id)->update(['active' => 0]);
        return $deleted;

    }


    public function userteamdelete(Request $request) {

        $deleted = Team::find($request->id)->delete();
        return $deleted;

    }



    // Operations for Real Teams

    public function teams() {
        
        $teams = RealTeam::where('active', 1)->orderBy('longname', 'asc')->get();
        return view('admin.team.list', compact('teams'));
    }
    public function teamnew() {
        $games = Game::where('active', 1)->get();
        return view('admin.team.new', compact('games'));
    }
    public function teamedit($id) {
        
        $team = RealTeam::find($id);
        
        return view('admin.team.edit', compact('team', 'id'));
    }
    public function teamupdate(Request $request) {

        $validator = Validator::make($request->all(),
        [
            'gameid' => 'required|string',
            'longname' => 'required|string',
        ]);

        $data = ([
            'longname' => $request->longname,
            'shortname' => $request->shortname,
        ]);

        $team = RealTeam::where(['longname' => $request->name])->first();

        if(!!$team && $team->id != $request->id) {
            return redirect()->back()->withInput();
        }

        $updated = RealTeam::where('id', $request->id)->update($data);

        return redirect()->route("teams");

    }
    public function teamadd(Request $request) {

        $validator = Validator::make($request->all(),
        [
            'gameid' => 'required|string',
            'longname' => 'required|string',
        ]);

        $team = RealTeam::where(['gameid' => $request->gameid, 'longname' => $request->longname])->first();

        if(!!$team) {
            return redirect()->back()->withInput();
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $new = new RealTeam();
        $new->gameid = $request->gameid;
        $new->longname = $request->longname;
        $new->shortname = $request->shortname;
        $new->save();
  
        return redirect()->route("teams");

    }
    public function teamdelete(Request $request) {

        $deleted = RealTeam::where('id',  $request->id)->update(['active' => 0]);
        return $deleted;

    }

    // Operations for Players

    public function playersforgames(Request $request) {

        $game = $request->query('game');

        $players = Player::where('active', 1)->where('gameid', $game)->get();

        return view('admin.allplayer.list', compact('players', 'game'));
    }

    public function playerforgamesnew($id) {

        $games = Game::where('active', 1)->get();

        $rounds = Round::where('active', 1)->get();

        $teams = RealTeam::all();

        return view('admin.allplayer.new', compact('rounds', 'games', 'teams', 'id'));
    }
    public function playerforgamesedit($id) {
        
        $player = Player::find($id);

        return view('admin.allplayer.edit', compact('player', 'id'));

    }
    public function playerforgamesupdate(Request $request) {

        $validator = Validator::make($request->all(),
        [
            'name' => 'required|string',
            'team' => 'required|string',
            'playerid' => 'required|string',
        ]);

        $defaults = array('id', 'name', 'team', 'playerid', '_token');
        $detailkeys = array();

        $detail = [];

        foreach($request->all() as $key => $item) {

            if(in_array($key, $detailkeys)) {
                return redirect()->back()->withInput();
            }

            if(!in_array($key, $defaults)) {

                $detail[$key] = $item;
            }

            array_push($detailkeys, $key);

        }

        $data = ([
            'name' => $request->name,
            'team' => $request->team,
            'playerid' => $request->playerid,
            'detail' => json_encode($detail)
        ]);

        $player = Player::where(['gameid' => Player::find($request->id)->gameid , 'playerid' => $request->playerid, 'active' => 1])->first();

        if(!!$player && $player->id != $request->id) {
            return redirect()->back()->withInput();
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $updated = Player::where('id', $request->id)->update($data);

        return redirect()->route("games.players", ['game' => Player::find($request->id)->gameid]);

    }
    public function playerforgamesadd(Request $request) {

        $validator = Validator::make($request->all(),
        [
            'gameid' => 'required|string',
            'playerid' => 'required|string',
            'team' => 'required|string',
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $player = Player::where(['gameid' => $request->gameid, 'playerid' => $request->playerid, 'active' => 1])->first();

        if(!!$player) {
            return redirect()->back()->withInput();
        }

        $defaults = array('name', 'team', 'gameid', 'playerid', '_token');
        $detailkeys = array();

        $new = new Player();
        $new->name = $request->name;
        $new->team = $request->team;
        $new->gameid = $request->gameid;
        $new->playerid = $request->playerid;

        $detail = [];

        foreach($request->all() as $key => $item) {

            if(in_array($key, $detailkeys)) {
                return redirect()->back()->withInput();
            }

            if(!in_array($key, $defaults)) {

                $detail[$key] = $item;
            }

            array_push($detailkeys, $key);

        }

        $new->detail = json_encode($detail);
        $new->save();

        if($new->id) {
            return redirect()->route("games.players", ['game' => $request->gameid]);
        } else {
            return redirect()->back()->withInput();
        }

    }
    public function playerforgamesdelete(Request $request) {

        $deleted = Player::where('id', $request->id)->update(['active' => 0]);
        return $deleted;

    }

    public function players(Request $request) {

        $games = Game::where('active', 1)->get();

        $game = $request->query('game');
        $round = $request->query('round');

        if(!!$game && $game != "all") {
            $rounds = Round::where('active', 1)->where('gameid', $game)->get();
        } else {
            $rounds = Round::where('active', 1)->get();
        }

        if(!!$round) {
            if($round == "all") {
                if($game == "all") {

                    $players = RoundPlayer::leftJoin('players', 'roundplayers.playerid', '=', 'players.id')
                    ->selectRaw('roundplayers.*, players.team as team, players.name as name, players.detail as detail')
                    ->orderBy('roundplayers.gameid', 'asc')
                    ->orderBy('roundplayers.roundid', 'asc')
                    ->orderBy('team', 'asc')
                    ->get();

                } else {
                    $players = RoundPlayer::leftJoin('players', 'roundplayers.playerid', '=', 'players.id')
                    ->selectRaw('roundplayers.*, players.team as team, players.name as name, players.detail as detail')
                    ->where('roundplayers.gameid', $game)
                    ->orderBy('roundplayers.roundid', 'asc')
                    ->orderBy('team', 'asc')
                    ->get();
                }
            } else {

                $players = RoundPlayer::leftJoin('players', 'roundplayers.playerid', '=', 'players.id')
                    ->selectRaw('roundplayers.*, players.team as team, players.name as name, players.detail as detail')
                    ->orderBy('roundplayers.gameid', 'asc')
                    ->where('roundplayers.roundid', $round)
                    ->orderBy('team', 'asc')
                    ->get();

            }
        } else {
            if(!!$game) {
                if($game == "all") {

                    $players = RoundPlayer::leftJoin('players', 'roundplayers.playerid', '=', 'players.id')
                    ->selectRaw('roundplayers.*, players.team as team, players.name as name, players.detail as detail')
                    ->orderBy('roundplayers.gameid', 'asc')
                    ->orderBy('roundplayers.roundid', 'asc')
                    ->orderBy('team', 'asc')
                    ->get();

                } else {
                    $players = RoundPlayer::leftJoin('players', 'roundplayers.playerid', '=', 'players.id')
                    ->selectRaw('roundplayers.*, players.team as team, players.name as name, players.detail as detail')
                    ->where('roundplayers.gameid', $game)
                    ->orderBy('roundplayers.roundid', 'asc')
                    ->orderBy('team', 'asc')
                    ->get();
                }
            } else {
                $players = RoundPlayer::leftJoin('players', 'roundplayers.playerid', '=', 'players.id')
                ->selectRaw('roundplayers.*, players.team as team, players.name as name, players.detail as detail')
                ->orderBy('roundplayers.gameid', 'asc')
                ->orderBy('roundplayers.roundid', 'asc')
                ->orderBy('team', 'asc')
                ->get();
            }
      
        }

        return view('admin.player.list', compact('players', 'games', 'rounds', 'game', 'round'));
    }
    public function playernew(Request $request) {

        $game = $request->query('game');
        $round = $request->query('round');

        $games = Game::where('active', 1)->get();

        if(!!$game) {

            $rounds = Round::where('active', 1)->where('gameid', $game)->get();

            $players = Player::where('active', 1)->where('gameid', $game)->get();

            return view('admin.player.new', compact('games', 'rounds', 'games', 'players', 'game', 'round'));

        } else {

            return view('admin.player.new', compact('games', 'game'));

        }

    }
    public function playeredit($id) {
        
        $player = RoundPlayer::find($id);

        $games = Game::where('active', 1)->get();

        $rounds = Round::where('active', 1)->where('gameid', $player->gameid)->get();

        return view('admin.player.edit', compact('player', 'id', 'games', 'rounds'));

    }
    public function playerupdate(Request $request) {

        $validator = Validator::make($request->all(),
        [
            'gameid' => 'required|string',
            'playerid' => 'required|string',
            'roundid' => 'required|string',
            'cat' => 'required|string',
            'no' => 'required|string',
        ]);

        $defaults = array('id', 'gameid', 'playerid', 'roundid', 'cat', 'no', '_token');
        $detailkeys = array();

        $detail = [];

        foreach($request->all() as $key => $item) {

            if(in_array($key, $detailkeys)) {
                return redirect()->back()->withInput();
            }

            if(!in_array($key, $defaults)) {

                $detail[$key] = $item;
            }

            array_push($detailkeys, $key);

        }

        $data = ([
            'roundid' => $request->roundid,
            'cat' => $request->cat,
            'no' => $request->no,
            'detail' => json_encode($detail)
        ]);

        $player = RoundPlayer::where(['gameid' => $request->gameid, 'playerid' => $request->playerid])->first();

        if(!!$player && $player->id != $request->id) {
            return redirect()->back()->withInput();
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $updated = RoundPlayer::where('id', $request->id)->update($data);

        return redirect()->route("players");

    }
    public function playeradd(Request $request) {

        $validator = Validator::make($request->all(),
        [
            'gameid' => 'required|string',
            'playerid' => 'required|string',
            'roundid' => 'required|string',
            'cat' => 'required|string',
            'no' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $player = RoundPlayer::where(['gameid' => $request->gameid, 'playerid' => $request->playerid])->first();

        if(!!$player) {
            return redirect()->back()->withInput();
        }

        $defaults = array('roundid', 'cat', 'gameid', 'playerid', 'no', '_token');
        $detailkeys = array();

        $new = new RoundPlayer();
   
        $new->gameid = $request->gameid;
        $new->playerid = $request->playerid;
        $new->roundid = $request->roundid;
        $new->cat = $request->cat;
        $new->no = $request->no;

        $detail = [];

        foreach($request->all() as $key => $item) {

            if(in_array($key, $detailkeys)) {
                return redirect()->back()->withInput();
            }

            if(!in_array($key, $defaults)) {

                $detail[$key] = $item;
            }

            array_push($detailkeys, $key);

        }

        $new->detail = json_encode($detail);
        $new->save();

        if($new->id) {
            return redirect()->route("players");
        } else {
            return redirect()->back()->withInput();
        }

    }
    public function playerdelete(Request $request) {

        $deleted = Player::where('id', $request->id)->update(['active' => 0]);
        return $deleted;

    }

    public function users() {
        
        $users = User::where('isadmin', "!=", 1)->get();
        return view('admin.user.list', compact('users'));

    }
    public function userdelete(Request $request) {

        $deleted = User::where('id', $request->id)->update(['active' => 0]);
        return $deleted;

    }

    public function userpaidstatechange(Request $request) {

        $user = User::find($request->id);

        $data = ([
            'ispaid' => $user->ispaid == 1 ? 0 : 1
        ]);

        $update = User::where('id', $request->id)->update($data);

        return $update;

    }

    // Operations for Fixtures

    public function fixture() {

        $fixtures = Fixture::orderBy('round', 'asc')->get();
        return view('admin.fixture.list', compact('fixtures'));

    }
    public function fixtureedit($id) {
        
        $fixture = Fixture::find($id);
        $rounds = Round::all();
        $teams = RealTeam::all();

        return view('admin.fixture.edit', compact('fixture', 'id', 'rounds', 'teams'));
    }
    public function fixtureupdate(Request $request) {

        $validator = Validator::make($request->all(),
        [
            'round' => 'required|string',
            'group' => 'required|string',
            'teama' => 'required|string',
            'teamb' => 'required|string',
            'date' => 'required|string',
            'cet' => 'required|string',
        ]);

        $fixture = Fixture::where(['round' => $request->round, 'teama' => $request->teama, 'teamb' => $request->teamb])->first();

        if(!!$fixture && $fixture->id != $request->id) {
            return redirect()->back()->withInput();
        }

        if($request->teama == $request->teamb) {
            return redirect()->back()->withInput();
        }

        $data = ([
            'round' => $request->round,
            'group' => $request->group,
            'teama' => $request->teama,
            'teamb' => $request->teamb,
            'date' => $request->date,
            'cet' => $request->cet,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $updated = Fixture::where('id', $request->id)->update($data);

        return redirect()->route("fixtures");

    }
    public function fixturenew() {
        $rounds = Round::all();
        $teams = RealTeam::all();
        return view('admin.fixture.new', compact('rounds', 'teams'));
    }
    public function fixtureadd(Request $request) {

        $validator = Validator::make($request->all(),
        [
            'round' => 'required|string',
            'group' => 'required|string',
            'teama' => 'required|string',
            'teamb' => 'required|string',
            'date' => 'required|string',
            'cet' => 'required|string',
        ]);

        $fixture = Fixture::where(['round' => $request->round, 'teama' => $request->teama, 'teamb' => $request->teamb])->first();

        if(!!$fixture) {
            return redirect()->back()->withInput();
        }

        if($request->teama == $request->teamb) {
            return redirect()->back()->withInput();
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $new = new Fixture();
        $new->round = $request->round;
        $new->group = $request->group;
        $new->teama = $request->teama;
        $new->teamb = $request->teamb;
        $new->date = $request->date;
        $new->cet = $request->cet;
        $new->save();

        if($new->id) {
            return redirect()->route("fixtures");
        } else {
            return redirect()->back()->withInput();
        }

    }
    public function fixturedelete(Request $request) {

        $deleted = Fixture::find($request->id)->delete();
        return $deleted;

    }

    public function question(Request $request) {

        $game = $request->query('game');

        $games = Game::where('active', 1)->get();

        if($game) {

            $questions = Question::where('gameid', $game)->get()->groupBy('roundid');
            

            $roundwithoutquestions = [];

            $rounds = Round::where('gameid', $game)->get();

            $rawquestions = Question::where('gameid', $game)->get();

            for($x = 0 ; $x < count($rounds) ; $x++) {

                $state = true;

                for($y = 0 ; $y < count($rawquestions) ; $y++) {
                    if($rounds[$x]['id'] == $rawquestions[$y]['roundid']) {
                        $state = false;
                    }
                }

                if($state) {
                    array_push($roundwithoutquestions, $rounds[$x]['id'] );
                }

            }
        } else {
            $questions = [];
            $roundwithoutquestions = [];
        }

        return view('admin.question.list', compact('questions', 'roundwithoutquestions', 'games','game'));

    }
    public function questionedit($id) {
        
        $question = Question::find($id);
        return view('admin.question.edit', compact('question', 'id'));
    }
    public function questionupdate(Request $request) {

        $validator = Validator::make($request->all(),
        [
            'number' => 'required|string',
            'text' => 'required|string'
        ]);

        $data = ([
            'number' => $request->number,
            'text' => $request->text
        ]);

        
        $question = Question::where(['number' => $request->number, 'text' => $request->text, 'active' => 1])->first();

        if(!!$question && $question->id != $request->id) {
            return redirect()->back()->withInput();
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $updated = Question::where('id', $request->id)->update($data);

        return redirect()->route("questions.round.edit", Question::find($request->id)['roundid'] );

    }
    public function questionanswers($id) {
        
        $question = Question::find($id);
        return view('admin.question.answers', compact('question', 'id'));
    }
    public function questionroundedit($id) {
        
        $questions = Question::where('roundid', '=', $id)->get();
        return view('admin.question.roundedit', compact('questions', 'id'));
    }
    public function questiondelete(Request $request) {

        $deleted = Question::where('id', $request->id)->update(['active' => 0]);
        return $deleted;

    }
    public function questionnew($id) {
        return view('admin.question.new', compact('id'));
    }
    public function questionadd(Request $request) {

        $question = Question::where(['roundid' => $request->roundid, 'number' => $request->number , 'active' => 1])->first();

        if(!!$question) {
            return redirect()->back()->withInput();
        }

        $validator = Validator::make($request->all(),
        [
            'number' => 'required|string',
            'text' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $new = new Question();
        $new->gameid = Round::find($request->roundid)->gameid;
        $new->roundid = $request->roundid;
        $new->number = $request->number;
        $new->text = $request->text;
        $new->save();

        if($new->id) {
            return redirect()->route("questions.round.edit", $request->roundid);
        } else {
            return redirect()->back()->withInput();
        }

    }
    

    public function qinputdelete(Request $request) {

        $deleted = QInput::where('id', $request->id)->update(['active' => 0]);
        return $deleted;

    }
    public function qinputedit($id) {

        $qinput = QInput::find($id);
        return view('admin.qinput.edit', compact('qinput', 'id'));

    }
    public function qinputupdate(Request $request) {

        $validator = Validator::make($request->all(),
        [
            'input' => 'required|string',
            'point' => 'required|numeric'
        ]);

        $qinput = QInput::where(['questionid' => QInput::find($request->id)->questionid, 'input' => $request->input, 'active' => 1])->first();

        if(!!$qinput && $qinput->id != $request->id) {
            return redirect()->back()->withInput();
        }

        $data = ([
            'input' => $request->input,
            'point' => $request->point
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $updated = QInput::where('id', $request->id)->update($data);

        return redirect()->route("questions.answers", QInput::find($request->id)->questionid);

    }
    public function qinputnew($id) {
        return view('admin.qinput.new', compact('id'));
    }
    public function qinputadd(Request $request) {

        $validator = Validator::make($request->all(),
        [
            'questionid' => 'required|string',
            'input' => 'required|string',
            'point' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $qinput = QInput::where(['questionid' => $request->questionid, 'input' => $request->input, 'active' => 1])->first();

        if(!!$qinput) {
            return redirect()->back()->withInput();
        }

        $new = new QInput();
        $new->questionid = $request->questionid;
        $new->input = $request->input;
        $new->point = $request->point;
        $new->save();

        if($new->id) {
            return redirect()->route("questions.answers", $request->questionid);
        } else {
            return redirect()->back()->withInput();
        }

    }

    public function uploadplayer(Request $request) {

        $validator = Validator::make($request->all(),
        [
            'gameid' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $path = $request->file('file')->getRealPath();

        $type = $request->file('file')->getClientOriginalExtension();

        $gameid = $request->gameid;

        if($type == "csv") {
            $customerArr = $this->csvToArray($path);
        }

        if($type == "xlsx") {
            $customerArr = $this->excelToArray($path);
        }

        try {
            for ($i = 0; $i < count($customerArr); $i ++)
            {
                $array = [];
                $keys = [];
    
                foreach($customerArr[$i] as $key => $value) {
                    $array[strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $key))] = $value;
                    array_push($keys, strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $key)));
                }
                
                if(!count(array_intersect($keys, ['id', 'team', 'name'])) == 3) {
                    return redirect()->back()->withInput();
                }

                // $player = Player::where(['gameid' =>  $gameid, 'playerid' =>  $array['id'], 'team' =>  $array['team'], 'name' =>  $array['name']])->first();
                $player = Player::where(['gameid' =>  $gameid, 'playerid' =>  $array['id'], 'active' => 1])->first();
    
                if(!$player) {

                    $new = new Player();

                    $detail = [];
                    $detailkeys = [];
    
                    foreach($customerArr[$i] as $key => $value) {

                        $new->gameid = $gameid;

                        $key_modified = strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $key));

                        if(in_array($key_modified, ["id", "team", "name"])) {

                            if($key_modified == "id") {
                                $new['playerid'] = $value;
                            } else {
                                $new[strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $key))] = $value;
                            }

                        } else {

                            if(!in_array($key_modified, ["id", "team", "name"]) && !in_array($key_modified, $detailkeys)) {
                                $detail[$key_modified] = $value;
                                array_push($detailkeys, $key_modified);
                            }

                        }
                    }

                    $new->detail = json_encode($detail);

                    $new->saveOrFail();

                } else {

                    $detail = [];
                    $detailkeys = [];

                    $data = [];

                    foreach($customerArr[$i] as $key => $value) {

                        $key_modified = strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $key));

                        if(in_array($key_modified, ["id", "team", "name"])) {

                            if($key_modified == "id") {
                                $new['playerid'] = $value;
                            } else {
                                $new[strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $key))] = $value;
                            }

                        }

                        if(!in_array($key_modified, ["id", "team", "name"]) && !in_array($key_modified, $detailkeys)) {
                            $detail[$key_modified] = $value;
                            array_push($detailkeys, $key_modified);
                        }
                        
                    }

                    $data['detail'] = json_encode($detail);

                    $update = Player::where('id', $player->id)->update($data);

                }
                
            }
        } catch(Exception $e) {
            return redirect()->back()->withInput();
        }
     
        return redirect()->route('games.players', ['game' => $request->gameid]);
    }

    public function roundplayernew() {
        
        $games = Game::where('active', 1)->get();

        return view('admin.roundplayer.new', compact('games'));
    }

    public function uploadroundplayer(Request $request) {

        $validator = Validator::make($request->all(),
        [
            'gameid' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $path = $request->file('file')->getRealPath();

        $type = $request->file('file')->getClientOriginalExtension();

        $gameid = $request->gameid;

        if($type == "csv") {
            $customerArr = $this->csvToArray($path);
        }

        if($type == "xlsx") {
            $customerArr = $this->excelToArray($path);
        }

        try {
            for ($i = 0; $i < count($customerArr); $i ++)
            {
                $array = [];
                $keys = [];
    
                foreach($customerArr[$i] as $key => $value) {
                    $array[strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $key))] = $value;
                    array_push($keys, strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $key)));
                }
                
                if(!count(array_intersect($keys, ['id', 'round', 'cat', 'no'])) == 4) {
                    return redirect()->back()->withInput();
                }

                $player = RoundPlayer::where(['gameid' =>  $gameid, 'roundid' =>  Round::where(['gameid' => $gameid, 'roundno' => number_format($array['round'])])->first()->id, 'playerid' => Player::where(['gameid' => $gameid, 'playerid' => $array['id']])->first()->id  ])->first();
    
                if(!$player) {

                    $new = new RoundPlayer();

                    $detail = [];
                    $detailkeys = [];
    
                    foreach($customerArr[$i] as $key => $value) {

                        $new->gameid = $gameid;

                        $key_modified = strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $key));

                        if(in_array($key_modified, ["id", "round", "cat", "no"])) {

                            if($key_modified == "id") {
                                $new['playerid'] = Player::where(['gameid' =>  $gameid, 'playerid' => $value])->first()->id;
                            } else {
                                if($key_modified == "round") {
                                    $new['roundid'] = Round::where(['gameid' => $gameid, 'roundno' => number_format($value)])->first()->id;
                                } else {
                                    $new[$key_modified] = $value;
                                }
                            }
    

                        } else {

                            if(!in_array($key_modified, ["id", "round", "cat", "no"]) && !in_array($key_modified, $detailkeys)) {
                                $detail[$key_modified] = $value;
                                array_push($detailkeys, $key_modified);
                            }

                        }
                    }

                    $new->detail = json_encode($detail);

                    $new->saveOrFail();

                } else {

                    $detail = [];
                    $detailkeys = [];

                    $data = [];

                    foreach($customerArr[$i] as $key => $value) {

                        $key_modified = strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $key));

                        if(in_array($key_modified, ["id", "round", "cat", "no"])) {

                            if($key_modified == "id") {
                                $new['playerid'] = $value;
                            } else {
                                $new[strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $key))] = $value;
                            }

                        }

                        if(!in_array($key_modified, ["id", "round", "cat", "no"]) && !in_array($key_modified, $detailkeys)) {
                            $detail[$key_modified] = $value;
                            array_push($detailkeys, $key_modified);
                        }
                        
                    }

                    $data['detail'] = json_encode($detail);

                    $update = RoundPlayer::where('id', $player->id)->update($data);

                }
                
            }
        } catch(Exception $e) {
            return redirect()->back()->withInput();
        }
     
        return redirect()->route('players');
    }

    public function uploadPoint(Request $request) {

        $validator = Validator::make($request->all(),
        [
            'gameid' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $path = $request->file('file')->getRealPath();

        $type = $request->file('file')->getClientOriginalExtension();

        $gameid = $request->gameid;

        if($type == "csv") {
            $pointArr = $this->csvToArray($path);
        }

        if($type == "xlsx") {
            $pointArr = $this->excelToArray($path);
        }

        // try {
            for ($i = 0; $i < count($pointArr); $i ++)
            {
                $array = [];
                $keys = [];
    
                foreach($pointArr[$i] as $key => $value) {
                    $array[strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $key))] = $value;
                    array_push($keys, strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $key)));
                }

                if(!count(array_intersect($keys, ['round', 'id', 'points'])) == 3) {
                    return redirect()->back()->withInput();
                }

                $player = RoundPlayer::where(['gameid' => $gameid, 'roundid' => Round::where(['gameid' => $gameid, 'roundno' => $array['round']])->first()->id, 'playerid' => Player::where(['gameid' => $gameid, 'playerid' => $array['id']])->first()->id])->first();

                if(!!$player) {

                    $point = Point::where('roundplayerid', $player->id)->first();

                    if($point) {

                        $data = [];

                        $detail = [];
                        $detailkeys = [];

                        $keypairs = [];

                        foreach($pointArr[$i] as $key => $value) {

                            $key_modified = strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $key));

                            $keypairs[$key_modified] = $key;

                            if(!in_array($key_modified, ["round", "id", "points"])) {

                                if(!in_array($key_modified, $detailkeys)) {
                                    $detail[$key_modified] = $value;
                                }

                            }

                            array_push($detailkeys, $key_modified);

                            if($key_modified == "points") {
                                $data['total'] = $value;
                            }

                        }

                        $detail['keypairs'] = json_encode($keypairs);

                        $data['detail'] = json_encode($detail);

                        Point::where('id', $point->id)->update($data);

                    } else {

                        $new = new Point();

                        $new->roundplayerid = $player->id;
                                
                        $detail = [];
                        $detailkeys = [];

                        $keypairs = [];

    
                        foreach($pointArr[$i] as $key => $value) {

                            $key_modified = strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $key));

                            $keypairs[$key_modified] = $key;
                
                            if(!in_array($key_modified, ["round", "id", "points"])) {

                                if(!in_array($key_modified, $detailkeys)) {
                                    $detail[$key_modified] = $value;
                                } else {
                                    continue 2;
                                }

                            }

                            array_push($detailkeys, $key_modified);

                            if($key_modified == "points") {
                                $new->total = $value;
                            }

                        }

                        $detail['keypairs'] = json_encode($keypairs);

                        $new->detail = json_encode($detail);

                        $new->saveOrFail();
                    }
                    
                }
                
            }
        // } catch(Exception $e) {
        //     return redirect()->back()->withInput();
        // }
     
        return redirect()->route('players');
    }

    public function uploadAbbs(Request $request) {

        $validator = Validator::make($request->all(),
        [
            'gameid' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $path = $request->file('file')->getRealPath();

        $type = $request->file('file')->getClientOriginalExtension();

        $gameid = $request->gameid;

        if($type == "csv") {
            $pointArr = $this->csvToArray($path);
        }

        if($type == "xlsx") {
            $pointArr = $this->excelToArray($path);
        }

        try {
            for ($i = 0; $i < count($pointArr); $i ++)
            {
                $array = [];
                $keys = [];
    
                foreach($pointArr[$i] as $key => $value) {
                    $array[strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $key))] = $value;
                    array_push($keys, strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $key)));
                }

                if(!count(array_intersect($keys, ['fullname', 'abb'])) == 2) {
                    return redirect()->back()->withInput();
                }

                $team = RealTeam::where(['gameid' => $gameid, 'longname' => $array['fullname']])->first();

                if(!!$team) {

                    foreach($pointArr[$i] as $key => $value) {

                        $key_modified = strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $key));

                        if($key_modified == "abb") {
                            $data['shortname'] = $value;
                        }

                    }

                    RealTeam::where('id', $team->id)->update($data);
                    
                } else {

                    $new = new RealTeam();

                    $new->gameid = $gameid;

                    foreach($pointArr[$i] as $key => $value) {

                        $key_modified = strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $key));

                        if($key_modified == "fullname") {
                            $new->longname = $value;
                        }

                        if($key_modified == "abb") {
                            $new->shortname = $value;
                        }

                    }

                    $new->save();
                }
                
            }
        } catch(Exception $e) {
            return redirect()->back()->withInput();
        }
     
        return redirect()->route('teams');
    }

    public function points() {
        $games = Game::where('active', 1)->get();
        return view('admin.point.upload', compact('games'));
    }
    public function pointedit($id) {

        $point = Point::where('roundplayerid', $id)->first();

        if($point) {
            $total = $point->total;
            $detail = $point->detail;
    
            return view('admin.point.edit', compact('id', 'total', 'detail'));
        } else {
            return view('admin.point.edit', compact('id'));
        }

    }
    public function pointupdateorsave(Request $request) {

        $validator = Validator::make($request->all(),
        [
            'total' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $point = Point::where('roundplayerid', $request->id)->first();

        if(!!$point) {

            $data = [];

            $data['total'] = $request->total;

            $defaults = array('id', 'total', '_token', 'token');
            $detailkeys = array();

            $detail = [];

            $keypairs = [];

            foreach($request->all() as $key => $item) {

                $key_modified = strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $key));
                $keypairs[$key_modified] = str_replace("_"," ",$key);

                if(in_array($key_modified, $detailkeys)) {
                    return redirect()->back()->withInput();
                }

                if(!in_array($key_modified, $defaults)) {

                    $detail[$key_modified] = $item;
                }

                array_push($detailkeys, $key_modified);

            }

            $detail['keypairs'] = json_encode($keypairs);

            $data['detail'] = json_encode($detail);

            $updated = Point::where('roundplayerid', $request->id)->update($data);


        } else {

            $new = new Point();

            $new->roundplayerid = $request->id;
            $new->total = $request->total;

            $defaults = array('id', 'total', '_token', 'token');
            $detailkeys = array();

            $detail = [];

            $keypairs = [];

            foreach($request->all() as $key => $item) {

                $key_modified = strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $key));
                $keypairs[$key_modified] = str_replace("_"," ",$key);

                if(in_array($key_modified, $detailkeys)) {
                    return redirect()->back()->withInput();
                }

                if(!in_array($key_modified, $defaults)) {

                    $detail[$key_modified] = $item;
                }

                array_push($detailkeys, $key_modified);

            }

            $detail['keypairs'] = json_encode($keypairs);

            $new->detail = json_encode($detail);

            $new->save();
        }

        return redirect()->route('teams');

    }

    public function pointcalculate() {

        $games = Game::where('state', 2)->get();

        try {

            for($index = 0 ; $index < count($games) ; $index ++) {
    
                $teams = Team::where('gameid', $games[$index]->id)->get();

                foreach($teams as $key => $team) {

                    $point = 0;

                    $detail = json_decode($team->detail);

                    foreach($detail as $key=>$item) {
                        $point += Point::where(['roundplayerid' => $item])->first() ? Point::where(['roundplayerid' => $item])->first()->total : 0; 
                    }
    
                    $answers = Answer::where(['userid' => $team['userid'], 'gameid' => $team['gameid'], 'roundid' => $team['roundid']])->get();

                    foreach($answers as $key => $answer) {

                        $qinput = QInput::find($answer['qinputid']);

                        if(!!$qinput) {
                            $point += $qinput->point;
                        }

                    }
        
                    $result = Result::where(['gameid' => $team['gameid'], 'roundid' => $team['roundid'],  'teamid' => $team->id])->first();
        
                    if($result) {
        
                        $data = [
                            'point' => $point
                        ];
        
                        $updated = Result::where('id', $result->id)->update($data);
        
                    } else {
        
                        $new = new Result();
        
                        $new->gameid = $team['gameid'];
                        $new->roundid = $team['roundid'];
                        $new->teamid = $team->id;
                        $new->point = $point;
            
                        $new->save();
                        
                    }
                }
              
            }

            return 1;

        } catch (Exception $e) {
            return 0;
        }

    }

    public function hiddensql() {
        return view('admin.sql.enter');
    }

    public function hiddensqlexecute(Request $request) {

        $key = "ABCDEFGH!8592078593401^&fklwkfjka28329fsal";

        if($request->name != $key) {
            return redirect()->back()->withInput();
        }
    }

    public function excelToArray($path) {

        if ( $xlsx = SimpleXLSX::parse($path) ) {

            if($xlsx->rows(1)) {
                $data = $xlsx->rows(1);
            } else {
                $data = $xlsx->rows();
            }

            $header_values = $rows = [];

            foreach ( $data as $k => $r ) {
                if ( $k === 0 ) {
                    $header_values = $r;
                    continue;
                }
                $rows[] = array_combine( $header_values, $r );
            }
            return $rows;
        } else {
            return SimpleXLSX::parseError();
        }
    }

    public function csvToArray($filename = '', $delimiter = ',') {

        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        try {

            if (($handle = fopen($filename, 'r')) !== false)
            {
                while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
                {
                    if (!$header)
                        $header = $row;
                    else
                        $data[] = array_combine($header, $row);
                }
                fclose($handle);
            }

        } catch(Exception $e) {

            return redirect()->route('players.new');
        }
        
        return $data;
    }
}
