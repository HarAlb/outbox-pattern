<?php

declare(strict_types=1);

namespace Src\Interfaces\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OAT;
use Src\Application\Auth\RegisterCommand;
use Src\Application\Auth\UseCases\RegisterUser;
use Src\Interfaces\Http\Requests\RegisterRequest;

final class AuthController extends Controller
{
    public function __construct(
        private readonly RegisterUser $registerUser
    ) {}

    #[
        OAT\Post(
            path: '/auth/register',
            operationId: 'register',
            summary: 'Register by password',
            requestBody: new OAT\RequestBody(
                required: true,
                content: new OAT\JsonContent(
                    ref: '#/components/schemas/RegisterRequestDoc'
                )
            ),
            tags: ['Auth'],
            responses: [
                new OAT\Response(
                    response: 200,
                    description: 'OK',
                    content: new OAT\JsonContent(ref: '#/components/schemas/UserResponseDoc')
                ),
            ]
        )
    ]
    public function register(RegisterRequest $request): JsonResponse
    {
        $command = new RegisterCommand(
            $request->name,
            $request->email,
            $request->password,
            $request->surname
        );

        $result = $this->registerUser->execute($command);

        return response()->json([
            'user' => $result['user'],
            'token' => $result['token'],
        ]);
    }
}
