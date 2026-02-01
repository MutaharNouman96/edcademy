<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $blogs = Blog::query()
            ->where('status', 'published')
            ->latest()
            ->paginate(9);

        return view('website.blogs.index', compact('blogs'));
    }

    public function show(Blog $blog)
    {
        abort_unless($blog->status === 'published', 404);

        return view('website.blogs.show', compact('blog'));
    }
}

