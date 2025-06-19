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
        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required',
            'address' => 'required',
            'user_type' => 'required|in:customer,supplier',
        ]);

        // Start: Save new customer
        $user = new User();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->user_type = $request->user_type;

        // Generate unique email from name
        $baseEmail = Str::slug($request->name, '.') . '@gmail.com';
        $email = $baseEmail;
        $counter = 1;
        while (User::where('email', $email)->exists()) {
            $email = Str::slug($request->name, '.') . $counter . '@gmail.com';
            $counter++;
        }
        $user->email = $email;
        $user->password = Hash::make(
            $request->user_type === 'customer' ? 'customer123#' : 'supplier123#'
        );

        $user->save();
        return redirect()->route('adduser.index')->with('success', 'Customer added successfully.');
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
        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required',
            'address' => 'required',
            'user_type' => 'required|in:customer,supplier',
        ]);

        // Start: Update existing customer
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->user_type = $request->user_type;

        // Generate unique email from name
        $baseEmail = Str::slug($request->name, '.') . '@gmail.com';
        $email = $baseEmail;
        $counter = 1;
        while (User::where('email', $email)->where('id', '!=', $id)->exists()) {
            $email = Str::slug($request->name, '.') . $counter . '@gmail.com';
            $counter++;
        }
        $user->email = $email;
        $user->password = Hash::make(
            $request->user_type === 'customer' ? 'customer123#' : 'supplier123#'
        );
        $user->save();
        return redirect()->route('adduser.index')->with('success', 'Customer updated successfully.');
    }

    // Delete customer
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('adduser.index')->with('success', 'Customer deleted successfully.');
    }
}
