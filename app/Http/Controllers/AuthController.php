<?php
namespace App\Http\Controllers;
use App\Models\User; use Illuminate\Http\Request; use Illuminate\Support\Facades\Auth; use Illuminate\Support\Facades\Hash;
class AuthController extends Controller {
 public function create(){return view('auth.register');}
 public function store(Request $r){$data=$r->validate(['name'=>'required|string|max:80','username'=>'required|alpha_dash|max:40|unique:users','email'=>'required|email|unique:users','password'=>'required|min:8|confirmed','role'=>'required|in:fan,creator']);$user=User::create([...$data,'password'=>Hash::make($data['password'])]); if($user->role==='creator')$user->creatorProfile()->create(['display_name'=>$user->name]);Auth::login($user);return redirect()->route('dashboard')->with('success','Welcome to EmberFans.');}
 public function login(){return view('auth.login');}
 public function authenticate(Request $r){$data=$r->validate(['email'=>'required|email','password'=>'required']);if(!Auth::attempt($data,$r->boolean('remember')))return back()->withErrors(['email'=>'Those credentials do not match our records.'])->onlyInput('email');$r->session()->regenerate();return redirect()->intended(route('dashboard'));}
 public function destroy(Request $r){Auth::logout();$r->session()->invalidate();$r->session()->regenerateToken();return redirect()->route('home');}
}
