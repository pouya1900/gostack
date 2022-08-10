<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\AdminSettings;
use App\Models\Images;
use App\Models\Stock;
use App\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use League\ColorExtractor\Color;
use League\ColorExtractor\ColorExtractor;
use Illuminate\Support\Facades\Storage;
use League\ColorExtractor\Palette;
use Image;


class BulkUploadController extends Controller {

	public function __construct(AdminSettings $settings, Request $request) {
		$this->settings = $settings::first();
		$this->request = $request;
	}

	public function bulkUpload()
	{
		return view('admin.bulk-upload');
	}//<--- End view method

	protected function validator(array $data)
  {
     Validator::extend('ascii_only', function($attribute, $value, $parameters){
       return !preg_match('/[^x00-x7F\-]/i', $value);
   });

   $sizeAllowed = $this->settings->file_size_allowed * 1024;

   $dimensions = explode('x',$this->settings->min_width_height_image);

   if ($this->settings->currency_position == 'right') {
     $currencyPosition =  2;
   } else {
     $currencyPosition =  null;
   }

  $messages = [
   'photo.required' => trans('misc.please_select_image'),
   'photo.*.max'   => trans('misc.max_size').' '.Helper::formatBytes($sizeAllowed, 1),
	 'photo.*.mimes' =>  trans('misc.photo_formats_available', ['formats' => 'JPG, GIF, PNG']),
	 'photo.*.dimensions' =>  trans('misc.photo_dimensions_validation', ['width' => $dimensions[0], 'height' => $dimensions[1]])
 ];

   // Create Rules
   return Validator::make($data, [
    	 'photo.*' => 'required|mimes:jpg,gif,png,jpe,jpeg|dimensions:min_width='.$dimensions[0].',min_height='.$dimensions[1].'|max:'.$this->settings->file_size_allowed.'',
       'title'   => 'required|min:3|max:255',
       'tags'    => 'required',
       'price' => 'required_if:item_for_sale,==,sale|integer|min:'.$this->settings->min_sale_amount.'|max:'.$this->settings->max_sale_amount.'',
     ], $messages);
   }

