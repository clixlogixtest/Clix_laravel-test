<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Traits\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
	use Common;

    public function index()
    {
    	$countries=Country::all();
    	$user_meta=$this->getUserMetaData(Auth::user()->id);
  		return view('admin.auth.profile',compact('countries','user_meta'));
    }

    public function update(Request $request)
    {
        $user=Auth::user();

    	$request->validate([
			'first_name'=>'required',
            'last_name'=>'required',
            //'job_title'=>'required',
            'nationality'=>'required',
            'date_of_birth'=>'required',
            'town_of_birth'=>'required',
            'gender'=>'required',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users','email')->ignore($user)
            ],
        ]);

        $data=$request->all();

        if(isset($data['email']) && $user->email != $data['email'])
        {
            $user->email = $data['email'];
            $user->save();
        }
        
        if(isset($data['first_name']))
        {
            $this->addUserMeta($user->id,'first_name',$data['first_name']);
        }
        else
        {
            $this->addUserMeta($user->id,'first_name','');
        }

        if(isset($data['last_name']))
        {
            $this->addUserMeta($user->id,'last_name',$data['last_name']);
        }
        else
        {
            $this->addUserMeta($user->id,'last_name','');
        }

        if(isset($data['job_title']))
        {
            $this->addUserMeta($user->id,'job_title',$data['job_title']);
        }
        else
        {
            $this->addUserMeta($user->id,'job_title','');
        }

        if(isset($data['nationality']))
        {
            $this->addUserMeta($user->id,'nationality',$data['nationality']);
        }
        else
        {
            $this->addUserMeta($user->id,'nationality','');
        }

        if(isset($data['date_of_birth']))
        {
            $this->addUserMeta($user->id,'date_of_birth',$data['date_of_birth']);
        }
        else
        {
        	$this->addUserMeta($user->id,'date_of_birth','');
        }

        if(isset($data['town_of_birth']))
        {
            $this->addUserMeta($user->id,'town_of_birth',$data['town_of_birth']);
        }
        else
        {
            $this->addUserMeta($user->id,'town_of_birth','');
        }

        if(isset($data['gender']))
        {
            $this->addUserMeta($user->id,'gender',$data['gender']);
        }
        else
        {
        	$this->addUserMeta($user->id,'gender','');
        }

    	return back()->with('success', 'Profile successfully updated.');
    }
}
