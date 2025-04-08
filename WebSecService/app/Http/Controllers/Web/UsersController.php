<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Artisan;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use App\Models\User;

class UsersController extends Controller {

	use ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list(Request $request) 
    {
        // Check permissions
        if (!auth()->user()->hasPermissionTo('show_users')) {
            abort(401);
        }

        // Build query
        $query = User::select('*')
            ->with(['roles', 'permissions']) // Eager load relationships
            ->when($request->keywords, function($q) use ($request) {
                return $q->where(function($query) use ($request) {
                    $query->where("name", "like", "%{$request->keywords}%")
                          ->orWhere("email", "like", "%{$request->keywords}%");
                });
            });

        // Get results
        $users = $query->orderBy('name')->paginate(10);

        // Return view with data
        return view('users.list', compact('users'));
    }

	public function register() {
        return view('users.register');
    }

    public function doRegister(Request $request) 
    {
        try {
            // Stronger password validation
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'confirmed', 
                    Password::min(8)
                        ->letters()
                        ->numbers()
                        ->mixedCase()
                        ->symbols()
                ]
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput($request->except('password', 'password_confirmation'));
            }

            DB::beginTransaction();
            try {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'credit' => 0.00
                ]);

                // Role creation if doesn't exist
                $customerRole = Role::firstOrCreate(['name' => 'Customer']);
                $user->assignRole($customerRole);

                DB::commit();
                return redirect()->route('login')->with('success', 'Account created successfully!');
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors('Registration failed. Please try again.');
        }
    }

    public function login(Request $request) 
    {
        // Redirects already authenticated users
        if (auth()->check()) {
            return redirect('/');
        }

        return view('users.login');
    }

    public function doLogin(Request $request) 
    {
        // Validate credentials
        if (!Auth::attempt([
            'email' => $request->email, 
            'password' => $request->password
        ])) {
            return redirect()
                ->back()
                ->withInput($request->input())
                ->withErrors('Invalid login information.');
        }

        // Set authenticated user
        $user = User::where('email', $request->email)->first();
        Auth::setUser($user);

        return redirect('/');
    }

    public function doLogout(Request $request) {
    	
    	Auth::logout();

        return redirect('/');
    }

    /**
     * Display user profile
     *
     * @param Request $request
     * @param int|null $userId
     * @return \Illuminate\View\View
     */
    public function profile(Request $request, ?int $userId = null): \Illuminate\View\View
    {
        try {
            // Get requested user or fallback to authenticated user
            $user = $userId ? User::findOrFail($userId) : auth()->user();

            // Check if user has permission to view other profiles
            if ($userId && $userId !== auth()->id() && !auth()->user()->hasPermissionTo('show_users')) {
                abort(403, 'Unauthorized action.');
            }

            // Get all permissions (direct and inherited from roles)
            $permissions = $user->getAllPermissions();

            // Load roles relationship for efficient access
            $user->load('roles');

            return view('users.profile', compact('user', 'permissions'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::notice('Profile view attempted for non-existent user: ' . $userId);
            abort(404, 'User not found');

        } catch (\Exception $e) {
            Log::error('Profile view failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to load profile. Please try again.');
        }
    }

    public function edit(Request $request, User $user = null) {
   
        $user = $user??auth()->user();
        if(auth()->id()!=$user?->id) {
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

    public function save(Request $request, User $user) {

        if(auth()->id()!=$user->id) {
            if(!auth()->user()->hasPermissionTo('show_users')) abort(401);
        }

        $user->name = $request->name;
        $user->save();

        if(auth()->user()->hasPermissionTo('admin_users')) {

            $user->syncRoles($request->roles);
            $user->syncPermissions($request->permissions);

            Artisan::call('cache:clear');
        }

        //$user->syncRoles([1]);
        //Artisan::call('cache:clear');

        return redirect(route('profile', ['user'=>$user->id]));
    }

    public function delete(Request $request, User $user) {

        if(!auth()->user()->hasPermissionTo('delete_users')) abort(401);

        //$user->delete();

        return redirect()->route('users');
    }

    public function editPassword(Request $request, User $user = null) {

        $user = $user??auth()->user();
        if(auth()->id()!=$user?->id) {
            if(!auth()->user()->hasPermissionTo('edit_users')) abort(401);
        }

        return view('users.edit_password', compact('user'));
    }

    public function savePassword(Request $request, User $user) {

        if(auth()->id()==$user?->id) {
            
            $this->validate($request, [
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);

            if(!Auth::attempt(['email' => $user->email, 'password' => $request->old_password])) {
                
                Auth::logout();
                return redirect('/');
            }
        }
        else if(!auth()->user()->hasPermissionTo('edit_users')) {

            abort(401);
        }

        $user->password = bcrypt($request->password); //Secure
        $user->save();

        return redirect(route('profile', ['user'=>$user->id]));
    }
}