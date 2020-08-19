<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\administrator;
use Illuminate\Http\Request;
use Auth;
use View;
use Storage;
use App\User;

class DashboardController extends Controller
{
    public function dashboard(){
        
          
        if (Auth::check() || Auth::viaRemember()) {
    	    
            return redirect(route( 'admin.dashboard' ));
        }else{

          return redirect()->guest(route( 'admin.login' ));

        }

        
    }

    public function totalUser(){
       
          $users = DB::table('users')
		            ->select(DB::raw('count(*)'))
		            ->get();
		            print_r($users);dd();

    }
}
