<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input limitedAccess" name="permissions[]" value="limited_access" @if (isset($permissions) && in_array('limited_access', $permissions)) checked="checked" @endif type="checkbox" id="gridCheck1">
			<label class="form-check-label" for="gridCheck1">
				{{ __('admin.limited_access') }}
			</label>
		</div>
		<small class="d-block">{{ __('admin.info_limited_access') }}</small>
	</div>
</div>

<div class="row mt-5 mb-2">
	<div class="col-sm-10 offset-sm-2">
		<h5>{{ __('admin.permissions') }}</h5>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input" type="checkbox" name="select_all" value="yes" id="select-all">
			<label class="form-check-label" for="select-all">
				<strong>{{ __('admin.select_all') }}</strong>
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="dashboard" @if (isset($permissions) && in_array('dashboard', $permissions)) checked="checked" @endif type="checkbox" id="gridCheck3">
			<label class="form-check-label" for="gridCheck3">
				{{ __('admin.dashboard') }}
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="general_settings" @if (isset($permissions) && in_array('general_settings', $permissions)) checked="checked" @endif type="checkbox" id="gridCheck4">
			<label class="form-check-label" for="gridCheck4">
				{{ __('admin.general_settings') }}
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="announcements" @if (isset($permissions) && in_array('announcements', $permissions)) checked="checked" @endif type="checkbox" id="checkAnnouncements">
			<label class="form-check-label" for="checkAnnouncements">
				{{ __('admin.announcements') }}
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="maintenance_mode" @if (isset($permissions) && in_array('maintenance_mode', $permissions)) checked="checked" @endif type="checkbox" id="gridCheck5">
			<label class="form-check-label" for="gridCheck5">
				{{ __('admin.maintenance_mode') }}
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="billing_information" @if (isset($permissions) && in_array('billing_information', $permissions)) checked="checked" @endif type="checkbox" id="gridCheck6">
			<label class="form-check-label" for="gridCheck6">
				{{ __('admin.billing_information') }}
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="purchases" @if (isset($permissions) && in_array('purchases', $permissions)) checked="checked" @endif type="checkbox" id="gridCheck7">
			<label class="form-check-label" for="gridCheck7">
				{{ __('admin.purchases') }}
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="tax_rates" @if (isset($permissions) && in_array('tax_rates', $permissions)) checked="checked" @endif type="checkbox" id="gridCheck8">
			<label class="form-check-label" for="gridCheck8">
				{{ __('admin.tax_rates') }}
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="plans" @if (isset($permissions) && in_array('plans', $permissions)) checked="checked" @endif type="checkbox" id="plans">
			<label class="form-check-label" for="plans">
				{{ __('admin.plans') }}
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="subscriptions" @if (isset($permissions) && in_array('subscriptions', $permissions)) checked="checked" @endif type="checkbox" id="subscriptions">
			<label class="form-check-label" for="subscriptions">
				{{ __('admin.subscriptions') }}
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="countries" @if (isset($permissions) && in_array('countries', $permissions)) checked="checked" @endif type="checkbox" id="gridCheck9">
			<label class="form-check-label" for="gridCheck9">
				{{ __('admin.countries') }}
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="states" @if (isset($permissions) && in_array('states', $permissions)) checked="checked" @endif type="checkbox" id="states">
			<label class="form-check-label" for="states">
				{{ __('admin.states') }}
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="email_settings" @if (isset($permissions) && in_array('email_settings', $permissions)) checked="checked" @endif type="checkbox" id="email_settings">
			<label class="form-check-label" for="email_settings">
				{{ __('admin.email_settings') }}
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="storage" @if (isset($permissions) && in_array('storage', $permissions)) checked="checked" @endif type="checkbox" id="storage">
			<label class="form-check-label" for="storage">
				{{ __('admin.storage') }}
			</label>
		</div>
	</div>
</div>

@foreach (Addons::all() as $addon)
<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="{{ $addon->name }}" @if (isset($permissions) && in_array($addon->name, $permissions)) checked="checked" @endif type="checkbox" id="{{$addon->name}}">
			<label class="form-check-label" for="{{ $addon->name }}">
				{{ __('admin.'.$addon->name) }}
			</label>
		</div>
	</div>
