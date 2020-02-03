<?php

namespace Illuminate\Foundation\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Registered;
use App\Modules\Property\Models\PropertiesModel;
use App\Modules\Property\Models\MasterPropertyLevelModel;
use App\Modules\Property\Models\MasterPropertyTypeModel;
use App\Modules\Property\Models\MasterPropertyBlockModel;

trait RegistersUsers
{
    use RedirectsUsers;

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {

        $blocks = MasterPropertyBlockModel::orderBy('property_block_sort','asc')->get();
        $types  = MasterPropertyTypeModel::orderBy('property_type_sort','asc')->where('property_type_id',1)->get();
        $levels = MasterPropertyLevelModel::orderBy('property_level_sort','asc')->get();

        return view('auth.register', ['blocks'=>$blocks, 'types'=>$types, 'levels'=>$levels]);

    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $properties = PropertiesModel::findOrFail($request->properties_id);
        $properties->properties_ownerid = $user->id;
        $properties->save();
        DB::table('user_has_roles')->insert(['role_id'=>'3','user_id'=>$user->id]);

        /*$this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());*/
        $successmsg = 'Your account has been registered and will be approved within 24 hours!';

        return Redirect()->back()->with(compact('successmsg'));
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        //
    }
}
