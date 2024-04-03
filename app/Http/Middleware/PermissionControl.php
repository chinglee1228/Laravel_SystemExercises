<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
//use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class PermissionControl 
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
       // return $next($request); 原本預設
       if (Auth::user()) {
        if(Auth::user()->did =='2')
       {
        return $next($request);
       }
       else
       {
        $request->session()->flash('success', '無權限進入！');
        return redirect()->route('index');
        
       }
        
       }
       else{
        return redirect()->to('login');
       }
       // 通過 middleware 檢查
       
   }



}
