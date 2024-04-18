<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ImageResource;
use App\Http\Resources\ServiceListResource;
use App\Http\Resources\ServiceResource;
use App\Models\Image;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Support\Str;
use League\OAuth1\Client\Server\Server;

class ServiceController extends Controller
{

    public function index(Request $request)
    {
        $services = new Service();
        if (!empty($request->q)) {
            $services = $services
                ->where('name', 'like', "%$request->q")
                ->orWhere('page', 'like', "%$request->q%")
                ->orWhere('description', 'like', "%$request->q%");
        }

        return Inertia::render('Service/Index', [
            'services' => ServiceListResource::collection($services->paginate(10)->appends($request->all())),
        ]);
    }

    public function create()
    {
        return Inertia::render('Service/Form');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'page' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['message' => $validator->errors()->first(), 'success' => false]);
        }
        $service = Service::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'page' => $request->page,
            'description' => $request->description,
        ]);
        if ($service) {
            Image::where(['id' => $request->image['id']])->update(['entity_id' => $service->id, 'entity_type' => 'service']);
            return redirect('services')->with('flash', [
                'success' => true,
                'message' => 'Service create successfully',
            ]);
        }
        return redirect('service')->with('flash', [
            'success' => true,
            'message' => 'Service not updated',
        ]);
    }

    public function show(Request $request, $id)
    {
        $service = Service::find($id);

        return Inertia::render('Service/Show', [
            'service' => new ServiceResource($service),
        ]);
    }
    public function edit($id)
    {
        $service = Service::find($id);
        if ($service) {
            $image = Image::where(['entity_id' => $id, 'entity_type' => 'service'])->first();
            return Inertia::render('Service/Form', [
                'service' => new ServiceResource($service),
                'image' => $image ?  new ImageResource($image) : null,
            ]);
        }
        return redirect('service');
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'page' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['message' => $validator->errors()->first(), 'success' => false]);
        }
        $service = Service::where(['id' => $request->id])->first();
        if ($service) {
            $update =  Service::where(['id' => $request->id])->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'page' => $request->page,
                'description' => $request->description,
            ]);
            if ($update) {
                Image::where('id', $request->image['id'])->update(['entity_id' => $service->id, 'entity_type' => 'service']);
                return redirect('services')->with('flash', updateMessage('Service'));
            }
            return response()->json([
                'success' => false,
                'message' => 'Service not updated',
            ]);
        }
        return redirect()->back();
    }

    public function destroy($id)
    {
        $service = Service::find($id);
        if ($service->delete()) {
            return response()->json(['success' => true, 'message' => 'Service has been deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'Opps something went wrong!'], 400);
    }
}
