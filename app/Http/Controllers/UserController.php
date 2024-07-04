<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $title  = 'Users Page';
        $data   = User::all();
        return view('user.index', compact('title', 'data'));
    }

    public function create()
    {
        $title  = 'Users Form';
        $role = ['Admin', 'User'];
        return view('user.create', compact('title'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nama'      => 'required',
            'username'  => 'required',
            'email'     => 'required',
            'password'  => 'required',
            'role'      => 'required'
        ]);
        try {
            $user           = new User();
            $user->name     = $request->nama;
            $user->username = $request->username;
            $user->email    = $request->email;
            $user->password = bcrypt($request->password);
            $user->role = $request->role;
            $user->save();
            return redirect()->route('users.index')->with('success', 'Data Berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->with('failed', $e->getMessage());
        }
    }

    public function edit(User $user)
    {
        $title  = 'Users Edit Form';
        $role = ['Admin', 'User'];
        $data = User::where('id', $user->id)->first();

        return view('user.edit', compact('title', 'user'));
    }

    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'nama'      => 'required',
            'username'  => 'required',
            'role'      => 'required',
            'email'     => 'required'
        ]);

        try {

            $user->name = $request->nama;
            $user->username = $request->username;
            $user->role = $request->role;
            $user->email = $request->email;
            $user->save();

            return redirect()->route('users.index')->with('success', 'Data Berhasil diubah');
        } catch (\Exception $e) {

            return back()->with('failed', $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return back()->with('success', 'Data Berhasil dihapus');
        } catch (\Exception $th) {
            return back()->with('failed', $th->getMessage());
        }
    }

    public function changePassword()
    {
        $title = 'Change Password';

        return view('user.change-password', compact('title'));
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'password_lama' => 'required',
            'password_baru' => 'required'
        ]);
        try {
            $user = User::find(auth()->user()->id);

            if (!Hash::check($request->password_lama, $user->password)) {

                return back()->with('failed', 'Password lama tidak sesuai');
            } else {
                $user->password = bcrypt($request->password_baru);
                $user->save();
                return redirect()->route('dashboard.index')->with('success', 'Password Berhasil diubah');
            }
        } catch (\Exception $th) {
            return back()->with('failed', $th->getMessage());
        }
    }
}
