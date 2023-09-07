<?php

namespace Alauddin\Authorize\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Alauddin\Authorize\Models\Permission;
use Illuminate\Http\Request;
use Session;
use Route;
use Alauddin\Authorize\Models\Role;

class PermissionsController extends Controller
{
    protected $skip = "App\Http\Controllers\User";
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $roles = Role::where('id', '>', 1)->pluck('name', 'id');
        $actions = $this->getRoutes();
        return view('vendor.authorize.permissions.index', compact('roles', 'actions'));
    }

    private function getRoutes()
    {
        $actions= [];
        $routes = Route::getRoutes();

        foreach ($routes as $route) {
            if (preg_match("/^App(.*)/i", trim($route->getActionName())) == 0 ||
                preg_match("/^App\\\\Http\\\\Controllers\\\\Auth(.*)/i", trim($route->getActionName())) > 0) {
                continue;
            }
            
            $actionName = $route->getActionName();
            $method = $route->methods()[0];
            $action = substr($actionName, strpos($actionName, '@') + 1);
            $namespace = substr($actionName, 0, strrpos($actionName, '\\'));
            $controller = substr($actionName, strrpos($actionName, '\\') + 1, -(strlen($action) + 1));

            if($namespace !== $this->skip){
                $actions[$namespace][$controller][$method][] = $action;
            }
        }
        ksort($actions);
        return $actions;
    }

    public function getSelectedRoutes(Request $request) {

        if($request->ajax())
        {
            $role_id = $request->input('role_id');
            $selected = Role::findOrFail($role_id)->permissions()->select('namespace', 'controller', 'method', 'action')->get()->toArray();
            $selectedActions = [];
            foreach ($selected as $action) {
                $selectedActions[] = $action['namespace'] . '-' . $action['controller'] . '-' . $action['method'] . '-' . $action['action'];
            }
            return response()->json(compact('selectedActions'), 200);
        }
        return response()->json([
            'responseText' => 'Not a ajax request'
        ], 500);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request)
    {
        $role_id = $request->input('role_id', 0);
        $actions = $request->input('actions');
        $data = [];
        if (count($actions) > 0) {
            foreach ($actions as $action) {
                $parts = explode('-', $action);
                $data[] = new Permission([
                    'namespace' => $parts[0],
                    'controller' => $parts[1],
                    'method' => $parts[2],
                    'action' => $parts[3],
                    'allowed' => 1
                ]);
            }
        }
        $role = Role::findOrFail($role_id);
        $role->permissions()->delete();
        $role->permissions()->saveMany($data);

        Session::flash('flash_message', 'Permission updated!');

        return redirect(Config("authorization.route-prefix") . '/permissions');
    }
}
