<div class="modal fade" id="modal2fa" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
	<div class="modal-dialog modal- modal-dialog-centered" role="document">
		<div class="modal-content">
          <div class="modal-body p-0">
            <div class="card bg-white shadow border-0">
							<div class="card-body px-lg-5 py-lg-5 position-relative">
								<div class="mb-3">

									<h6><i class="bi bi-shield-lock me-1"></i> {{ trans('misc.two_step_auth') }}</h6>

									<small>{{ trans('misc.2fa_title_modal') }}</small>
								</div>

								<form method="post" action="{{ url('verify/2fa') }}" id="formVerify2fa">
									@csrf

									<input type="number" autocomplete="off" id="onlyNumber" onKeyPress="if(this.value.length==4) return false;" class="form-control mb-2" name="code" placeholder="{{ trans('misc.enter_code') }}">

									<small class="form-text text-muted m-0">
                    <a href="javascript:void(0);" class="resend_code">
											<i class="bi bi-arrow-counterclockwise"></i> <span id="resendCode">{{ trans('misc.resend_code') }}</span>
                    </a>
                  </small>

									<div class="alert alert-danger display-none mt-2" id="errorModal2fa">
										<ul class="list-unstyled m-0" id="showErrorsModal2fa"></ul>
									</div>

									<div class="text-center">
										<button type="submit" id="btn2fa" class="btn btn-dark bg-sm w-100 mt-3">
											<i></i> {{ trans('auth.send') }}
										</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
	</div>
</div><!-- End Modal 2FA -->
