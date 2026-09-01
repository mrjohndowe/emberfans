<?php
namespace App\Http\Controllers;
use App\Models\{CreatorProfile,Payment,Subscription,Tip,User}; use Illuminate\Http\Request;
class CreatorController extends Controller {
 public function index(){return view('creators.index',['creators'=>CreatorProfile::with('user')->latest()->paginate(12)]);}
 public function show(User $creator){abort_unless($creator->role==='creator',404);return view('creators.show',['creator'=>$creator,'profile'=>$creator->creatorProfile,'posts'=>$creator->posts()->whereNotNull('published_at')->latest('published_at')->paginate(12)]);}
 public function subscribe(Request $r,User $creator){abort_unless($creator->role==='creator',404);$provider=$r->validate(['provider'=>'required|in:stripe,paypal,manual'])['provider'];$amount=$creator->creatorProfile->monthly_price;Subscription::updateOrCreate(['subscriber_id'=>auth()->id(),'creator_id'=>$creator->id],['provider'=>$provider,'provider_reference'=>(string)str()->uuid(),'amount'=>$amount,'status'=>$provider==='manual'?'pending':'active','starts_at'=>now(),'ends_at'=>now()->addMonth()]);return back()->with('success',$provider==='manual'?'Membership request submitted.':'Membership is active.');}
 public function tip(Request $r,User $creator){$d=$r->validate(['amount'=>'required|numeric|min:1|max:10000','message'=>'nullable|string|max:1000','provider'=>'required|in:stripe,paypal,manual']);Tip::create(['sender_id'=>auth()->id(),'creator_id'=>$creator->id,'amount'=>$d['amount'],'message'=>$d['message']??null,'provider'=>$d['provider'],'status'=>$d['provider']==='manual'?'pending':'completed']);return back()->with('success','Thank you for supporting '.$creator->name.'.');}
 public function edit(){abort_unless(auth()->user()->canCreateContent(),403);return view('creators.edit',['profile'=>auth()->user()->creatorProfile]);}
 public function update(Request $r){$d=$r->validate(['display_name'=>'required|string|max:100','bio'=>'nullable|string|max:2000','monthly_price'=>'required|numeric|min:0','is_accepting_tips'=>'nullable|boolean']);auth()->user()->creatorProfile()->update($d+['is_accepting_tips'=>$r->boolean('is_accepting_tips')]);return back()->with('success','Creator profile updated.');}
}
