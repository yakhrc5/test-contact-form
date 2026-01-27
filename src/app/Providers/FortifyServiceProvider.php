<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LogoutResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;


class FortifyServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
  $this->app->instance(LogoutResponse::class, new class implements LogoutResponse {
    public function toResponse($request)
    {
      return redirect('/login');
    }
  });
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    Fortify::createUsersUsing(CreateNewUser::class);

    // register（会員登録画面）
    Fortify::registerView(function () {
      return view('auth.register');
    });
    // login（ログイン画面）
    Fortify::loginView(function () {
      return view('auth.login');
    });
    //試行回数制限
    RateLimiter::for('login', function (Request $request) {
    $email = (string) $request->email;

    return Limit::perMinute(10)->by($email . $request->ip());
    });

  }
}
