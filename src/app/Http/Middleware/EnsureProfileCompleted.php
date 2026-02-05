<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // プロフィール完了済みユーザーがプロフィール設定画面にいる場合
        if($user && $user->is_profile_completed && $request->path() === 'mypage/profile'){
            // マイページから来た場合（?from=mypage）→ 編集を許可
            if($request->query('from') === 'mypage'){
                return $next($request);
            }
            // それ以外（ログイン直後など）→ 商品一覧へ
            return redirect('/');
        }

        // プロフィール未完了ユーザーが他のページにアクセスしようとしたら、プロフィール設定画面に飛ばす
        if($user && !$user->is_profile_completed && $request->path() !== 'mypage/profile'){
            return redirect('mypage/profile');
        }

        return $next($request);
    }
}
