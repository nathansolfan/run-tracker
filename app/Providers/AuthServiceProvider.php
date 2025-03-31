<?php

namespace App\Providers;

use App\Models\Run;
use App\Policies\RunPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Run::class => RunPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}


// Separation of concerns - It keeps your authorization logic separate from your controllers, making your code more maintainable
// Security by design - It prevents accidental security holes by centralizing access control
// Scalability - As your application grows, having policies makes it easier to manage permissions
// Industry standard - Companies hiring Laravel developers expect familiarity with this pattern