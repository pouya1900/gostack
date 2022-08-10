@extends('layouts.app')

@section('title'){{ trans('users.upload').' - ' }}@endsection

@section('css')
    <link href="{{ asset('public/js/tagin/tagin.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <section class="section section-sm">
        <div class="container pt-5">
            <div >
                <a href="{{url('upload/video')}}">upload video</a>
            </div>

            <div class="row">

                @if (auth()->user()->status == 'active')

                    @if ($settings->limit_upload_user == 0
                        || auth()->user()->dailyUploads() < $settings->limit_upload_user
                        || auth()->user()->isSuperAdmin()
                        )

                        <div class="col-md-7 mb-3">
                            <!-- form start -->
                            <form method="POST" action="{{ url('upload') }}" enctype="multipart/form-data"
                                  id="formUpload" files="true">

                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                <!-- wrapper upload -->
                                <div class="filer-input-dragDrop position-relative" id="draggable">

                                    <input type="file" accept="image/*"  name="photo" id="filePhoto">

                                    <!-- previewPhoto -->
                                    <div class="previewPhoto"></div><!-- previewPhoto -->

                                    <span class="text-dark btn-remove-photo display-none c-pointer" id="removePhoto">
          <i class="bi bi-x-lg text-white"></i>
        </span>

                                    <div class="filer-input-inner">
                                        <div class="filer-input-icon">
                                            <i class="bi bi-image"></i>
                                        </div>
                                        <div class="filer-input-text">
                                            <h3 class="mb-2 fw-light">{{ trans('misc.click_select_image') }}</h3>
                                            <h3 class="fw-light">{{ trans('misc.max_size') }}
                                                : {{  $settings->min_width_height_image.' - '.Helper::formatBytes($settings->file_size_allowed * 1024)}} </h3>
                                        </div>
                                    </div>
                                </div><!-- ./ wrapper upload -->

                                <ul class="list-inline">
                                    <li class="list-inline-item"><i
                                            class="bi bi-dot me-1"></i> {{ trans('conditions.terms') }}</li>
                                    <li class="list-inline-item"><i
                                            class="bi bi-dot me-1"></i> {{ trans('conditions.upload_max', ['limit' => $settings->limit_upload_user == 0 ? strtolower(trans('admin.unlimited')) : $settings->limit_upload_user ]) }}
                                    </li>
                                    <li class="list-inline-item"><i
                                            class="bi bi-dot me-1"></i> {{ trans('conditions.sex_content') }}</li>
                                    <li class="list-inline-item"><i
                                            class="bi bi-dot me-1"></i> {{ trans('conditions.own_images') }}</li>
                                </ul>

                        </div>

                        <!-- col-md-12 -->
                        <div class="col-md-5">

                            <div class="card border-0">

                                <div class="card-body p-0">

                                    <div class="mb-3">
                                        <input type="text" required class="form-control" id="title" name="title"
                                               placeholder="{{ trans('admin.title') }}">
                                        <div class="alert alert-info py-2 mt-2">
                                            <small><i
                                                    class="bi-info-circle me-2"></i> {{ trans('misc.info_data_iptc') }}
                                            </small>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <input type="text" required class="form-control tagin" id="tagInput" name="tags"
                                               placeholder="{{ trans('misc.tags') }}">
                                        <small class="d-block">* {{ trans('misc.add_tags_guide') }}
                                            ({{trans('misc.maximum_tags', ['limit' => $settings->tags_limit ]) }}
                                            )</small>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <select name="categories_id" class="form-select" id="input-category">

                                            @foreach (Categories::where('mode','on')->orderBy('name')->get() as $category)
                                                <option value="{{$category->id}}">
                                                    {{ Lang::has('categories.' . $category->slug) ? __('categories.' . $category->slug) : $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="input-category">{{ trans('misc.category') }}</label>
                                    </div>

                                    @if ($settings->sell_option == 'on'
                                        && $settings->who_can_sell == 'all'
                                        || $settings->sell_option == 'on'
                                        && $settings->who_can_sell == 'admin'
                                        && auth()->user()->isSuperAdmin()
                                        )

                                        @if ($settings->free_photo_upload == 'on')

                                            <div class="form-floating mb-3">
                                                <select name="item_for_sale" class="form-select" id="itemForSale">
                                                    <option value="free">{{ trans('misc.no_free') }}</option>
                                                    <option value="sale">{{ trans('misc.yes_for_sale') }}</option>
                                                </select>
                                                <label for="itemForSale">{{ trans('misc.item_for_sale') }}</label>
                                            </div>

                                        @else
                                            <input type="hidden" name="item_for_sale" value="sale">
                                        @endif

                                        <div
                                            class="form-floating mb-3 @if ($settings->free_photo_upload == 'on') display-none @endif"
                                            id="priceBox">
                                            <input type="number" @if ($settings->default_price_photos) readonly
                                                   value="{{$settings->default_price_photos}}" @endif name="price"
                                                   class="form-control onlyNumber" autocomplete="off" id="price"
                                                   placeholder="{{ trans('misc.price') }}">

                                            <small class="d-block fw-bold mb-2 mt-2">
                                                @if (auth()->user()->author_exclusive == 'yes')
                                                    * {{ trans('misc.user_gain', ['percentage' => (100 - $settings->fee_commission)]) }}
                                                @else
                                                    * {{ trans('misc.user_gain', ['percentage' => (100 - $settings->fee_commission_non_exclusive)]) }}
                                                @endif

                                                <i class="bi bi-info-circle showTooltip ms-1"
                                                   title="{{trans('misc.earnings_information')}}"></i>
                                            </small>
                                            <label for="price">({{ $settings->currency_symbol }}
                                                ) {{ trans('misc.price') }}</label>

                                            @if (! $settings->default_price_photos)
                                                <div class="alert alert-primary">
                                                    <h6>{{trans('misc.price_formats')}}</h6>
                                                    <ul class="list-unstyled">
                                                        <li>{{trans('misc.small_photo_price')}} {{ $settings->currency_position == 'left' ? $settings->currency_symbol : null }}
                                                            <span
                                                                id="s-price">0</span>{{ $settings->currency_position == 'right' ? $settings->currency_symbol : null }}
                                                        </li>
                                                        <li>{{trans('misc.medium_photo_price')}} {{ $settings->currency_position == 'left' ? $settings->currency_symbol : null }}
                                                            <span
                                                                id="m-price">0</span>{{ $settings->currency_position == 'right' ? $settings->currency_symbol : null }}
                                                        </li>
                                                        <li>{{trans('misc.large_photo_price')}} {{ $settings->currency_position == 'left' ? $settings->currency_symbol : null }}
                                                            <span
                                                                id="l-price">0</span>{{ $settings->currency_position == 'right' ? $settings->currency_symbol : null }}
                                                        </li>
                                                        <li>{{trans('misc.vector_photo_price')}} {{ $settings->currency_position == 'left' ? $settings->currency_symbol : null }}
                                                            <span
                                                                id="v-price">0</span>{{ $settings->currency_position == 'right' ? $settings->currency_symbol : null }}
                                                            <small>{{trans('misc.if_included')}}</small></li>
                                                    </ul>
                                                    <small>{{trans('misc.price_minimum')}} {{Helper::amountFormat($settings->min_sale_amount)}}
                                                        - {{trans('misc.price_maximum')}} {{Helper::amountFormat($settings->max_sale_amount)}}</small>
                                                </div>
                                            @endif
                                        </div>

                                @endif

                                <!-- Start Form Group -->
                                    <div
                                        class="form-floating mb-3 options_free @if ($settings->free_photo_upload == 'off') display-none @endif">
                                        <select name="how_use_image" class="form-select" id="how_use_image">
                                            <option value="free">{{ trans('misc.use_free') }}</option>
                                            <option value="free_personal">{{ trans('misc.use_free_personal') }}</option>
                                            <option
                                                value="editorial_only">{{ trans('misc.use_editorial_only') }}</option>
                                            <option value="web_only">{{ trans('misc.use_web_only') }}</option>
                                        </select>
                                        <label for="how_use_image">{{ trans('misc.how_use_image') }}</label>
                                    </div>

                                    <!-- Start Form Group -->
                                    <div class="form-floating mb-3">
                                        <select name="type_image" class="form-select" id="typeImage">
                                            <option value="image">{{ trans('misc.image') }}</option>
                                            <option value="vector">{{ trans('misc.image_and_vector_graphic') }} (AI,
                                                EPS, PSD, SVG, CDR)
                                            </option>
                                        </select>
                                        <label for="typeImage">{{ trans('misc.type_image') }}</label>

                                        <div class="w-100 display-none" id="vector">
                                            <button type="button" class="btn btn-light w-100" id="upload_file"
                                                    style="margin-top: 10px;border: 1px dashed #bdbdbd;padding: 12px;">
                                                <i class="bi bi-cloud-arrow-up me-1"></i> {{trans('misc.select_file')}}
                                                (AI, EPS, PSD, SVG, CDR)
                                            </button>

                                            <input type="file" name="file" id="uploadFile" style="visibility: hidden;">
                                        </div>

                                        <small class="d-block mt-2" id="fileDocument"></small>
                                    </div>

                                    <div
                                        class="form-check form-switch form-switch-md mb-3 options_free @if ($settings->free_photo_upload == 'off') display-none @endif">
                                        <input class="form-check-input" name="attribution_required" type="checkbox"
                                               checked value="yes" id="flexSwitchCheckDefault">
                                        <label class="form-check-label"
                                               for="flexSwitchCheckDefault">{{ trans('misc.attribution_required') }}</label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" placeholder="{{ trans('misc.description') }}"
                                                  name="description" id="input-description"
                                                  style="height: 100px"></textarea>
                                        <label for="input-description">{{ trans('admin.description') }}
                                            ({{ trans('misc.optional') }})</label>
                                    </div>
                                    <!-- Alert -->
                                    <div class="alert alert-danger display-none" id="dangerAlert">
                                        <ul class="list-unstyled mb-0" id="showErrors"></ul>
                                    </div><!-- Alert -->

                                    <div class="box-footer text-center">
                                        <button type="submit" id="upload" class="btn btn-lg btn-custom w-100"
                                                data-msg-processing="{{trans('misc.processing')}}"
                                                data-error="{{trans('misc.error')}}"
                                                data-msg-error="{{trans('misc.err_internet_disconnected')}}">
                                            <i class="bi bi-cloud-arrow-up-fill me-1"></i> {{ trans('users.upload') }}
                                        </button>
                                    </div><!-- /.box-footer -->
                                    </form>
                                </div>
                            </div>

                        </div>
                        <!-- col-md-12-->

                    @else
                        <h3 class="mt-0 text-center fw-light">
			<span class="w-100 d-block mb-4 display-1 text-warning">
				<i class="bi bi-exclamation-triangle-fill"></i>
			</span>

                            {{trans('misc.limit_uploads_user')}}
                        </h3>
                    @endif

                @else
                    <h3 class="mt-0 text-center fw-light">
			 <span class="w-100 d-block mb-4 display-1 text-warning">
 				<i class="bi bi-exclamation-triangle-fill"></i>
 			</span>

                        {{trans('misc.confirm_email')}} <span class="fw-bold">{{auth()->user()->email}}</span>
                    </h3>
                @endif
                {{-- Verify User Active --}}

            </div><!-- row -->
        </div><!-- container -->
    </section>
@endsection

@section('javascript')
    <script src="{{ asset('public/js/tagin/tagin.min.js') }}" type="text/javascript"></script>

    <script type="text/javascript">

        const tagin = new Tagin(document.querySelector('.tagin'), {
            enter: true,
            placeholder: '{{ trans("misc.add_tag") }}',
        });

        $(".tagin").on('change', function () {
            var input = $(this).siblings('.tagin-wrapper');
            var maxLen = {{$settings->tags_limit}};

            if (input.children('span.tagin-tag').length >= maxLen) {
                input.children('input.tagin-input').addClass('d-none');
            } else {
                input.children('input.tagin-input').removeClass('d-none');
            }
        });

        function replaceString(string) {
            return string.replace(/[\-\_\.\+]/ig, ' ')
        }

        $('#removePhoto').click(function () {
            $('#filePhoto').val('');
            $('#title').val('');
            $('.previewPhoto').css({backgroundImage: 'none'}).hide();
            $('.filer-input-dragDrop').removeClass('hoverClass');
            $(this).hide();
        });

        //================== START FILE IMAGE FILE READER
        $("#filePhoto").on('change', function () {

            var loaded = false;
            if (window.File && window.FileReader && window.FileList && window.Blob) {
                if ($(this).val()) { //check empty input filed
                    oFReader = new FileReader(), rFilter = /^(?:image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/png|image)$/i;
                    if ($(this)[0].files.length === 0) {
                        return
                    }


                    var oFile = $(this)[0].files[0];
                    var fsize = $(this)[0].files[0].size; //get file size
                    var ftype = $(this)[0].files[0].type; // get file type


                    {{--if (!rFilter.test(oFile.type)) {--}}
                    {{--    $('#filePhoto').val('');--}}
                    {{--    $('.popout').addClass('popout-error').html("{{ trans('misc.formats_available') }}").fadeIn(500).delay(5000).fadeOut();--}}
                    {{--    return false;--}}
                    {{--}--}}

                    var allowed_file_size = {{$settings->file_size_allowed * 1024}};

                    if (fsize > allowed_file_size) {
                        $('#filePhoto').val('');
                        $('.popout').addClass('popout-error').html("{{trans('misc.max_size').': '.Helper::formatBytes($settings->file_size_allowed * 1024)}}").fadeIn(500).delay(5000).fadeOut();
                        return false;
                    }
                    <?php $dimensions = explode('x', $settings->min_width_height_image); ?>

                        oFReader.onload = function (e) {

                        var image = new Image();
                        image.src = oFReader.result;

                        image.onload = function () {

                            if (image.width < {{ $dimensions[0] }}) {
                                $('#filePhoto').val('');
                                $('.popout').addClass('popout-error').html("{{trans('misc.width_min',['data' => $dimensions[0]])}}").fadeIn(500).delay(5000).fadeOut();
                                return false;
                            }

                            if (image.height < {{ $dimensions[1] }}) {
                                $('#filePhoto').val('');
                                $('.popout').addClass('popout-error').html("{{trans('misc.height_min',['data' => $dimensions[1]])}}").fadeIn(500).delay(5000).fadeOut();
                                return false;
                            }

                            $('.previewPhoto').css({backgroundImage: 'url(' + e.target.result + ')'}).show();
                            $('#removePhoto').show();
                            $('.filer-input-dragDrop').addClass('hoverClass');
                            var _filname = oFile.name;
                            var fileName = _filname.substr(0, _filname.lastIndexOf('.'));
                            $('#title').val(replaceString(fileName));
                        };// <<--- image.onload


                    }

                    oFReader.readAsDataURL($(this)[0].files[0]);

                }
            } else {
                $('.popout').html('Can\'t upload! Your browser does not support File API! Try again with modern browsers like Chrome or Firefox.').fadeIn(500).delay(5000).fadeOut();
                return false;
            }
        });

        $('input[type="file"]').attr('title', window.URL ? ' ' : '');

        $('#itemForSale').on('change', function () {
            if ($(this).val() == 'sale') {
                $('#priceBox').slideDown();
                $('.options_free').slideUp();

            } else {
                $('#priceBox').slideUp();
                $('.options_free').slideDown();
            }
        });

        $('#typeImage').on('change', function () {
            if ($(this).val() == 'vector') {
                $('#vector').slideDown();
            } else {
                $('#vector').slideUp('fast');
                $('#uploadFile').val('');
                $('#fileDocument').html('');
            }
        });

        $(".onlyNumber").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
                // Allow: Ctrl+A, Command+A
                (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                // Allow: home, end, left, right, down, up
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });

        $(document).on('click', '#deleteFile', function () {
            $('#uploadFile').val('');
            $('#fileDocument').html('');
        });

        //================== START FILE - FILE READER
        $("#uploadFile").change(function () {

            $('#fileDocument').html('');

            var loaded = false;
            if (window.File && window.FileReader && window.FileList && window.Blob) {
                if ($(this).val()) { //check empty input filed
                    if ($(this)[0].files.length === 0) {
                        return
                    }

                    var oFile = $(this)[0].files[0];
                    var fsize = $(this)[0].files[0].size; //get file size
                    var ftype = $(this)[0].files[0].type; // get file type

                    var allowed_file_size = {{$settings->file_size_allowed_vector * 1024}};

                    if (fsize > allowed_file_size) {
                        $('.popout').addClass('popout-error').html("{{trans('misc.max_size_vector').': '.Helper::formatBytes($settings->file_size_allowed_vector * 1024)}}").fadeIn(500).delay(4000).fadeOut();
                        $(this).val('');
                        return false;
                    }

                    $('#fileDocument').html('<i class="fa fa-paperclip"></i> <strong class="text-muted"><em>' + oFile.name + '</em></strong> - <a href="javascript:void(0);" id="deleteFile" class="text-danger">{{trans('misc.delete')}}</a>');

                }
            } else {
                alert('Can\'t upload! Your browser does not support File API! Try again with modern browsers like Chrome or Firefox.');
                return false;
            }
        });
        //================== END FILE - FILE READER ==============>

        $('#price').on('keyup', function () {

            var valueOriginal = $('.onlyNumber').val();
            var value = parseFloat($('.onlyNumber').val());
            var element = $(this).val();

            if (element != '') {

                if (valueOriginal >= {{$settings->min_sale_amount}} && valueOriginal <= {{$settings->max_sale_amount}}) {
                    var amountSmall = value;
                } else {
                    amountSmall = 0;
                }
                var amountMedium = (amountSmall * 2);
                var amountLarge = (amountSmall * 3);
                var amountVector = (amountSmall * 4);


                $('#s-price').html(amountSmall);
                $('#m-price').html(amountMedium);
                $('#l-price').html(amountLarge);
                $('#v-price').html(amountVector);

            }

            if (valueOriginal == '') {
                $('#s-price').html('0');
                $('#m-price').html('0');
                $('#l-price').html('0');
                $('#v-price').html('0');
            }
        });
    </script>


@endsection
