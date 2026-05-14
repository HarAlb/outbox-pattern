<?php

declare(strict_types=1);

namespace Src\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OAT;
use Src\Application\Auth\Responses\UserResponse;
use function response;

final class ProfileController extends Controller
{
    #[
        OAT\Get(
            path: '/profile',
            operationId: 'getProfile',
            summary: 'Get profile',
            security: [['bearerAuth' => []]],
            tags: ['Profile'],
            responses: [
                new OAT\Response(
                    response: 200,
                    description: 'OK',
                    content: new OAT\JsonContent(ref: '#/components/schemas/UserResponseDoc')
                ),
            ]
        )
    ]
    public function me(Request $request): JsonResponse
    {
        $user = $request->attributes->get('user');

        return response()->json(new UserResponse($user));
    }
}
