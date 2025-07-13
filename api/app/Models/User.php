<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Enums\GroupRole;
use App\Constants\Constants;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'group_role',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function getListUsersByCondition($conditions = [])
    {
        $page = isset($conditions['page']) ? (int)$conditions['page'] : 1;

        $query = self::select(DB::raw('row_number() OVER () AS row_num'), 'id as user_id', 'name', 'email', 'group_role', 'is_active');

        if (!empty($conditions['name'])) {
            $query->where('name', 'like', '%' . $conditions['name'] . '%');
        }

        if (!empty($conditions['email'])) {
            $query->where('email', 'like', '%' . $conditions['email'] . '%');
        }

        if (!empty($conditions['group_role'])) {
            $query->where('group_role', GroupRole::from($conditions['group_role'])->label());
        }

        if (isset($conditions['is_active'])) {
            $query->where('is_active', $conditions['is_active']);
        }

        $query->where('is_delete', false);
        $query->orderBy('created_at', 'desc');

        $usersPaginator = $query->paginate(Constants::ROW_PER_PAGE, ['*'], 'page', $page);

        return [
            'list_users' => $usersPaginator->items(),
            'pagination' => [
                'current_page' => $usersPaginator->currentPage(),
                'total_page' => $usersPaginator->lastPage(),
                'row_per_page' => $usersPaginator->perPage(),
                'total_records' => $usersPaginator->total(),
            ]
        ];
    }

    public static function createUser(array $data): self
    {
        return self::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'group_role' => GroupRole::from($data['group_role'])->label(),
            'is_active' => $data['is_active'],
        ]);
    }

    public static function editUser(array $data): bool
    {
        $user = self::find($data['user_id']);
        if (!$user) {
            return false;
        }

        $user->name = $data['name'];
        $user->email = $data['email'];
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->group_role = GroupRole::from($data['group_role'])->label();
        $user->is_active = $data['is_active'];

        return $user->save();
    }

    public static function deleteUser(int $userId): bool
    {
        $user = self::find($userId);
        if (!$user) {
            return false;
        }

        $user->is_delete = true;
        return $user->save();
    }

    public static function updateActiveUser(array $data): bool
    {
        $user = self::find($data['user_id']);
        if (!$user) {
            return false;
        }

        $user->is_active = $data['is_active']; // Toggle active status
        return $user->save();
    }

    public static function updateLoginInfo($userId, $ip)
    {
        return self::where('id', $userId)->update(['last_login_ip' => $ip, 'last_login_at' => now()]);
    }
}
