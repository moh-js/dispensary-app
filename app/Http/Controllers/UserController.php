<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $defaultPassword = '123456';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('user-view');

        return view('users.index', [
            'users' => User::getUsers()->paginate(20),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('user-add');

        return view('users.add', [
            'roles' => Role::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('user-add');

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', "unique:users,email"],
            'phone' => ['nullable', 'digits:9', 'integer'],
            'role' => ['required', 'array']
        ]);

        $data = collect([
            'username' => $this->getUsername(),
            'password' => bcrypt($this->defaultPassword)
        ])
        ->merge($request->except(['role', '_token']))
        ->toArray();

        User::firstOrCreate($data)->assignRole($request->role);

        flash('User added successfully');
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize('user-update');

        return view('users.edit', [
            'user' => $user,
            'roles' => Role::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('user-update');

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', "unique:users,email,$user->id,id"],
            'phone' => ['nullable', 'digits:9', 'integer'],
            'role' => ['required', 'array']
        ]);

        $data = collect([
            'username' => $this->getUsername($user->id),
        ])
        ->merge($request->except(['role', '_token']))
        ->toArray();

        $user->update($data);
        $user->syncRoles($request->role);

        flash('User updated successfully');
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->trashed()) {
            $this->authorize('user-activate');
            $user->restore();
            $action = 'restored';
        } else {
            $this->authorize('user-deactivate');
            $user->delete();
            $action = 'deleted';
        }

        flash("User $action successfully");
        return redirect()->route('users.index');
    }

    public function destroyPermanently(User $user)
    {
        $this->authorize('user-delete');
        $user->delete();

        flash("User removed permanently successfully");
        return redirect()->route('users.index');
    }

    public function getUsername($user_id = null)
    {
        $username = strtolower(substr(request('first_name'), 0, 1).request('last_name'));

        $userFound = User::where([['username', $username], ['id', '!=', $user_id]])->count();

        if ($userFound) {
            $username = $username.'-'.rand(50);
        }

        return $username;
    }

    public function myProfile()
    {
        return view('profile.index');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'old_password' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!Hash::check($value, request()->user()->password)) {
                    $fail('The '.str_replace('_', ' ', $attribute).' incorrect .');
                }
            }]
        ]);

        request()->user()->update([
            'password' => bcrypt($request->password)
        ]);

        flash('Password updated successfully');
        return back();
    }
}
