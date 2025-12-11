<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
