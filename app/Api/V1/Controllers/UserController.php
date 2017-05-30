<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Models\User;
use App\Api\V1\Transformers\UserTransformer;
use App\Api\V1\Validators\UserCreateValidator;
use App\Api\V1\Validators\UserUpdateValidator;
use App\Http\Controllers\Controller;
use Dingo\Api\Http\Request;
use Dingo\Api\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Dingo\Api\Auth\Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * User resource representation.
 *
 * @Resource("Users", uri="/users")
 */
class UserController extends Controller
{
    /**
     * List the users
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
     * Get an existing user
     *
     * @Get("/{id}")
     * @Versions({"v1"})
     */
    public function show($id): Response
    {
        try {
            $user = User::findById($id);
        } catch (ModelNotFoundException $ex) {
            $this->response->errorNotFound("User with id {$id} not found!");
        }

        $this->authorize('load', $user);

        return $this->item($user, new UserTransformer, ['key' => 'user']);
    }

    /**
     * Create a new user
     *
     * @Post("/")
     * @Versions({"v1"})
     * @Request({"name": "John Doe", "email": "john@doe.com", "password": "secret", "roles": {"ADMIN", "CASHIER"}})
     */
    public function store(Request $request): Response
    {
        $this->authorize('create', User::class);
        $this->validateRequest($request, new UserCreateValidator);

        $user = new User;
        $user->fill($request->only(['name', 'email']));
        $user->setPassword($request->get('password'));
        if ($request->has('roles')) {
            $user = app(Auth::class)->user();
            $roles = $request->get('roles');

            if (in_array('ADMIN', $roles, true) && !$user->isAdmin()) {
                throw new UnauthorizedHttpException(null, 'Only ADMIN user can add ADMIN role to a user');
            }

            $user->setRoles($roles);
        }

        $user->save();

        return $this->item($user, new UserTransformer, ['key' => 'user']);
    }

    /**
     * Update an existing user
     *
     * @Put("/{id}")
     * @Versions({"v1"})
     * @Request({"name": "John Doe", "email": "john@doe.com", "roles": {"ADMIN", "CASHIER"}})
     */
    public function update(Request $request, $userId): Response
    {
        $user = User::findById($userId);

        $this->authorize('update', $user);
        $this->validateRequest($request, new UserUpdateValidator($user));

        $user->fill(array_filter($request->only(['name', 'email'])));

        if ($request->has('roles')) {
            $user->setRoles($request->get('roles'));
        }

        $user->save();

        return $this->item($user, new UserTransformer, ['key' => 'user']);
    }

    /**
     * Delete an existing user
     *
     * @Delete("/{id}")
     * @Version({"1"})
     */
    public function delete($userId): Response
    {
        $user = User::findById($userId);

        $this->authorize('delete', $user);

        $user->delete();

        return $this->response->noContent();
    }
}
