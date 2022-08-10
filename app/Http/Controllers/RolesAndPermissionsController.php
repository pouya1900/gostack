<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Models\RolesAndPermissions;
use App\Models\User;

class RolesAndPermissionsController extends Controller
{
  public function __construct(AdminSettings $settings)
	{
		$this->settings = $settings::first();
	}

  public function index()
  {
    $roles = RolesAndPermissions::all();

    return view('admin.roles-permissions')->withRoles($roles);
  }

  public function store(Request $request)
	{
		if (isset($request->limited_access) && isset($request->permissions)) {
			return back()->withErrorMessage(trans('admin.give_access_error'));
		}

    $messages = [
      'permissions.required' => __('admin.missing_permissions')
    ];

    $validated = $request->validate([
        'name' => 'required',
        'permissions' => 'required',
    ], $messages);

		foreach ($request->permissions as $key) {

			if (isset($request->permissions)) {
				 $permissions[] = $key;
			}
		}

		$permissions = implode( ',', $permissions);

    RolesAndPermissions::create([
      'name' => $request->name,
      'permissions' => $permissions
    ]);

    return redirect('panel/admin/roles-and-permissions')->withSuccessMessage(__('admin.success_add'));

	}//<--- End Method

  public function edit($id)
  {
    $role = RolesAndPermissions::whereEditable('1')->whereId($id)->firstOrFail();

    $permissions = explode(',', $role->permissions);

    return view('admin.edit-role')->with([
      'role' => $role,
      'permissions' => $permissions
    ]);
  }// End Method

  public function update(Request $request)
	{
		if (isset($request->limited_access) && isset($request->permissions)) {
			return back()->withErrorMessage(trans('admin.give_access_error'));
		}

    $messages = [
      'permissions.required' => __('admin.missing_permissions')
    ];

    $validated = $request->validate([
        'name' => 'required',
        'permissions' => 'required',
    ], $messages);

		foreach ($request->permissions as $key) {

			if (isset($request->permissions)) {
				 $permissions[] = $key;
			}
		}

		$permissions = implode( ',', $permissions);

    RolesAndPermissions::whereId($request->id)->update([
      'name' => $request->name,
      'permissions' => $permissions
    ]);

    return redirect('panel/admin/roles-and-permissions')->withSuccessMessage(__('admin.success_update'));

	}//<--- End Method

  public function destroy($id)
  {
    // Remove Role to Users
    User::whereRole($id)->update(['role' => 0]);

    // Delete
    RolesAndPermissions::whereId($id)->delete();

    return back();

  }//<--- End Method

}
