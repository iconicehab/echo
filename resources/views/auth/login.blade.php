@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col justify-center items-center bg-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-black">Sign in to your account</h2>
        </div>
        <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="rounded-md shadow-sm -space-y-px">
                <div class="mb-4">
                    <label for="email" class="block text-black font-semibold mb-1">Email</label>
                    <input id="email" name="email" type="email" required autofocus class="form-input w-full border-black rounded text-black" value="{{ old('email') }}">
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-black font-semibold mb-1">Password</label>
                    <input id="password" name="password" type="password" required class="form-input w-full border-black rounded text-black">
                </div>
            </div>
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox" class="form-checkbox border-black text-black">
                    <label for="remember_me" class="ml-2 block text-black">Remember me</label>
                </div>
                <div class="text-sm">
                    <a href="{{ route('password.request') }}" class="underline text-black">Forgot your password?</a>
                </div>
            </div>
            <div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-black rounded bg-black text-white font-semibold hover:bg-white hover:text-black transition">Sign In</button>
            </div>
            @if ($errors->any())
                <div class="mt-4 text-red-600">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
        </form>
        <div class="text-center mt-4">
            <a href="{{ route('register') }}" class="underline text-black">Don't have an account? Register</a>
        </div>
    </div>
</div>
@endsection
