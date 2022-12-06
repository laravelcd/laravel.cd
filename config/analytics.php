<?php

return [

    /**
     * Analytics Dashboard
     *
     * The prefix and middleware for the analytics dashboard.
     */
    'prefix' => 'analytics',

    'middleware' => [
        'web',
        'auth',
        'role:moderator|admin',
    ],

    /**
     * Exclude
     *
     * The routes excluded from page view tracking.
     */
    'exclude' => [
        '/cpanel/*',
        '/livewire/message/*',
    ],

    'session' => [
        'provider' => \AndreasElia\Analytics\RequestSessionProvider::class,
    ],

];