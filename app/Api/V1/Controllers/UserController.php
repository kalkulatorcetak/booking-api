<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Models\User;
use App\Api\V1\Transformers\UserTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    protected $itemsPerPage = 20;

    public function index()
    {
        $users = User::paginate($this->itemsPerPage);

        return $this->response->paginator($users, new UserTransformer, ['key' => 'users']);
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return $this->response->errorNotFound("User with id {$id} not found!");
        }

        return $this->item($user, new UserTransformer, ['key' => 'user']);
    }
}
