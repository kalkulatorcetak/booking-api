<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Models\User;
use App\Api\V1\Transformers\UserTransformer;
use App\Api\V1\Validators\UserCreateValidator;
use App\Http\Controllers\Controller;
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
    public function index(Request $request)
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
    public function show($id)
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
     * Register user
     *
     * Register a new user with a `username` and `password`.
     *
     * @Post("/")
     * @Versions({"v1"})
     * @Request({"name": "John Doe", "email": "john@doe.com", "password": "secret"})
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);
        $this->validateRequest($request, new UserCreateValidator);

        $user = new User;
        $user->fill($request->only(['name', 'email']));
        $user->setPassword($request->get('password'));
        $user->save();

        return $this->item($user, new UserTransformer, ['key' => 'user']);
    }
}
