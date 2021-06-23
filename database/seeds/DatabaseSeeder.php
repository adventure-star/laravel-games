<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Type\Time;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);


        $users = 
        [
            ["id"=>1, "fullname"=>"Admin", "username"=>"admin", "displayname"=>"admin", "email"=>"admin@email.com", "password"=>"$2y$10\$oXzrsnaXh3ovVdrIB2OAz.eYu/ToqjPDNw1hY2rUOdT2kVepyJ1qO", "ismarketing"=>0, "isadmin"=>1, "ispaid"=>0],
            ["id"=>2, "fullname"=>"John Michael", "username"=>"mancity", "displayname"=>"John Hunter", "email"=>"john@email.com", "password"=>"$2y$10\$oXzrsnaXh3ovVdrIB2OAz.eYu/ToqjPDNw1hY2rUOdT2kVepyJ1qO", "ismarketing"=>0, "isadmin"=>0, "ispaid"=>1],
        ];

        $games = 
        [
            ["id"=>1, "name"=>"Tour de France", "state"=>1, "deadline"=>"June 14", "active"=>1],
            ["id"=>2, "name"=>"Tour de Germany", "state"=>1, "deadline"=>"June 14", "active"=>1],
            ["id"=>3, "name"=>"Tour de Italy", "state"=>2, "deadline"=>"June 14", "active"=>1],
            ["id"=>4, "name"=>"Tour de London", "state"=>2, "deadline"=>"June 14", "active"=>1],
            ["id"=>5, "name"=>"Tour de Wales", "state"=>0, "deadline"=>"June 14", "active"=>1],
            ["id"=>6, "name"=>"Tour de Edinburgh", "state"=>0, "deadline"=>"June 14", "active"=>1],
        ];

        $rounds = 
        [
            ["id"=>1, "gameid"=>1, "roundno"=>1, "state"=>1, "active"=>1],
            ["id"=>2, "gameid"=>1, "roundno"=>2, "state"=>1, "active"=>1],
        ];

        DB::table('users')->insert($users);
        DB::table('games')->insert($games);
        DB::table('rounds')->insert($rounds);

    }
}
