<?php

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Ensure roles exist
    Role::firstOrCreate(['name' => 'customer']);
});

test('user is assigned customer role after email verification', function () {
    Event::fake();

    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    expect($user->hasRole('customer'))->toBeFalse();

    // Simulate email verification
    $user->markEmailAsVerified();
    Event::dispatch(new Verified($user));

    $user->refresh();
    expect($user->hasRole('customer'))->toBeTrue();
});

test('user cannot login without email verification and gets verification email sent', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $response->assertRedirect('/verify-email');
    $response->assertSessionHas('message', 'Please verify your email before logging in. A verification email has been sent to your email address.');
    $this->assertGuest();
});

test('verified user can login successfully', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
        'password' => bcrypt('password123'),
    ]);

    $user->assignRole('customer');

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $response->assertRedirect('/');
    $this->assertAuthenticatedAs($user);
});

test('email verification link works without authentication', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    // Generate verification URL using Laravel's standard approach
    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->getKey().'.'.$user->getEmailForVerification())]
    );

    $response = $this->get($verificationUrl);

    $response->assertOk();
    $response->assertViewIs('auth.verify-email-success');
    $response->assertViewHas('justVerified', true);

    $user->refresh();
    expect($user->hasVerifiedEmail())->toBeTrue();
    expect($user->hasRole('customer'))->toBeTrue();
});

test('already verified user gets appropriate message', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->getKey().'.'.$user->getEmailForVerification())]
    );

    $response = $this->get($verificationUrl);

    $response->assertOk();
    $response->assertViewIs('auth.verify-email-success');
    $response->assertViewHas('alreadyVerified', true);
});

test('invalid verification hash shows error', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => 'invalid-hash']
    );

    $response = $this->get($verificationUrl);

    $response->assertOk();
    $response->assertViewIs('auth.verify-email');
    $response->assertViewHas('error');

    $user->refresh();
    expect($user->hasVerifiedEmail())->toBeFalse();
});