</div>
@endforeach

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="theme" @if (isset($permissions) && in_array('theme', $permissions)) checked="checked" @endif type="checkbox" id="theme">
			<label class="form-check-label" for="theme">
				{{ __('admin.theme') }}
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="custom_css_js" @if (isset($permissions) && in_array('custom_css_js', $permissions)) checked="checked" @endif type="checkbox" id="checkCustomCssJs">
			<label class="form-check-label" for="checkCustomCssJs">
				{{ __('admin.custom_css_js') }}
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="images" @if (isset($permissions) && in_array('images', $permissions)) checked="checked" @endif type="checkbox" id="images">
			<label class="form-check-label" for="images">
				{{ __('admin.images') }}
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="collections" @if (isset($permissions) && in_array('collections', $permissions)) checked="checked" @endif type="checkbox" id="collections">
			<label class="form-check-label" for="collections">
				{{ __('misc.collections') }}
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="languages" @if (isset($permissions) && in_array('languages', $permissions)) checked="checked" @endif type="checkbox" id="languages">
			<label class="form-check-label" for="languages">
				{{ __('admin.languages') }}
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="deposits" @if (isset($permissions) && in_array('deposits', $permissions)) checked="checked" @endif type="checkbox" id="deposits">
			<label class="form-check-label" for="deposits">
				{{ __('admin.deposits') }}
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="withdrawals" @if (isset($permissions) && in_array('withdrawals', $permissions)) checked="checked" @endif type="checkbox" id="withdrawals">
			<label class="form-check-label" for="withdrawals">
				{{ __('admin.withdrawals') }}
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="categories" @if (isset($permissions) && in_array('categories', $permissions)) checked="checked" @endif type="checkbox" id="categories">
			<label class="form-check-label" for="categories">
				{{ __('admin.categories') }}
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="members" @if (isset($permissions) && in_array('members', $permissions)) checked="checked" @endif type="checkbox" id="members">
			<label class="form-check-label" for="members">
				{{ __('admin.members') }}
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="role_and_permissions" @if (isset($permissions) && in_array('role_and_permissions', $permissions)) checked="checked" @endif type="checkbox" id="role_and_permissions">
			<label class="form-check-label" for="role_and_permissions">
				{{ __('admin.role_and_permissions') }}
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="members_reported" @if (isset($permissions) && in_array('members_reported', $permissions)) checked="checked" @endif type="checkbox" id="members_reported">
			<label class="form-check-label" for="members_reported">
				{{ __('admin.members_reported') }}
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="images_reported" @if (isset($permissions) && in_array('images_reported', $permissions)) checked="checked" @endif type="checkbox" id="images_reported">
			<label class="form-check-label" for="images_reported">
				{{ __('admin.images_reported') }}
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="pages" @if (isset($permissions) && in_array('pages', $permissions)) checked="checked" @endif type="checkbox" id="pages">
			<label class="form-check-label" for="pages">
				{{ __('admin.pages') }}
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="payment_settings" @if (isset($permissions) && in_array('payment_settings', $permissions)) checked="checked" @endif type="checkbox" id="payment_settings">
			<label class="form-check-label" for="payment_settings">
				{{ __('admin.payment_settings') }}
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="profiles_social" @if (isset($permissions) && in_array('profiles_social', $permissions)) checked="checked" @endif type="checkbox" id="profiles_social">
			<label class="form-check-label" for="profiles_social">
				{{ __('admin.profiles_social') }}
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="social_login" @if (isset($permissions) && in_array('social_login', $permissions)) checked="checked" @endif type="checkbox" id="social_login">
			<label class="form-check-label" for="social_login">
				{{ __('admin.social_login') }}
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="google" @if (isset($permissions) && in_array('google', $permissions)) checked="checked" @endif type="checkbox" id="google">
			<label class="form-check-label" for="google">
				Google
			</label>
		</div>
	</div>
</div>

<div class="row mb-3">
	<div class="col-sm-10 offset-sm-2">
		<div class="form-check">
			<input class="form-check-input check" name="permissions[]" value="pwa" @if (isset($permissions) && in_array('pwa', $permissions)) checked="checked" @endif type="checkbox" id="pwa">
			<label class="form-check-label" for="pwa">
				PWA
			</label>
		</div>
	</div>
</div>
