<?php

namespace App\Http\Controllers;

use App\Http\Resources\TestimonialListResource;
use App\Http\Resources\TestimonialResource;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class TestimonialController extends Controller
{
    private $data;

    public function index(Request $request)
    {
        $testimonials = new Testimonial();
        if (!empty($request->q)) {
            $testimonials = $testimonials
                ->where('name', 'like', "%$request->q")
                ->orWhere('testimonial', 'like', "%$request->q%");
        }
        if ($request->status != null && $request->status !== 'all') {
            $testimonials = $testimonials->where('is_published', (int)$request->status);
        }
        if ($request->expectsJson()) {
            if ($testimonials) {
                return TestimonialResource::collection($testimonials->paginate(10));
            } else {
                return response()->json(['data' => [], 'success' => true]);
            }
        }
        return Inertia::render('Testimonial/Index', [
            'testimonials' => TestimonialListResource::collection($testimonials->paginate(10)->appends($request->all())),
        ]);
    }

    public function create()
    {
        return Inertia::render('Testimonial/Form');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'testimonial' => 'required',
            'is_published' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['message' => $validator->errors()->first(), 'success' => false]);
        }
        $testimonial = Testimonial::create([
            'name' => $request->name,
            'testimonial' => $request->testimonial,
            'is_published' => $request->is_published,

        ]);
        if ($testimonial) {
            return redirect('/testimonials')->with('flash', createMessage("Testimonial"));
        }
        return redirect()->back()->withErrors(errorMessage());
    }

    public function statusUpdate(Request  $request)
    {
        if (Testimonial::where(['id' => $request->id])->update(['is_published' => $request->status ? 1 : 0])) {
            $status = $request->status == 0  ? "Unpublished" : "Published";
            return response()->json(['message' => "Your Testimonial has been " . $status, 'success' => true]);
        }
        return response()->json(['message' => 'Opps! something went wrong.', 'success' => false]);
    }
    public function show($id)
    {
        return Inertia::render('Testimonial/Show', [
            'testimonial' => new TestimonialResource(Testimonial::find($id)),
        ]);
    }

    public function edit($id)
    {
        $testimonial = Testimonial::find($id);
        return Inertia::render('Testimonial/Form', [
            'testimonial' => new TestimonialResource($testimonial),
        ]);
    }

    public function update(Request $request,  $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'testimonial' => 'required',
            'is_published' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['message' => $validator->errors()->first(), 'success' => false]);
        }
        $update = Testimonial::where(['id' => $id])->update([
            'name' => $request->name,
            'testimonial' => $request->testimonial,
            'is_published' => $request->is_published,
        ]);

        if ($update) {
            return redirect('testimonials')->with('flash', updateMessage('Testimonial'));
        }
        return redirect()->back()->withErrors(errorMessage());
    }


    public function destroy($id)
    {
        $testimonial = Testimonial::find($id);
        if ($testimonial->delete()) {
            return response()->json(deleteMessage('Testimonial'));
        }
        return response()->json(errorMessage());
    }
}
