<?php

namespace App\Http\Controllers;

use App\Http\Resources\BlogListResource;
use App\Http\Resources\BlogResource;
use App\Http\Resources\ImageResource;
use App\Models\Blog;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Support\Str;


class BlogController extends Controller
{
    public function index(Request $request)
    {
        $blogs = new Blog();
        if (!empty($request->q)) {
            $blogs = $blogs
                ->where('title', 'like', "%$request->q%")
                ->orWhere('content', 'like', "%$request->q%");
        }
        if ($request->status !== null && $request->status !== 'all') {
            $blogs = $blogs->where('is_published', '=', (int)$request->status);
        }
        return Inertia::render('Blog/Index', [
            'blogs' => BlogListResource::collection($blogs->paginate(10)->appends($request->all())),
        ]);
    }

    public function statusUpdate(Request  $request)
    {
        if (Blog::where(['id' => $request->id])->update(['is_published' => $request->status ? 1 : 0])) {
            $status = $request->status == 0  ? "Unpublished" : "Published";
            return response()->json(['message' => "Your Blog has been " . $status, 'success' => true]);
        }
        return response()->json(['message' => 'Opps! something went wrong.', 'success' => false]);
    }
    public function create()
    {
        return Inertia::render('Blog/Form');
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:blogs,title',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['message' => $validator->errors()->first(), 'success' => false]);
        }
        $blog = Blog::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'is_published' => $request->status,
        ]);
        if ($blog) {
            Image::where(['id' => $request->image['id']])->update(['entity_id' => $blog->id, 'entity_type' => 'blog']);

            return redirect('blogs')->with('flash', createMessage('Blog'));
        }
        return redirect('blog');
    }


    public function show(Request $request, $id)
    {
        $blog = Blog::find($id);

        if ($request->expectsJson()) {
            return response()->json([
                'data' => new BlogResource($blog),
            ]);
        }

        return Inertia::render('Blog/Show', [
            'blog' => new BlogResource($blog),
        ]);
        // return response()->json(['data' => $data, 'success' => true]);
    }
    public function edit($id)
    {
        $blog = Blog::find($id);
        $image = Image::where(['entity_id' => $id, 'entity_type' => 'blog'])->first();
        return Inertia::render('Blog/Form', [
            'blog' => new BlogResource($blog),
            'image' => $image ?  new ImageResource($image) : null,
        ]);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['message' => $validator->errors()->first(), 'success' => false]);
        }
        $blog = Blog::find($id);
        if ($blog) {
            $update = Blog::where(['id' => $blog->id])->update([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'content' => $request->content,
                'is_published' => $request->status,
            ]);
            if ($update) {
                Image::where('id', $request->image['id'])->update(['entity_id' => $blog->id, 'entity_type' => 'blog']);
                return redirect('blogs')->with('flash', updateMessage('Blog'));
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'Blog not updated',
        ], 400);
    }


    public function destroy($id)
    {
        $blog = Blog::find($id);
        if ($blog->delete()) {
            return response()->json(['success' => true, 'message' => 'Blog has been deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'Opps something went wrong!']);
    }
}
