<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\CompanyInfo;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;


        
class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }


    public function handle($request, Closure $next, ...$guards)
    {
        $page=explode('.',Route::currentRouteName())[0];
        $info=CompanyInfo::first();
        $website=$info->company_name;
        $dashboard_active=$unit_active=$user_active=$purchase_active=$brand_active=
        $sales_active=$category_active=$tax_active=$graph_active=$product_active=$report_active=$supplier_active='inactive_page';
        ${$page."_active"} = 'active_page';
        view()->share(compact('info','website','dashboard_active','sales_active','purchase_active','brand_active',
        'unit_active','tax_active','category_active','product_active','report_active','graph_active','user_active','supplier_active','page'));

        return parent::handle($request, $next);
    }
}
