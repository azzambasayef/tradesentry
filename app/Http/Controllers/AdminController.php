<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Port;
use App\Models\Article;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::all();
        $ports = Port::all();
        $articles = Article::all();

        return view('admin.index', compact('users', 'ports', 'articles'));
    }

    // --- User Management ---
    public function updateUserRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->role = $request->input('role');
        $user->save();
        return redirect()->route('admin.index')->with('success', 'User role updated successfully.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.index')->with('error', 'You cannot delete yourself.');
        }
        $user->delete();
        return redirect()->route('admin.index')->with('success', 'User deleted successfully.');
    }

    // --- Port Management ---
    public function storePort(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'country_name' => 'required',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);
        Port::create($request->all());
        return redirect()->route('admin.index')->with('success', 'Port added successfully.');
    }

    public function updatePort(Request $request, $id)
    {
        $port = Port::findOrFail($id);
        $port->update($request->all());
        return redirect()->route('admin.index')->with('success', 'Port updated successfully.');
    }

    public function deletePort($id)
    {
        Port::findOrFail($id)->delete();
        return redirect()->route('admin.index')->with('success', 'Port deleted successfully.');
    }

    // --- Article Management ---
    public function storeArticle(Request $request)
    {
        $request->validate(['title' => 'required', 'content' => 'required']);
        Article::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'is_published' => $request->has('is_published'),
        ]);
        return redirect()->route('admin.index')->with('success', 'Article added successfully.');
    }

    public function updateArticle(Request $request, $id)
    {
        $article = Article::findOrFail($id);
        $article->update([
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'is_published' => $request->has('is_published'),
        ]);
        return redirect()->route('admin.index')->with('success', 'Article updated successfully.');
    }

    public function deleteArticle($id)
    {
        Article::findOrFail($id)->delete();
        return redirect()->route('admin.index')->with('success', 'Article deleted successfully.');
    }
}