 // Store Image
  protected function bulkUploadStore()
  {
    $files = $this->request->file('photo');

    foreach ($files as $file) {

    //======= EXIF DATA
    $exif_data  = @exif_read_data($file, 0, true);
    if (isset($exif_data['COMPUTED']['ApertureFNumber'])) : $ApertureFNumber = $exif_data['COMPUTED']['ApertureFNumber']; else: $ApertureFNumber = ''; endif;

    if (isset($exif_data['EXIF']['ISOSpeedRatings'][0]))
      : $ISO = 'ISO '.$exif_data['EXIF']['ISOSpeedRatings'][0];
      elseif(!isset($exif_data['EXIF']['ISOSpeedRatings'][0]) && isset($exif_data['EXIF']['ISOSpeedRatings']))
      : $ISO = 'ISO '.$exif_data['EXIF']['ISOSpeedRatings'];
    else: $ISO = '';
  endif;

    if (isset($exif_data['EXIF']['ExposureTime'])) : $ExposureTime = $exif_data['EXIF']['ExposureTime']; else: $ExposureTime = ''; endif;
    if (isset($exif_data['EXIF']['FocalLength'])) : $FocalLength = $exif_data['EXIF']['FocalLength']; else: $FocalLength = ''; endif;
    if (isset($exif_data['IFD0']['Model'])) : $camera = $exif_data['IFD0']['Model']; else: $camera = ''; endif;
    $exif = $FocalLength.' '.$ApertureFNumber.' '.$ExposureTime. ' '.$ISO;
    //dd($exif_data);

    $pathFiles      = config('path.files');
    $pathLarge      = config('path.large');
    $pathPreview    = config('path.preview');
    $pathMedium     = config('path.medium');
    $pathSmall      = config('path.small');
    $pathThumbnail  = config('path.thumbnail');
    $watermarkSource = url('public/img', $this->settings->watermark);

    $input = $this->request->all();

    if (! $this->request->price) {
      $price = 0;
    } else {
      $price = $input['price'];
    }

      $_type = true;
      $replace = ['+','-','_','.','*'];
      $input['title']  = str_replace($replace, ' ', Helper::fileNameOriginal($file->getClientOriginalName()));

      $tags = explode(' ', $input['title']);

      if ($this->request->tags == '') {
 		 	   $input['tags'] = $tags[0];
 		 }

      // Set price min
      if ($this->request->item_for_sale == 'sale'
           && $this->request->price == ''
           || $this->request->item_for_sale == 'sale'
           && $this->request->price < $this->settings->min_sale_amount
         ) {
 		 	   $price = $this->settings->min_sale_amount;
          $input['price'] = $this->settings->min_sale_amount;
 		 } else if($this->request->item_for_sale == 'sale'
       && $this->request->price == ''
       || $this->request->item_for_sale == 'sale'
       && $this->request->price > $this->settings->max_sale_amount) {
        $price = $this->settings->max_sale_amount;
        $input['price'] = $this->settings->max_sale_amount;
      }

    $input['tags'] = Helper::cleanStr($input['tags']);
    $tags = $input['tags'];

    if (strlen($tags) == 1) {
      return response()->json([
          'success' => false,
          'errors' => ['error' => trans('validation.required', ['attribute' => trans('misc.tags')])],
      ]);
    }

    $validator = $this->validator($input);

    if ($validator->fails()) {
      return response()->json([
        'files' => [
          0 => [
            'uploaded' => true,
          ]
        ],
        'hasWarnings' => true,
        'warnings' => $validator->getMessageBag()->toArray(),
        'isSuccess' => false
      ]);
    }

    $photo          = $file;
    $fileSizeLarge  = Helper::formatBytes($photo->getSize(), 1);
    $extension      = $photo->getClientOriginalExtension();
    $originalName   = Helper::fileNameOriginal($photo->getClientOriginalName());
    $widthHeight    = getimagesize($photo);
    $large          = strtolower(auth()->user()->id.time().str_random(100).'.'.$extension );
    $medium         = strtolower(auth()->user()->id.time().str_random(100).'.'.$extension );
    $small          = strtolower(auth()->user()->id.time().str_random(100).'.'.$extension );
    $preview        = strtolower(str_slug($input['title'], '-').'-'.auth()->user()->id.time().str_random(10).'.'.$extension );
    $thumbnail      = strtolower(str_slug($input['title'], '-').'-'.auth()->user()->id.time().str_random(10).'.'.$extension );

    $watermark   = Image::make($watermarkSource);
    $x = 0;

         $width    = $widthHeight[0];
         $height   = $widthHeight[1];

        if ($width > $height) {

          if ($width > 1280) : $_scale = 1280; else: $_scale = 900; endif;
              $previewWidth = 850 / $width;
              $mediumWidth = $_scale / $width;
              $smallWidth = 640 / $width;
              $thumbnailWidth = 280 / $width;
        } else {

          if ($width > 1280) : $_scale = 960; else: $_scale = 800; endif;
              $previewWidth = 480 / $width;
              $mediumWidth = $_scale / $width;
              $smallWidth = 480 / $width;
              $thumbnailWidth = 190 / $width;
        }

          //======== PREVIEW
          $scale    = $previewWidth;
          $widthPreview = ceil($width * $scale);

          $imgPreview  = Image::make($photo)->orientate()->resize($widthPreview, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
          })->encode($extension);

          //======== Medium
          $scaleM  = $mediumWidth;
          $widthMedium = ceil($width * $scaleM);

          $imgMedium  = Image::make($photo)->orientate()->resize($widthMedium, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
          })->encode($extension);

          //======== Small
          $scaleSmall  = $smallWidth;
          $widthSmall = ceil($width * $scaleSmall);

