<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\SearchUserRequest;
use App\Http\Requests\AddUserRequest;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\DeleteUserRequest;
use App\Http\Requests\UpdateActiveUserRequest;
use App\Traits\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    use ApiResponse;

    public function getListUsersByCondition(SearchUserRequest $request)
    {
        $conditionSearch = $request->validated();
        $users = User::getListUsersByCondition($conditionSearch);
        return $this->successResponse(
            "Lấy danh sách user thành công.",
            $users
        );
    }

    public function addUser(AddUserRequest $addUserRequest)
    {
        $data = $addUserRequest->validated();
        $user = User::createUser($data);
        if (!$user) {
            return $this->errorResponse("Thêm user không thành công.", Response::HTTP_BAD_REQUEST);
        }
        return $this->successResponse("Thêm user thành công.");
    }

    public function editUser(EditUserRequest $editUserRequest)
    {
        $data = $editUserRequest->validated();
        $user = User::editUser($data);
        if (!$user) {
            return $this->errorResponse("Cập nhật user không thành công.", Response::HTTP_BAD_REQUEST);
        }
        return $this->successResponse("Thêm user thành công.");
    }

    public function deleteUser(DeleteUserRequest $deleteUserRequest)
    {
        $data = $deleteUserRequest->validated();
        $user = User::deleteUser($data['user_id']);
        if (!$user) {
            return $this->errorResponse("Xoá user không thành công.", Response::HTTP_BAD_REQUEST);
        }
        return $this->successResponse("Xoá user thành công.");
    }

    public function updateActiveUser(UpdateActiveUserRequest $updateActiveUserRequest)
    {
        $data = $updateActiveUserRequest->validated();
        $user = User::updateActiveUser($data);
        if (!$user) {
            return $this->errorResponse("Cập nhật trạng thái hoạt động của user không thành công.");
        }
        return $this->successResponse("Cập nhật trạng thái hoạt động của user thành công.");
    }
}
