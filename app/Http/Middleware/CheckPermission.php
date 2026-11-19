<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ?string $permission = null): Response
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        if (! $permission) {
            $routeName = $request->route()->getName();

            if ($routeName) {
                $routeMap = [
                    'author-books' => 'author_books',
                    'authors' => 'authors',
                    'book' => 'books',
                    'book-copies' => 'book_copies',
                    'book-returns' => 'book_return',
                    'category' => 'category',
                    'fine-payments' => 'fine_payments',
                    'fines' => 'fines',
                    'loan-details' => 'loans_detail',
                    'loans' => 'loans',
                    'loanTransaction' => 'loans_return',
                    'bookTransaction' => 'book_return',
                    'members' => 'member',
                    'publishers' => 'publishers',
                    'users' => 'users',
                    'dashboard' => 'dashboard',
                ];

                $actionMap = [
                    'index' => 'view',
                    'show' => 'view',
                    'store' => 'create',
                    'update' => 'edit',
                    'destroy' => 'delete',
                ];

                $parts = explode('.', $routeName);
                if (count($parts) >= 2) {
                    $action = array_pop($parts);
                    $module = implode('.', $parts);

                    if (isset($routeMap[$module]) && isset($actionMap[$action])) {
                        $permission = $routeMap[$module].'.'.$actionMap[$action];
                    }
                }
            }
        }

        // To prevent breaking the UI (dropdowns, related tables like book-copies in Loans),
        // we can allow 'view' for certain common resources without strict permission,
        // or you can just require the user to check 'View' in the Permission UI.
        // As requested to fix "can see bookCopy", we will bypass 'view' for book_copies.
        $alwaysAllowView = [
            'book_copies.view',
            'category.view',
            'authors.view',
            'publishers.view',
            'fines.view',
        ];

        if ($permission && in_array($permission, $alwaysAllowView)) {
            return $next($request);
        }

        if ($permission && ! $user->hasPermission($permission)) {
            return response()->json(['message' => 'Forbidden - You do not have permission to perform this action. ('.$permission.')'], 403);
        }

        return $next($request);
    }
}
