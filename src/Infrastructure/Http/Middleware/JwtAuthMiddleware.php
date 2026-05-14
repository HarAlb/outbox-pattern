<?php

declare(strict_types=1);

namespace Src\Infrastructure\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Src\Domain\Common\ValueObject\Id;
use Src\Domain\User\Repositories\UserRepository;
use Tymon\JWTAuth\JWT;
use function response;

final class JwtAuthMiddleware
{
    public function __construct(
        private UserRepository $users,
        private JWT $jwt
    ) {}

    public function handle(Request $request, Closure $next)
    {
        try {
            $payload = $this->jwt->parseToken()->payload();

            if ($payload->get('type') !== 'access') {
                return response()->json(['message' => 'Invalid token type'], 401);
            }

            $userId = (int) $payload->get('sub');

            $user = $this->users->findById(new Id($userId));

            if (! $user) {
                return response()->json(['message' => 'User not found'], 401);
            }

            $request->attributes->set('user', $user);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
