<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

test('连续五次登录失败后触发限流', function () {
    $user = User::factory()->create();
    $payload = ['email' => $user->email, 'password' => 'wrong-password'];

    foreach (range(1, 5) as $attempt) {
        $this->from('/login')->post('/login', $payload);
    }

    $response = $this->from('/login')->post('/login', $payload);

    $response->assertSessionHasErrors('email');
    expect(session('errors')->first('email'))->toContain('尝试次数过多');

    $key = Str::transliterate(Str::lower($user->email).'|127.0.0.1');
    expect(RateLimiter::tooManyAttempts($key, 5))->toBeTrue();
});
