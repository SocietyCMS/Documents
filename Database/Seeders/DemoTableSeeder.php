<?php

namespace Modules\Documents\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Core\Traits\Factory\useFactories;
use Modules\User\Entities\Entrust\EloquentRole;
use Modules\User\Entities\Entrust\EloquentUser;

class DemoTableSeeder extends Seeder
{
    use useFactories;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('documents__objects')->delete();
        DB::table('documents__pool')->delete();
    }
}
