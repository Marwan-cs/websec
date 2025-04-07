<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use DB;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersController extends Controller {

    use ValidatesRequests;

    public function register(Request $request) {
        return view('users.register');
    }

    public function doRegister(Request $request) {
        try {
            $this->validate($request, [
                'name' => ['required', 'string', 'min:5'],
                'email' => ['required', 'email', 'unique:users'],
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->withInput($request->input())->withErrors('Invalid registration information.');
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password); 
        $user->save();

        return redirect('/users');
    }

    public function login(Request $request) {
        return view('users.login');
    }

    public function doLogin(Request $request) {
        if(!Auth::attempt(['email' => $request->email, 'password' => $request->password]))
            return redirect()->back()->withInput($request->input())->withErrors('Invalid login information.');

        $user = User::where('email', $request->email)->first();
        Auth::setUser($user);

        return redirect('/users');
    }

    public function doLogout(Request $request) {
        Auth::logout();
        return redirect('/');
    }

    public function profile(Request $request, User $user = null) {
        $user = $user ?? auth()->user();
        if (!$user) {
            abort(404, 'User not found');
        }
        if (auth()->id() != $user->id) {
            if (!auth()->user()->hasPermissionTo('show_users')) {
                abort(401);
            }
        }

        $permissions = [];
        if ($user->permissions) {
            foreach ($user->permissions as $permission) {
                $permissions[] = $permission;
            }
        }
        if ($user->roles) {
            foreach ($user->roles as $role) {
                foreach ($role->permissions as $permission) {
                    $permissions[] = $permission;
                }
            }
        }

        return view('users.profile', compact('user', 'permissions'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:8',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'The provided password does not match your current password.']);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }

    public function index(Request $request)
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request) {
   
            $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'admin' => $request->has('admin') ? true : false,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(Request $request, User $user = null) {
        $user = $user ?? auth()->user();
        if(auth()->id() != $user?->id) {
            if(!auth()->user()->hasPermissionTo('edit_users')) abort(401);
        }

        $roles = [];
        foreach(Role::all() as $role) {
            $role->taken = ($user->hasRole($role->name));
            $roles[] = $role;
        }

        $permissions = [];
        $directPermissionsIds = $user->permissions()->pluck('id')->toArray();
        foreach(Permission::all() as $permission) {
            $permission->taken = in_array($permission->id, $directPermissionsIds);
            $permissions[] = $permission;
        }

        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    public function update(Request $request, $id) {
        $user = User::findOrFail($id);

        $data = $request->only(['name', 'email', 'admin']); // Only allow specific fields

        // Update password only if provided
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }

    public function save(Request $request, User $user) {
        if(auth()->id() != $user->id) {
            if(!auth()->user()->hasPermissionTo('show_users')) abort(401);
        }
    
        $user->name = $request->name;
    
        // Update password only if provided
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
    
        if(auth()->user()->hasPermissionTo('edit_users')) {
            $user->syncRoles($request->roles ?? []);
            $user->syncPermissions($request->permissions ?? []);
            Artisan::call('cache:clear');
        }
    
        $user->save();
        return redirect(route('profile', ['user' => $user->id]));
    }

    // Method 1: Assign role with permissions
    public function assignRoleWithPermissions($userId) {
        $user = User::find($userId);
        $user->assignRole('admin');  // Admin role has all necessary permissions
        $user->admin = true;
        $user->save();
    }

    // Method 2: Assign specific permissions
    public function assignSpecificPermissions($userId) {
        $user = User::find($userId);
        $user->givePermissionTo('edit_users');
    }

    public function checkEditUsersPermission() {
        if (auth()->user()->hasPermissionTo('edit_users')) {
            // Allow access
        } else {
            abort(403);
        }
    }

    public function updateProfile(Request $request){
            $user = auth()->user();
            
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8|confirmed',
            ]);

            $user->name = $request->name;
            $user->email = $request->email;
            
            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }

            $user->save();

            return redirect()->route('profile')->with('success', 'Profile updated successfully');
        }
}
