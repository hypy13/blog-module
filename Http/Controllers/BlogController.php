<?php

namespace Modules\Blog\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Blog\Entities\Blog;
use Modules\Blog\Http\Requests\BlogRequest;
use Modules\Filemanager\Entities\Filemanager;
use Modules\Filemanager\Http\Controllers\StoreFile;

class BlogController extends Controller
{

    public function index(): Renderable
    {
        $blogs = Blog::with(["magazine", "user"])->orderByDesc("id")->get();
        return view('blog::index', compact("blogs"));
    }


    public function create(): Renderable
    {
        return view('blog::create');
    }

    public function store(BlogRequest $request): RedirectResponse
    {
        $request->merge(['content' => $this->moveHtmlImageFromTmp($request["content"])]);
        $file = filepondAndMergeToRequest($request, 'photo_id', 'blogs');

        if(!$file) return onResultNotify(0, 'admin.blogs.create', message: 'please upload blog photo');

        $result = Blog::create($request->all());
        return onResultNotify($result);
    }


    public function show(Blog $blog): Renderable
    {
        return view('blog::show', compact("blog"));
    }

    public function edit(Blog $blog): Renderable
    {
        return view('blog::edit', compact("blog"));
    }


    public function update(BlogRequest $request, Blog $blog): RedirectResponse
    {
        $this->removeDifferentImage($request["content"], $blog->content);
        $request->merge(['content' => $this->moveHtmlImageFromTmp($request["content"])]);
        filepondAndMergeToRequest($request, 'photo_id', 'blogs');
        $result = $blog->update($request->all());
        return onResultNotify($result);
    }


    public function destroy(Blog $blog): JsonResponse
    {
        $this->removeDifferentImage("", $blog->content);
        $result = $blog->delete();
        return onResultNotify($result);
    }

    private function removeImages(array $storages)
    {
        foreach ($storages as $storage) {
            $path = Str::afterLast($storage, "storage/");
            Filemanager::where("path", $path)->delete();
//            Storage::disk("public")->delete($path);
        }
    }

    private function removeDifferentImage(string $new, string $before)
    {
        $new_sources = $this->getSourcesFromHtml($new);
        $before_sources = $this->getSourcesFromHtml($before);
        $array_diff = array_diff($before_sources, $new_sources);
        if (!empty($array_diff)) $this->removeImages($array_diff);
    }

    private function moveHtmlImageFromTmp(string $content): string
    {
        preg_match_all('/data-filename *= *["\']?([^"\']*)/i', $content, $file_names);
        $tmp_images = $this->getSourcesFromHtml($content);
        if (!empty($tmp_images))
            foreach ($tmp_images as $i => $tmp_image) {
                try {
                    $tmp_path = Str::after($tmp_image, "storage");
                    $alt = Str::beforeLast($file_names[1][$i], ".");
                    $newPath = "blog/" . Str::afterLast($tmp_image, "/");
                    $result = StoreFile::submitFile($tmp_path, "blog", $alt);
                    if ($result) $content = str_replace("src=\"$tmp_image\"", "src=\"" . assets($newPath) . "\"", $content);
                } catch (\Exception $exception) {
                }
            }
        return $content;
    }

    private function getSourcesFromHtml(string $new): array
    {
        preg_match_all('/src *= *["\']?([^"\']*)/i', $new, $array);
        $result = $array[1];
        foreach ($result as $item => $value) {
            if (!str_contains($value, url("/"))) unset($result[$item]);
        }
        return array_values($result) ?? [];
    }
}
