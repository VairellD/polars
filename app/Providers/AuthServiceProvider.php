<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Post;
use App\Models\Comment;
use App\Policies\PostPolicy;
use App\Policies\CommentPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Post::class => PostPolicy::class,
        Comment::class => CommentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define admin gate
        Gate::define('admin', function ($user) {
            return $user->isAdmin();
        });

        // Define additional gates for admin functionality
        Gate::define('delete-users', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('manage-posts', function ($user) {
            return $user->isAdmin();
        });

        // Define any additional gates here if needed
        // Gate::define('admin', function ($user) {
        //    return $user->isAdmin();
        // });
    }
}
