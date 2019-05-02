<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller {
	
	public function __construct() {
		$this->middleware('auth', [
			'except' => ['show', 'create', 'store', 'index', 'confirmEmail']
		]);
		
		$this->middleware('guest', [
			'only' => ['create']
		]);
	}
	
	public function index() {
		$users = User::paginate(10);
		return view('users.index', compact('users'));
	}
	
	public function create() {
		return view('users.create');
	}
	
	public function show(User $user) {
		return view('users.show', compact('user'));
	}
	
	/**
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function store(Request $request) {
		$this->validate($request, [
			'name' => 'required|max:50',
			'email' => 'required|email|unique:users|max:255',
			'password' => 'required|confirmed|min:2'
		]);
		
		$user = User::create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => bcrypt($request->password),
		]);
		
		$this->sendEmailConfirmationTo($user);
		session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收');
		return redirect('/');
	}
	
	protected function sendEmailConfirmationTo($user){
		$view = 'emails.confirm';
		$data = compact('user');
		$name = 'administrator';
		$from = '111@qq.com';
		$to = $user->email;
		$subject = "感谢注册";
		
		Mail::send($view, $data, function($message) use ($from, $name, $to, $subject) {
			$message->from($from, $name)->to($to)->subject($subject);
		});
 	}
	
	/**
	 * @param User $user
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function edit(User $user) {
		$this->authorize('update', $user);
		return view('users.edit', compact('user'));
	}
	
	/**
	 * @param User    $user
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function update(User $user, Request $request) {
		$this->authorize('update', $user);
		$this->validate($request, [
			'name' => 'required|max:50',
			'password' => 'nullable|confirmed|min:2'
		]);
		
		$data = [];
		$data['name'] = $request->name;
		if($request->password){
		    $data['password'] = bcrypt($request->password);
		}
		
		$user->update($data);
		
		session()->flash('success', '个人资料更新成功');
		return redirect()->route('users.show', $user);
	}
	
	/**
	 * @param User $user
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 * @throws \Exception
	 */
	public function destroy(User $user) {
		$this->authorize('destroy', $user);
		$user->delete();
		session()->flash('success', '删除用户!');
		return back();
	}
	
	/**
	 * @param $token
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function confirmEmail($token) {
		/* @var $user User */
		$user = User::where('activation_token', $token)->firstOrFail();
		
		$user->activated = true;
		$user->activation_token = null;
		$user->save();
		
		Auth::login($user);
		session()->flash('success', '激活成功');
		return redirect()->route('users.show', [$user]);
	}
}
