<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>{{ $title ?? 'EmberFans' }}</title><link rel="preconnect" href="https://fonts.bunny.net"><link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800" rel="stylesheet"><link rel="stylesheet" href="{{ asset('css/emberfans.css') }}"></head>
<body class="{{ auth()->check() ? 'app-body' : 'guest-body' }}">
@if(auth()->check())
<aside class="sidebar"><a class="legacy-logo" href="{{ route('home') }}"><img src="{{ asset('images/emberfan-logo.png') }}" alt="EmberFan — Hot Content. Real Connection."></a><nav class="legacy-nav"><a href="{{ route('home') }}">⌂ <span>Home</span></a><a href="{{ route('dashboard') }}">♨ <span>My Feed</span></a><a href="{{ route('creators.index') }}">✦ <span>Social Feed</span></a><a href="{{ route('creators.index') }}">◉ <span>Stories</span></a><a href="{{ route('creators.index') }}">+ <span>My Stories</span></a><a href="{{ route('creators.index') }}">◆ <span>Explore Creators</span></a><a href="{{ route('creators.index') }}">🔍 <span>Discover</span></a><a href="{{ route('dashboard') }}">♡ <span>My Subscriptions</span></a></nav><div class="side-footer"><a href="{{ route('creator.edit') }}">⚙ <span>Account Settings</span></a><form method="POST" action="{{ route('logout') }}">@csrf<button>Log Out</button></form></div></aside>
<section class="app-shell"><header class="legacy-top"><div class="story-links"><span>◉&nbsp; Stories</span><span>+&nbsp; My Stories</span></div><form class="creator-search" action="{{ route('creators.index') }}"><input aria-label="Search creators" placeholder="Search creators…"></form><div class="profile-tools"><span class="user-chip">{{ auth()->user()->name }}<small>{{ ucfirst(auth()->user()->role) }}</small></span><a href="{{ route('messages.index') }}">●</a><a href="{{ route('dashboard') }}">♧</a></div></header><main>
@else
<header class="public-top"><a class="public-brand" href="{{ route('home') }}"><img src="{{ asset('images/emberfan-logo.png') }}" alt="EmberFan"></a><div><a class="public-login" href="{{ route('login') }}">Log in</a><a class="public-signup" href="{{ route('register') }}">Sign up</a></div></header><main class="public-main">
@endif
@if(session('success'))<div class="notice">{{ session('success') }}</div>@endif
@if($errors->any())<div class="error">{{ $errors->first() }}</div>@endif
{{ $slot }}
</main>
@if(auth()->check())</section>@endif
</body></html>
