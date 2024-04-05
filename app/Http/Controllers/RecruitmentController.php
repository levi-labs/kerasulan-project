<?php

namespace App\Http\Controllers;

use App\Models\Recruitment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RecruitmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title  = 'Recruitment Page';
        $data   = Recruitment::all();

        return view('recruitment.index', compact('title', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Recruitment Add Page';

        return view('recruitment.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'nik' => 'required',
            'nama_lengkap' => 'required',
            'email' => 'required',
            'pekerjaan' => 'required',
            'umur' => 'required',
            'pendidikan_terakhir' => 'required',
            'no_telp' => 'required',
            'alamat' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $recruitment                        = new Recruitment();
            $recruitment->nik                   = $request->nik;
            $recruitment->nama_lengkap          = $request->nama_lurator;
            $recruitment->email                 = $request->email;
            $recruitment->pekerjaan             = $request->pekerjaan;
            $recruitment->umur                  = $request->umur;
            $recruitment->pendidikan_terakhir   = $request->pendidikan_terakhir;
            $recruitment->no_telp               = $request->no_telp;
            $recruitment->alamat                = $request->alamat;


            $user = new User();
            $user->name                         = $request->name;
            $user->email                        = $request->email;
            $user->password                     = bcrypt($request->email);

            $user->save();

            $recruitment->id_user               = $user->id;

            $recruitment->save();
            DB::commit();
            return redirect()->route('recruitment.index')->with('success', 'Data Berhasil');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('failed', $e->getMessage());
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
