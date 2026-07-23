@extends('layouts.app')

@section('title', '重置密码 - ' . \App\Support\SiteSetting::get('site_name', config('app.name')))

@section('content')
    <div class="max-w-md mx-auto">
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg shadow-sm p-6">
            <h1 class="text-xl font-semibold mb-6">重置密码</h1>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ request('email') }}">

                <div>
                    <label for="email" class="block text-sm font-medium mb-1">邮箱</label>
                    <input id="email" type="email" value="{{ request('email') }}" disabled
                           class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm text-sm px-3 py-2 opacity-70">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium mb-1">新密码</label>
                    <input id="password" name="password" type="password" required autofocus
                           class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium mb-1">确认新密码</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2">
                </div>

                <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    重置密码
                </button>
            </form>
        </div>
    </div>
@endsection
