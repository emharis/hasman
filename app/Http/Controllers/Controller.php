<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use App\Models\Menu as Menu;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    public function __construct() {
        $sidebar_collapse = \DB::table('appsetting')
                ->whereName('sidebar_collapse')
                ->first();
        \View::share('sidebar_collapse', $sidebar_collapse);

        $sidemenu = Menu::get();

        \View::share('sidemenu', $sidemenu);
    }
}
