<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('index');
});
Route::get('login', function()
{
    return View::make('login');
});
Route::post('login', array('before' => 'csrf', function()
{
    $rules = array(
        'email'       => 'required|email',
        'password'    => 'required|min:6',
        'remember_me' => 'boolean',
    );
    $validator = Validator::make(Input::all(), $rules);
    if ($validator->passes())
    {
        if (Auth::attempt(array(
            'email'    => Input::get('email'),
            'password' => Input::get('password'),
            'block'    => 0), (boolean) Input::get('remember_me')))
        {
            return Redirect::intended('home');
        } else {
            return Redirect::to('login')->withInput()->with('message', array('type' => 'danger', 'content' => 'E-mail or password error'));
        }
    } else {
        return Redirect::to('login')->withInput()->withErrors($validator);
    }
}));

Route::get('home', array('before' => 'auth', function()
{
    return View::make('home');
}));
Route::get('logout', array('before' => 'auth', function()
{
    Auth::logout();
    return Redirect::to('/');
}));
//用户注册视图
Route::get('register', function()
{
    return View::make('users.create');
});
//实现用户注册
Route::post('register', array('before' => 'csrf', function()
{
    $rules = array(
        'email' => 'required|email|unique:users,email',
        'nickname' => 'required|min:4|unique:users,nickname',
        'password' => 'required|min:6|confirmed',
    );
    $validator = Validator::make(Input::all(), $rules);
    if ($validator->passes())
    {
        $user = User::create(Input::only('email', 'password', 'nickname'));
        $user->password = Hash::make(Input::get('password'));
        if ($user->save())
        {
            return Redirect::to('login')->with('message', array('type' => 'success', 'content' => 'Register successfully, please login'));
        } else {
            return Redirect::to('register')->withInput()->with('message', array('type' => 'danger', 'content' => 'Register failed'));
        }
    } else {
        return Redirect::to('register')->withInput()->withErrors($validator);
    }
}));
//用户编辑视图
Route::get('user/{id}/edit', array('before' => 'auth', 'as' => 'user.edit', function($id)
{
    if (Auth::user()->is_admin or Auth::id() == $id) {
        return View::make('users.edit')->with('user', User::find($id));
    } else {
        return Redirect::to('/');
    }
}));
//实现
Route::put('user/{id}', array('before' => 'auth|csrf', function($id)
{
    if (Auth::user()->is_admin or (Auth::id() == $id)) {
        $user = User::find($id);
        $rules = array(
            'password' => 'required_with:old_password|min:6|confirmed',
            'old_password' => 'min:6',
        );
        if (!(Input::get('nickname') == $user->nickname))
        {
            $rules['nickname'] = 'required|min:4||unique:users,nickname';
        }
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->passes())
        {
            if (!(Input::get('old_password') == '')) {
                if (!Hash::check(Input::get('old_password'), $user->password)) {
                    return Redirect::route('user.edit', $id)->with('user', $user)->with('message', array('type' => 'danger', 'content' => 'Old password error'));
                } else {
                    $user->password = Hash::make(Input::get('password'));
                }
            }
            $user->nickname = Input::get('nickname');
            $user->save();
            return Redirect::route('user.edit', $id)->with('user', $user)->with('message', array('type' => 'success', 'content' => 'Modify successfully'));
        } else {
            return Redirect::route('user.edit', $id)->withInput()->with('user', $user)->withErrors($validator);    
        }
    } else {
        return Redirect::to('/');
    }
}));
