<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::with('role:id,name')->orderBy('created_at', 'desc')->get();
        if ($request->ajax()) {
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => User::count(),
                'recordsFiltered' => User::count(),
                'data' => $users
            ]);
        } else {
            $roles = Role::get(['id', 'name']);
            return view('users', ["users" => $users, 'roles' => $roles]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('add_users', ['title' => 'Add users']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'full_name' => 'required|min:3',
            'email' => 'required|email:rfc',
            'mobile' => 'required|numeric|min:10',
            'description' => 'required',
            'role_id' => 'required'
        ]);
        if ($request->hasFile('profile_image')) {
            // Get the file
            $image = $request->file('profile_image');

            // Generate a unique file name (optional)
            $filename = time() . '.' . $image->getClientOriginalExtension();

            // Store the image in the 'public/images' directory
            $path = $image->storeAs('public/images', $filename);
        }
        try {
            User::create([
                'full_name' => $validate['full_name'],
                'email' => $validate['email'],
                'mobile' => $validate['mobile'],
                'description' => $validate['description'],
                'profile_image' => $path ?? '',
                'role_id' => $validate['role_id']
            ]);
            return response()->json(['status' => true, 'msg' => 'User added successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'msg' => 'Something went wrong! Please try later again.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('add_users', [
            'user' => $user,
            'title' => "Edit User",
            'action' => 'PUT',
            'actionUrl' => route('users.update', $user),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validate = $request->validate([
            'full_name' => 'required|min:3',
            'email' => 'required|email:rfc',
            'mobile' => 'required|numeric|min:10'
        ]);

        $user->update($validate);
        echo json_encode([
            'status' => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