          $imgSmall  = Image::make($photo)->orientate()->resize($widthSmall, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
          })->encode($extension);

          //======== Thumbnail
          $scaleThumbnail  = $thumbnailWidth;
          $widthThumbnail = ceil($width * $scaleThumbnail);

          $imgThumbnail  = Image::make($photo)->orientate()->resize($widthThumbnail, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
          })->encode($extension);


    //======== Large Image
    $photo->storePubliclyAs($pathLarge, $large);

    //========  Preview Image
    Storage::put($pathPreview.$preview, $imgPreview, 'public');
    $url = Storage::url($pathPreview.$preview);

    //======== Medium Image
    Storage::put($pathMedium.$medium, $imgMedium, 'public');
    $urlMedium = Storage::url($pathMedium.$medium);

    //======== Small Image
    Storage::put($pathSmall.$small, $imgSmall, 'public');
    $urlSmall = Storage::url($pathSmall.$small);

    //======== Thumbnail Image
    Storage::put($pathThumbnail.$thumbnail, $imgThumbnail, 'public');

    //=========== Colors
    $palette   = Palette::fromFilename($urlSmall);
    $extractor = new ColorExtractor($palette);

    // it defines an extract method which return the most “representative” colors
    $colors = $extractor->extract(5);

    // $palette is an iterator on colors sorted by pixel count
    foreach ($colors as $color) {

      $_color[] = trim(Color::fromIntToHex($color), '#') ;
    }

    $colors_image = implode( ',', $_color);

    $token_id = str_random(200);

		$dataIPTC = Helper::dataIPTC($file);

		if ($dataIPTC) {
			if (isset($dataIPTC['title'])) {
				$input['title'] = $dataIPTC['title'];
			}

			if (isset($dataIPTC['tags'])) {

				foreach ($dataIPTC['tags'] as $_tags) {
					$allTags[] = $_tags;
				}
				$__tags = implode(', ', $allTags);

				$tags = $__tags;
			}
		}

    $sql = new Images;
    $sql->thumbnail            = $thumbnail;
    $sql->preview              = $preview;
    $sql->title                = ucfirst(trim($input['title']));
    $sql->categories_id        = $this->request->categories_id;
    $sql->user_id              = auth()->user()->id;
    $sql->status               = 'active';
    $sql->token_id             = $token_id;
    $sql->tags                 = mb_strtolower($tags);
    $sql->extension            = strtolower($extension);
    $sql->colors               = $colors_image;
    $sql->exif                 = trim($exif);
    $sql->camera               = $camera;
    $sql->how_use_image        = $this->request->how_use_image;
    $sql->attribution_required = $this->request->attribution_required;
    $sql->original_name        = $originalName;
    $sql->price                = $price;
    $sql->item_for_sale        = $this->request->item_for_sale ? $this->request->item_for_sale : 'free';
    $sql->save();

    // ID INSERT
    $imageID = $sql->id;

    // Save Vector DB
    if ($this->request->hasFile('file')) {

        $file->storePubliclyAs($pathFiles, $fileVector);

        $stockVector             = new Stock;
        $stockVector->images_id  = $imageID;
        $stockVector->name       = $fileVector;
        $stockVector->type       = 'vector';
        $stockVector->extension  = $extensionVector;
        $stockVector->resolution = '';
        $stockVector->size       = $sizeFileVector;
        $stockVector->token      = $token_id;
        $stockVector->save();
    }

    // INSERT STOCK IMAGES
    $lResolution = list($w, $h) = $widthHeight;
    $lSize       = $fileSizeLarge;

    $mResolution = list($_w, $_h) = getimagesize($urlMedium);
    $mSize      = Helper::getFileSize($urlMedium);

    $smallResolution = list($__w, $__h) = getimagesize($urlSmall);
    $smallSize       = Helper::getFileSize($urlSmall);

  $stockImages = [
      ['name' => $large, 'type' => 'large', 'resolution' => $w.'x'.$h, 'size' => $lSize ],
      ['name' => $medium, 'type' => 'medium', 'resolution' => $_w.'x'.$_h, 'size' => $mSize ],
      ['name' => $small, 'type' => 'small', 'resolution' => $__w.'x'.$__h, 'size' => $smallSize ],
    ];

    foreach ($stockImages as $key) {
      $stock             = new Stock;
      $stock->images_id  = $imageID;
      $stock->name       = $key['name'];
      $stock->type       = $key['type'];
      $stock->extension  = $extension;
      $stock->resolution = $key['resolution'];
      $stock->size       = $key['size'];
      $stock->token      = $token_id;
      $stock->save();

    }

		return response()->json([
				'files' => [
					0 => [
						'extension' => $extension,
						'format' => 'image',
						'name' => $thumbnail,
						'replaced' => false,
						'size2' => $lSize,
						'uploaded' => true,
					]
				],
				'hasWarnings' => false,
				'isSuccess' => true,
		]);

   }// End foreach
  }

	public function destroy()
	{
		$image = Images::whereThumbnail($this->request->file)->first();

		if (! $image) {
      return false;
    }

		//<---- ALL RESOLUTIONS IMAGES
		$stocks = Stock::where('images_id', '=', $image->id)->get();

		foreach ($stocks as $stock) {
			// Delete Stock
			Storage::delete(config('path.uploads').$stock->type.'/'.$stock->name);

			// Delete Stock Vector
			Storage::delete(config('path.files').$stock->name);

			$stock->delete();

		}//<--- End foreach

		// Delete preview
		Storage::delete(config('path.preview').$image->preview);

		// Delete thumbnail
		Storage::delete(config('path.thumbnail').$image->thumbnail);

		$image->delete();

		return response()->json([
        'success' => true
    ]);
	}
}
