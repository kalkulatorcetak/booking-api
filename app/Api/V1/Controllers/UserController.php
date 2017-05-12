<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Models\User;
use App\Api\V1\Transformers\UserTransformer;
use App\Api\V1\Validators\UserCreateValidator;
use App\Api\V1\Validators\UserUpdateValidator;
use App\Http\Controllers\Controller;
use Dingo\Api\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * User resource representation.
 *
 * @Resource("Users", uri="/users")
 */
class UserController extends Controller
{
    /**
     * Show all users
     *
     * Get a JSON representation of all the registered users.
     *
     * @Get("/")
     * @Versions({"v1"})
     */
    public function index(Request $request): Response
    {
        $this->authorize('list', User::class);

        return $this->responseByParams(User::class, $request, ['key' => 'users']);
    }

    /**
     * Get user
     *
     * Get a JSON representation of one user identified by id.
     *
     * @Get("/{id}")
     * @Versions({"v1"})
     */
    public function show($id): Response
    {
        try {
            $user = User::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            $this->response->errorNotFound("User with id {$id} not found!");
        }

        $this->authorize('load', $user);

        return $this->item($user, new UserTransformer, ['key' => 'user']);
    }

    /**
     * Create user
     *
     * Create a new user with a `username` and `password`.
     *
     * @Post("/")
     * @Versions({"v1"})
     * @Request({"name": "John Doe", "email": "john@doe.com", "password": "secret", "roles": ["ADMIN", "CASHIER"]})
     */
    public function store(Request $request): Response
    {
        $this->authorize('create', User::class);
        $this->validateRequest($request, new UserCreateValidator);

        $user = new User;
        $user->fill($request->only(['name', 'email']));
        $user->setPassword($request->get('password'));
        if ($request->has('roles')) {
            $user->setRoles($request->get('roles'));
        }

        $user->save();

        return $this->item($user, new UserTransformer, ['key' => 'user']);
    }

    /**
     * Update user
     *
     * Update the user datas
     *
     * @Put("/{id}")
     * @Versions({"v1"})
     * @Request({"name": "John Doe", "email": "john@doe.com", "roles": ["ADMIN", "CASHIER"]})
     */
    public function update(Request $request, $userId): Response
    {
        $user = User::findOrFail($userId);

        $this->authorize('update', $user);
        $this->validateRequest($request, new UserUpdateValidator($user));

        $user->fill(array_filter($request->only(['name', 'email'])));

        if ($request->has('roles')) {
            $user->setRoles($request->get('roles'));
        }

        $user->save();

        return $this->item($user, new UserTransformer, ['key' => 'user']);
    }
}
