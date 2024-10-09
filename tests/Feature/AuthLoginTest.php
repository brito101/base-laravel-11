<?php

it('has auth login page', function () {
    $response = $this->get(route('login'));
    $response->assertStatus(200);
});

it('has no auth register page', function () {
    $response = $this->get('/register');
    $response->assertStatus(404);
});

it('has forgot password page', function () {
    $response = $this->get(route('password.request'));
    $response->assertStatus(200);
});

it('expect redirect if no auth', function () {
    $response = $this->get(route('admin.home'));
    $response->assertRedirect(route('login'));
});

it('expect redirect if no auth login and post to logout', function () {
    $response = $this->post(route('logout'));
    $response->assertStatus(302);
    $response->assertRedirect('/');
});

it('expect success login', function () {
    $credentials = array(
        'email' => env('ADMIN_EMAIL'),
        'password' => env('ADMIN_PASSWD'),
        '_token' => csrf_token()
    );
    $response = $this->post(route('login'), $credentials);
    $response->assertRedirect(route('admin.home'));
});

it('expect fail login', function () {
    $credentials = array(
        'email' => 'test',
        'password' => 'test',
        '_token' => csrf_token()
    );
    $response = $this->post(route('login'), $credentials);
    $response->assertRedirect('/');
})->expect("Essas credenciais não foram encontradas em nossos registros.");

it('expect success logout after login', function () {
    $credentials = array(
        'email' => env('ADMIN_EMAIL'),
        'password' => env('ADMIN_PASSWD'),
        '_token' => csrf_token()
    );
    $response = $this->post(route('login'), $credentials);
    $response->assertRedirect(route('admin.home'));

    $response = $this->post(route('logout'));
    $response->assertStatus(302);
    $response->assertRedirect('/');

    $response = $this->get(route('admin.home'));
    $response->assertRedirect(route('login'));
});
