<?php

namespace Modules\Blog\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Blog\Entities\Blog;

class SiteController extends Controller
{

    public function index()
    {

        $blogs = Blog::whereStatus(1)->with('photo')->latest()->paginate(4);
        return view('site.pages.blogs', compact('blogs'));
    }

    public function show(Blog $blog)
    {
        return view('site.pages.blogs', compact('blog'));
    }
}
