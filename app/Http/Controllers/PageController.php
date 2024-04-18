<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    public function index(Request $request)
    {


        $termsAndCondition = Page::where(['entity_type' => 'terms_and_condition'])->first();

        return Inertia::render('Pages/Index', ['terms_and_condition' => $termsAndCondition]);
    }
    public function termAndCondition(Request $request)
    {


        $request->validate([
            'terms_and_condition' => 'required',
        ]);

        $termsAndCondition = Page::where(['id' => $request->id, 'entity_type' => 'terms_and_condition'])->first();

        if ($termsAndCondition) {
            $update = $termsAndCondition->update([
                'content' => $request->terms_and_condition,
                'slug'  => $request->segment(2),
            ]);
            if ($update) {
                return redirect('pages')->with('flash', updateMessage('Terms and condition'));
            }
        } else {
            $termsAndCondition = Page::create([
                'entity_type' => 'terms_and_condition',
                'slug'  => $request->segment(2),
                'content' => $request->terms_and_condition,
            ]);

            if ($termsAndCondition) {
                return redirect('page/terms-and-condition')->with('flash', createMessage('Terms and condition'));
            }
        }
        return redirect('pages')->with('flash', errorMessage());
    }

    //privacy policy

    public function getPrivacyPolicy(Request $request)
    {
        $privacyPolicy = Page::where(['entity_type' => 'privacy_policy'])->first();

        return Inertia::render('Pages/PrivacyPolicy', ['privacy_policy' => $privacyPolicy]);
    }
    public function storePrivacyPolicy(Request $request)
    {
        $request->validate([
            'privacy_policy' => 'required',
        ]);

        $privacyPolicy = Page::where(['id' => $request->id, 'entity_type' => 'privacy_policy'])->first();

        if ($privacyPolicy) {
            $update = $privacyPolicy->update([
                'slug'  => $request->segment(2),
                'content' => $request->privacy_policy
            ]);
            if ($update) {
                return redirect('page/privacy-policy')->with('flash', updateMessage('Privacy policy'));
            }
        } else {
            $privacyPolicy = Page::create([
                'slug'  => $request->segment(2),
                'entity_type' => 'privacy_policy',
                'content' => $request->privacy_policy,
            ]);

            if ($privacyPolicy) {
                return redirect('page/privacy-policy')->with('flash', createMessage('Privacy policy'));
            }
        }
        return redirect('page/privacy-policy')->with('flash', errorMessage());
    }

    //refund policy

    public function getRefundPolicy(Request $request)
    {
        $termAndCondition = Page::where(['entity_type' => 'refund_policy'])->first();

        return Inertia::render('Pages/RefundPolicy', ['refund_policy' => $termAndCondition]);
    }
    public function storeRefundPolicy(Request $request)
    {
        $request->validate([
            'refund_policy' => 'required',
        ]);

        $refundPolicy = Page::where(['id' => $request->id, 'entity_type' => 'refund_policy'])->first();

        if ($refundPolicy) {
            $update = $refundPolicy->update([
                'slug'  => $request->segment(2),
                'content' => $request->refund_policy
            ]);
            if ($update) {
                return redirect('page/refund-policy')->with('flash', updateMessage('Refund policy'));
            }
        } else {
            $refundPolicy = Page::create([
                'slug'  => $request->segment(2),
                'entity_type' => 'refund_policy',
                'content' => $request->refund_policy,
            ]);

            if ($refundPolicy) {
                return redirect('page/refund-policy')->with('flash', createMessage('Refund policy'));
            }
        }
        return redirect('page/refund-policy')->with('flash', errorMessage());
    }

    //return policy

    public function getReturnPolicy(Request $request)
    {
        $returnPolicy = Page::where(['entity_type' => 'return_policy'])->first();

        return Inertia::render('Pages/ReturnPolicy', ['return_policy' => $returnPolicy]);
    }
    public function storeReturnPolicy(Request $request)
    {
        $request->validate([
            'return_policy' => 'required',
        ]);

        $returnPolicy = Page::where(['id' => $request->id, 'entity_type' => 'return_policy'])->first();

        if ($returnPolicy) {
            $update = $returnPolicy->update([
                'slug'  => $request->segment(2),
                'content' => $request->return_policy
            ]);
            if ($update) {
                return redirect('page/return-policy')->with('flash', updateMessage('Return policy'));
            }
        } else {
            $returnPolicy = Page::create([
                'slug'  => $request->segment(2),
                'entity_type' => 'return_policy',
                'content' => $request->return_policy,
            ]);

            if ($returnPolicy) {
                return redirect('page/return-policy')->with('flash', createMessage('Return policy'));
            }
        }
        return redirect('page/return-policy')->with('flash', errorMessage());
    }

    //cancellation policies
    public function getCancellationPolicy(Request $request)
    {
        $cancelPolicy = Page::where(['entity_type' => 'cancellation_policy'])->first();

        return Inertia::render('Pages/CancellationPolicy', ['cancellation_policy' => $cancelPolicy]);
    }
    public function storeCancellationPolicy(Request $request)
    {
        $request->validate([
            'cancellation_policy' => 'required',
        ]);

        $cancelPolicy = Page::where(['id' => $request->id, 'entity_type' => 'cancellation_policy'])->first();

        if ($cancelPolicy) {
            $update = $cancelPolicy->update([
                'slug'  => $request->segment(2),
                'content' => $request->cancellation_policy
            ]);
            if ($update) {
                return redirect('page/cancellation-policy')->with('flash', updateMessage('Cancellation policy'));
            }
        } else {
            $cancelPolicy = Page::create([
                'slug'  => $request->segment(2),
                'entity_type' => 'cancellation_policy',
                'content' => $request->cancellation_policy,
            ]);

            if ($cancelPolicy) {
                return redirect('page/cancellation-policy')->with('flash', createMessage('Cancellation policy'));
            }
        }
        return redirect('page/cancellation-policy')->with('flash', errorMessage());
    }
    //about us

    public function getAboutUs(Request $request)
    {
        $termAndCondition = Page::where(['entity_type' => 'about_us'])->first();

        return Inertia::render('Pages/AboutUS', ['about_us' => $termAndCondition]);
    }
    public function storeAboutUs(Request $request)
    {
        $request->validate([
            'about_us' => 'required',
        ]);

        $termAndCondition = Page::where(['id' => $request->id, 'entity_type' => 'about_us'])->first();

        if ($termAndCondition) {
            $update = $termAndCondition->update([
                'slug'  => $request->segment(2),
                'content' => $request->about_us
            ]);
            if ($update) {
                return redirect('page/about-us')->with('flash', updateMessage('About us'));
            }
        } else {
            $termAndCondition = Page::create([
                'slug'  => $request->segment(2),
                'entity_type' => 'about_us',
                'content' => $request->about_us,
            ]);

            if ($termAndCondition) {
                return redirect('page/about-us')->with('flash', createMessage('About us'));
            }
        }
        return redirect('page/about-us')->with('flash', errorMessage());
    }
}
