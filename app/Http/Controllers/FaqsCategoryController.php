<?php

namespace App\Http\Controllers;

use App\Http\Resources\FaqCategoryListResource;
use App\Http\Resources\FaqCategoryResource;
use App\Http\Resources\FAQsCategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\FAQResource;
use App\Http\Resources\ImageResource;
use App\Models\FaqCategory;
use App\Models\Image;
use Inertia\Inertia;

class FaqsCategoryController extends Controller
{
    public function index(Request $request)
    {
        $faqs_categories = new FaqCategory();
        if (!empty($request->q)) {
            $faqs_categories = $faqs_categories->where('title', 'like', "%{$request->q}%");
        }
        if ($request->status !== 'all' && $request->status != null) {
            $faqs_categories = $faqs_categories->where('status', (int)$request->status);
        }
        return Inertia::render('FaqsCategory/Index', [
            'faqs_categories' => FaqCategoryListResource::collection($faqs_categories->paginate(10)->onEachSide(1)->appends(request()->query()))
        ]);
    }
    public function statusUdate(Request $request)
    {
        if (FaqCategory::where(['id' => $request->id])->update(['status' => $request->status ? 1 : 0])) {
            $status = $request->status == 0  ? "Inactive" : "Active";
            return response()->json(['message' => "Your Blog has been " . $status, 'success' => true]);
        }
        return response()->json(['message' => 'Opps! something went wrong.', 'success' => false]);
    }


    public function create()
    {
        return Inertia::render('FaqsCategory/Form');
    }

    public function store(Request $request)
    {

        // return $request->image;
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }
        $faqCategory = FaqCategory::create([
            'title' => $request->title,
            'status' => $request->status,
        ]);
        if ($faqCategory) {
            Image::where('id', $request->image['id'])->update(['entity_id' => $faqCategory->id, 'entity_type' => 'faq-category']);
            return redirect('faqs-categories')->with('flash', CreateMessage('FAQs Category'));
        }
        return redirect('faqs-categories')->with('flash',  ErrorMessage());
    }
    public function show($id)
    {
        $image = Image::where(['entity_id' => $id, 'entity_type' => 'faq-category'])->first();

        return Inertia::render('FaqsCategory/Show', [
            'faq_category' => new FAQsCategoryResource(FaqCategory::find($id)),
            'image' => $image ?  new ImageResource($image) : null,

        ]);
    }
    public function edit($id)
    {
        $faqCategory = FaqCategory::find($id);
        $image = Image::where(['entity_id' => $id, 'entity_type' => 'faq-category'])->first();

        if ($faqCategory) {
            return Inertia::render('FaqsCategory/Form', [
                'faq_category' => new FaqCategoryResource($faqCategory),
                'image' => $image ?  new ImageResource($image) : null,

            ]);
        }
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }
        $faqCategory = FaqCategory::find($request->id);
        if ($faqCategory) {
            $update = FaqCategory::where(['id' => $request->id])->update([
                'title' => $request->title,
                'status' => $request->status,
            ]);
            if ($update) {
                Image::where('id', $request->image['id'])->update(['entity_id' => $faqCategory->id, 'entity_type' => 'faq-category']);
                return redirect('faqs-categories')->with('flash',  UpdateMessage('FAQs Category'));
            }
            return redirect('faqs-categories')->with('flash',  ErrorMessage());
        }
    }

    public function destroy($id)
    {
        $faqCategory = FaqCategory::find($id);
        if ($faqCategory->delete()) {
            return response()->json(DeleteMessage('FAQs Category'));
        }
        return response()->json(ErrorMessage());
    }
}
