@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col justify-center items-center bg-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-black">Create your account</h2>
        </div>
        <form class="mt-8 space-y-6" method="POST" action="{{ route('register') }}">
            @csrf
            <div class="rounded-md shadow-sm -space-y-px">
                <div class="mb-4">
                    <label for="name" class="block text-black font-semibold mb-1">Name</label>
                    <input id="name" name="name" type="text" required autofocus class="form-input w-full border-black rounded text-black" value="{{ old('name') }}">
                </div>
                <div class="mb-12">
                    <label for="email" class="block text-black font-semibold mb-1">Email</label>
                    <input id="email" name="email" type="email" required class="form-input w-full border-black rounded text-black" value="{{ old('email') }}">
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-black font-semibold mb-1">Password</label>
                    <input id="password" name="password" type="password" required class="form-input w-full border-black rounded text-black">
                </div>
                <div class="mb-4">
                    <label for="password_confirmation" class="block text-black font-semibold mb-1">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required class="form-input w-full border-black rounded text-black">
                </div>
            </div>
            <div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-black rounded bg-black text-white font-semibold hover:bg-white hover:text-black transition">Register</button>
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
            <a href="{{ route('login') }}" class="underline text-black">Already have an account? Sign in</a>
        </div>
    </div>
</div>
@endsection
