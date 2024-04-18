<?php

namespace App\Http\Controllers;

use App\Http\Resources\Menu\MenuItemResource;
use App\Models\Menu;
use Inertia\Inertia;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use App\Http\Resources\MenuListResource;
use App\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\MenuItemListResource;
use App\Models\Role;

class MenuItemController extends Controller
{
    public $roles;
    public function  __construct()
    {
        $this->roles = Role::get();
    }
    public function index(Request $request)
    {
        $menuLists = new MenuItem();
        if (!empty($request->q)) {
            $menuLists = $menuLists->where('title', 'like', "%$request->q%");
        }
        return Inertia::render('Menu/Index', [
            'menu_lists' => MenuItemListResource::collection($menuLists->orderBy('title', 'asc')->paginate(5)->appends($request->all())),
        ]);
    }
    public function create(Request $request)
    {

        $menuLists = new Menu();
        return Inertia::render('Menu/Create', [
            'menu_lists' => $menuLists->get(),
            'parents' => MenuItem::where(['parent_id' => null])->get(),
            'roles' => $this->roles
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'menu' => 'required',
            'title' => 'required|unique:menu_items,title',
            'url' => 'required',
            'icon' => 'required',
            'color' => 'required',
            'order_by' => 'required',
            'route' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['message' => $validator->errors()->first()]);
        }
        $menu = MenuItem::create([
            'menu_id' => $request->menu,
            'role' => $request->role,
            'title' => $request->title,
            'url' => $request->url,
            'target' => $request->target,
            'icon_class' => $request->icon,
            'color' => $request->color,
            'parent_id' => $request->parent,
            'order_by' => $request->order_by,
            'route' => $request->route,
            'parameters' => $request->parameters,
        ]);
        if ($menu) {
            $permission_read = Permission::create(['name' => $request->title . '.read', 'menu_item_id' => $menu->id, 'description' => 'All ' . $request->title . ' content can access']);
            $permission_write = Permission::create(['name' => $request->title . '.write', 'menu_item_id' => $menu->id, 'description' => 'All ' . $request->title . ' content can modify']);
            $permission_delete = Permission::create(['name' => $request->title . '.delete', 'menu_item_id' => $menu->id, 'description' => 'All ' . $request->title . ' content can remove']);
            return redirect('/menu-items')->with('flash', ['message' => 'Menu successfully added.']);
        }
        return redirect()->back()->withErrors(['Opps something went wrong!']);
    }

    public function edit(Request $request, $id)
    {
        $menuLists = new Menu();
        return Inertia::render('Menu/Edit', [
            'menu_lists' => MenuListResource::collection($menuLists->get()),
            'parents' => MenuItem::where(['parent_id' => null])->get(),
            'item' => new MenuItemResource(MenuItem::find($id)),
            'roles' => $this->roles
        ]);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'menu' => 'required',
            'title' => 'required',
            'url' => 'required',
            'icon' => 'required',
            'color' => 'required',
            'order_by' => 'required',
            'route' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['message' => $validator->errors()->first()]);
        }
        $menu = MenuItem::where(['id' => $id])->update([
            'menu_id' => $request->menu,
            'role' => $request->role,
            'title' => $request->title,
            'url' => $request->url,
            'target' => $request->target,
            'icon_class' => $request->icon,
            'color' => $request->color,
            'parent_id' => $request->parent,
            'order_by' => $request->order_by,
            'route' => $request->route,
            'parameters' => $request->parameters,
        ]);
        if ($menu) {
            return redirect('menu-items')->with('flash', ['message' => 'Menu successfully updated.']);
        }
    }

    public function destroy($id)
    {



        $menuItem = MenuItem::where('id', $id)->first();
        if ($menuItem->delete()) {
            return response()->json(['success' => true, 'message' => 'Menu Item has been deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'Opps something went wrong!'], 400);
    }
}
