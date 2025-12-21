<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AlatController extends Controller
{
    public function index()
    {
        $alats = Alat::latest()->get();
        return view('admin.alat.index', compact('alats'));
    }

    public function create()
    {
        return view('admin.alat.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_alat' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga_sewa_per_hari' => 'required|integer|min:0',
            'stok_total' => 'required|integer|min:0',
            'kategori' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'foto_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $validated['slug'] = Str::slug($validated['nama_alat']);

        if ($request->hasFile('foto_path')) {
            $validated['foto_path'] = $request->file('foto_path')->store('uploads/alats', 'public');
        }

        Alat::create($validated);

        return redirect()->route('admin.alat.index')
            ->with('success', 'Alat berhasil ditambahkan.');
    }


    public function edit(Alat $alat)
    {
        return view('admin.alat.edit', compact('alat'));
    }

    public function update(Request $request, Alat $alat)
    {
        $validated = $request->validate([
            'nama_alat' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga_sewa_per_hari' => 'required|integer|min:0',
            'stok_total' => 'required|integer|min:0',
            'kategori' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'foto_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Slug unik
        $slug = Str::slug($validated['nama_alat']);
        $slugCount = Alat::where('slug', $slug)
            ->where('id', '<>', $alat->id)
            ->count();

        if ($slugCount > 0) {
            $slug .= '-' . time();
        }
        $validated['slug'] = $slug;

        // Simpan foto baru
        if ($request->hasFile('foto_path')) {
            if ($alat->foto_path && Storage::disk('public')->exists($alat->foto_path)) {
                Storage::disk('public')->delete($alat->foto_path);
            }
            $validated['foto_path'] = $request->file('foto_path')->store('uploads/alats', 'public');
        }


        $alat->update($validated);

        return redirect()->route('admin.alat.index')
            ->with('success', 'Alat berhasil diupdate.');
    }



    public function destroy(Alat $alat)
    {
        $alat->delete();

        return redirect()->route('admin.alat.index')
            ->with('success', 'Alat berhasil dihapus.');
    }
}
