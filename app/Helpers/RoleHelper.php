<?php

if (!function_exists('userIsAdmin')) {
    /**
     * Check if authenticated user is admin
     */
    function userIsAdmin(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }
}

if (!function_exists('userIsManager')) {
    /**
     * Check if authenticated user is manager
     */
    function userIsManager(): bool
    {
        return auth()->check() && auth()->user()->isManager();
    }
}

if (!function_exists('userHasRole')) {
    /**
     * Check if authenticated user has specific role
     */
    function userHasRole(string $role): bool
    {
        return auth()->check() && auth()->user()->role === $role;
    }
}

if (!function_exists('userHasAnyRole')) {
    /**
     * Check if authenticated user has any of the given roles
     */
    function userHasAnyRole(string ...$roles): bool
    {
        return auth()->check() && in_array(auth()->user()->role, $roles);
    }
}
