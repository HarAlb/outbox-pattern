<?php

declare(strict_types=1);

namespace Src\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OAT;
use Src\Application\Auth\LoginCommand;
use Src\Application\Auth\RegisterCommand;
use Src\Application\Auth\UseCases\LoginUser;
use Src\Application\Auth\UseCases\RegisterUser;
use Src\Infrastructure\Http\Requests\LoginRequest;
use Src\Infrastructure\Http\Requests\RegisterRequest;
use function response;

final class AuthController extends Controller
{
    public function __construct(
        private readonly RegisterUser $registerUser,
        private readonly LoginUser $loginUser
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
                    content: new OAT\JsonContent(ref: '#/components/schemas/AuthResponseDoc')
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

        return response()->json($result);
    }

    #[
        OAT\Post(
            path: '/auth/login',
            operationId: 'login',
            summary: 'Login by password',
            requestBody: new OAT\RequestBody(
                required: true,
                content: new OAT\JsonContent(
                    ref: '#/components/schemas/LoginRequestDoc'
                )
            ),
            tags: ['Auth'],
            responses: [
                new OAT\Response(
                    response: 200,
                    description: 'OK',
                    content: new OAT\JsonContent(ref: '#/components/schemas/AuthResponseDoc')
                ),
            ]
        )
    ]
    public function login(LoginRequest $request): JsonResponse
    {
        $command = new LoginCommand(
            $request->email,
            $request->password,
        );

        $result = $this->loginUser->execute($command);

        return response()->json($result);
    }
}
