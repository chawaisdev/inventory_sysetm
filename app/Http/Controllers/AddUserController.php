<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AddUserController extends Controller
{
    // Show list of customers
    public function index(Request $request)
    {
        $users = User::paginate(10);
        return view('adduser.index', compact('users'));
    }

    // Show create customer form
    public function create()
    {
        return view('adduser.create');
    }

    // Store new customer
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required',
            'address' => 'required',
            'user_type' => 'required|in:customer,supplier',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->user_type = $request->user_type;
        $user->save();

        return redirect()->route('adduser.index')->with('success', 'User added successfully.');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('adduser.index', compact('user'));
    }   

    // Show edit form for customer
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('adduser.edit', compact('user'));
    }

    // Update customer
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required',
            'address' => 'required',
            'user_type' => 'required|in:customer,supplier',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->user_type = $request->user_type;
        $user->save();

        return redirect()->route('adduser.index')->with('success', 'User updated successfully.');
    }

    // Delete customer
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('adduser.index')->with('success', 'Customer deleted successfully.');
    }
}
