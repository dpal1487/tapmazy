<?php

namespace App\Http\Controllers;

use App\Http\Resources\FAQListResource;
use App\Models\Faq;
use Illuminate\Http\Request;
use App\Models\Category;

use Illuminate\Support\Facades\Validator;
use App\Models\Image as CategoryImage;
use App\Http\Resources\FAQResource;
use App\Models\FAQsCategory;
use Illuminate\Support\Str;
use Inertia\Inertia;

class FaqController extends Controller
{

    public function index(Request $request)
    {

        $faqs = new Faq();
        if ($request->q) {
            $faqs = $faqs->where('title', 'like', "%{$request->q}%");
        }
        $faqs = $faqs->paginate(10)->onEachSide(1)->appends(request()->query());
        // return $faqs;
        return Inertia::render('FaqsCategory/Index', [
            'faqs_categories' => FAQListResource::collection($faqs)
        ]);
    }



    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'artical' => 'required',
            'faq_category' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('faq-category.show', $request->faq_category)->withErrors([
                'success' => false,
                'message' => $validator->errors()->all()
            ]);
        }
        $faq = Faq::create([
            'title' => $request->title,
            'artical' => $request->artical,
            'category_id' => $request->faq_category,
        ]);

        if ($faq) {
            return redirect()->route('faq-category.show', $request->faq_category)->with('flash', createMessage('FAQs'));
        }
        return redirect()->route('faq-category.show', $request->faq_category)->with('flash', errorMessage());
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'artical' => 'required',
            'faq_category' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('faq-category.show', $request->faq_category)->withErrors([
                'success' => false,
                'message' => $validator->errors()->all()
            ]);
        }

        $faq = Faq::find($id);
        if ($faq) {
            $faq = Faq::where(['id' => $id])->update([
                'title' => $request->title,
                'artical' => $request->artical,
                'category_id' => $request->faq_category,
            ]);
            if ($faq) {
                return redirect()->route('faq-category.show', $request->faq_category)->with('flash', UpdateMessage('FAQs'));
            }
            return redirect()->route('faq-category.show', $request->faq_category)->with('flash', ErrorMessage());
        }
    }


    public function destroy(Faq $faq, $id)
    {
        $faq = Faq::find($id);
        if ($faq->delete()) {
            return response()->json(deleteMessage('Faq'));
        }
        return response()->json(errorMessage());
    }
}
