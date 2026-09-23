<?php

test('the application redirects unauthenticated guest to login page', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('login'));
});
