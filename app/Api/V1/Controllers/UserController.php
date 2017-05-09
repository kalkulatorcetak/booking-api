<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Models\User;
use App\Api\V1\Transformers\UserTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

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
            return $this->response->errorNotFound("User with id {$id} not found!");
        }

        return $this->item($user, new UserTransformer, ['key' => 'user']);
    }

    /**
     * Register user
     *
     * Register a new user with a `username` and `password`.
     *
     * @Post("/")
     * @Versions({"v1"})
     * @Request({"username": "foo", "password": "bar"})
     * @Response(200, body={"id": 10, "username": "foo"})
     */
    public function store(UserRequest $request)
    {
    }
}
