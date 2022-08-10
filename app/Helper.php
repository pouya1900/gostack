<?php

namespace App;

use League\ColorExtractor\Color;
use League\ColorExtractor\ColorExtractor;
use League\ColorExtractor\Palette;
use App\Models\Pages;
use Image;
use App\Models\AdminSettings;


class Helper
{
	// spaces
	public static function spacesUrlFiles($string) {
	  return ( preg_replace('/(\s+)/u','_',$string ) );

	}

	public static function spacesUrl($string) {
	  return ( preg_replace('/(\s+)/u','+',trim( $string ) ) );

	}

	public static function removeLineBreak( $string )  {
		return str_replace(array("\r\n", "\r"), "", $string);
	}

    public static function hyphenated($url)
    {
        $url = strtolower($url);
        //Rememplazamos caracteres especiales latinos
        $find = array('á','é','í','ó','ú','ñ');
        $repl = array('a','e','i','o','u','n');
        $url = str_replace($find,$repl,$url);
        // Añaadimos los guiones
        $find = array(' ', '&', '\r\n', '\n', '+');
                $url = str_replace ($find, '-', $url);
        // Eliminamos y Reemplazamos demás caracteres especiales
        $find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
        $repl = array('', '-', '');
        $url = preg_replace ($find, $repl, $url);
        //$palabra=trim($palabra);
        //$palabra=str_replace(" ","-",$palabra);
        return $url;
        }

	// Text With (2) line break
	public static function checkTextDb( $str ) {

		//$str = trim( self::spaces( $str ) );
		if( mb_strlen( $str, 'utf8' ) < 1 ) {
			return false;
		}
		$str = preg_replace('/(?:(?:\r\n|\r|\n)\s*){3}/s', "\r\n\r\n", $str);
		$str = trim($str,"\r\n");

		return $str;
	}

	public static function checkText( $str ) {

		//$str = trim( self::spaces( $str ) );
		if( mb_strlen( $str, 'utf8' ) < 1 ) {
			return false;
		}

		$str = nl2br(e($str));
		$str = str_replace( array( chr( 10 ), chr( 13 ) ), '' , $str );

		$str = stripslashes( $str );

		return $str;
	}

	public static function formatNumber( $number ) {
    if( $number >= 1000 &&  $number < 1000000 ) {

       return number_format( $number/1000, 1 ). "k";
    } else if( $number >= 1000000 ) {
		return number_format( $number/1000000, 1 ). "M";
	} else {
        return $number;
    }
   }//<<<<--- End Function

   public static function formatNumbersStats( $number ) {

    if( $number >= 100000000 ) {
		return '<span class=".numbers-with-commas counter">'.number_format( $number/1000000, 0 ). "</span>M";
	} else {
        return '<span class=".numbers-with-commas counter">'.number_format( $number ).'</span>';
    }
   }//<<<<--- End Function

   public static function spaces($string) {
	  return ( preg_replace('/(\s+)/u',' ',$string ) );

	}

	public static function resizeImage( $image, $width, $height, $scale, $imageNew = null ) {

		list($imagewidth, $imageheight, $imageType) = getimagesize($image);
	$imageType = image_type_to_mime_type($imageType);
	$newImageWidth = ceil($width * $scale);
	$newImageHeight = ceil($height * $scale);
	$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
	switch($imageType) {
		case "image/gif":
			$source=imagecreatefromgif($image);
			imagefill( $newImage, 0, 0, imagecolorallocate( $newImage, 255, 255, 255 ) );
			imagealphablending( $newImage, TRUE );
			break;
	    case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
			$source=imagecreatefromjpeg($image);
			break;
	    case "image/png":
		case "image/x-png":
			$source=imagecreatefrompng($image);
			imagealphablending( $newImage, false );
			imagesavealpha( $newImage, true );

			//imagefill( $newImage, 0, 0, imagecolorallocate( $newImage, 255, 255, 255 ) );
			//imagealphablending( $newImage, TRUE );
			break;
  	}
	imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);

	switch($imageType) {
		case "image/gif":
	  		imagegif( $newImage, $imageNew );
			break;
      	case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
	  		imagejpeg( $newImage, $imageNew ,90 );
			break;
		case "image/png":
		case "image/x-png":
			imagepng( $newImage, $imageNew );
			break;
    }

	chmod($image, 0777);
	return $image;
	}

public static function resizeImageFixed( $image, $width, $height, $imageNew = null ) {

	list($imagewidth, $imageheight, $imageType) = getimagesize($image);
	$imageType = image_type_to_mime_type($imageType);
	$newImage = imagecreatetruecolor($width,$height);

	switch($imageType) {
		case "image/gif":
			$source=imagecreatefromgif($image);
			imagefill( $newImage, 0, 0, imagecolorallocate( $newImage, 255, 255, 255 ) );
			imagealphablending( $newImage, TRUE );
			break;
	    case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
			$source=imagecreatefromjpeg($image);
			break;
	    case "image/png":
		case "image/x-png":
			$source=imagecreatefrompng($image);
			imagealphablending( $newImage, false );
			imagesavealpha( $newImage, true );

			break;
  	}
	if( $width/$imagewidth > $height/$imageheight ){
        $nw = $width;
        $nh = ($imageheight * $nw) / $imagewidth;
        $px = 0;
        $py = ($height - $nh) / 2;
    } else {
        $nh = $height;
        $nw = ($imagewidth * $nh) / $imageheight;
        $py = 0;
        $px = ($width - $nw) / 2;
    }

	imagecopyresampled($newImage,$source,$px, $py, 0, 0, $nw, $nh, $imagewidth, $imageheight);

	switch($imageType) {
		case "image/gif":
	  		imagegif($newImage,$imageNew);
			break;
      	case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
	  		imagejpeg($newImage,$imageNew,90);
			break;
		case "image/png":
		case "image/x-png":
			imagepng($newImage,$imageNew);
			break;
    }

		chmod($image, 0777);
		return $image;
	}

	public static function getHeight( $image ) {
		$size   = getimagesize( $image );
		$height = $size[1];
		return $height;
	}

	public static function getWidth( $image ) {
		$size  = getimagesize($image);
		$width = $size[0];
		return $width;
	}
	public static function formatBytes($size, $precision = 2, $suffixes = true) {
    $base = log($size, 1024);
		if ($suffixes == true) {
			$unit = array('', 'kB', 'MB', 'GB', 'TB');
			$size = $unit[floor($base)];
		} else {
			$size = null;
		}

    return round(pow(1024, $base - floor($base)), $precision) . $size;
  }

	public static function removeHTPP($string){
		$string = preg_replace('#^https?://#', '', $string);
		return $string;
	}

	public static function Array2Str( $kvsep, $entrysep, $a ){
		$str = "";
			foreach ( $a as $k => $v ){
				$str .= "{$k}{$kvsep}{$v}{$entrysep}";
				}
		return $str;
	}

	public static function removeBR($string) {
		$html    = preg_replace( '[^(<br( \/)?>)*|(<br( \/)?>)*$]', '', $string );
		$output = preg_replace('~(?:<br\b[^>]*>|\R){3,}~i', '<br /><br />', $html);
		return $output;
	}

	public static function removeTagScript( $html ){

			  	//parsing begins here:
				$doc = new \DOMDocument();
				@$doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
				$nodes = $doc->getElementsByTagName('script');

				$remove = [];

				foreach ($nodes as $item) {
					$remove[] = $item;
				}

				foreach ($remove as $item) {
					$item->parentNode->removeChild($item);
				}

				return preg_replace(
					'/^<!DOCTYPE.+?>/', '',
					str_replace(
					array('<html>', '</html>', '<body>', '</body>', '<head>', '</head>', '<p>', '</p>', '&nbsp;' ),
					array('','','','','',' '),
					$doc->saveHtml() ));
	}// End Method

	public static function removeTagIframe( $html ){

			  	//parsing begins here:
				$doc = new \DOMDocument();
				@$doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
				$nodes = $doc->getElementsByTagName('iframe');

				$remove = [];

				foreach ($nodes as $item) {
					$remove[] = $item;
				}

				foreach ($remove as $item) {
					$item->parentNode->removeChild($item);
				}

				return preg_replace(
					'/^<!DOCTYPE.+?>/', '',
					str_replace(
					array('<html>', '</html>', '<body>', '</body>', '<head>', '</head>', '<p>', '</p>', '&nbsp;' ),
					array('','','','','',' '),
					$doc->saveHtml() ));
	}// End Method

	public static function fileNameOriginal($string){
		return pathinfo($string, PATHINFO_FILENAME);
	}

	public static function formatDate($date, $time = false)
	{
		if ($time == false) {
			$date = strtotime($date);
		}

		$day    = date('d',  $date);
		$_month = date('m',  $date);
		$month  = trans("months.$_month");
		$year   = date('Y', $date);

		$dateFormat = $month.' '.$day.', '.$year;

		return $dateFormat;
	}

	public static function watermark($name, $watermarkSource) {

		$thumbnail = Image::make($name);
		$watermark = Image::make($watermarkSource);
		$x = 0;

		while ($x < $thumbnail->width()) {
		    $y = 0;

		    while($y < $thumbnail->height()) {
		        $thumbnail->insert($watermarkSource, 'top-left', $x, $y);
		        $y += $watermark->height();
		    }

		    $x += $watermark->width();
		}

		$thumbnail->save($name)->destroy();
	}

	public static function strRandom($number = 8) {
		return substr( strtolower( md5( time() . mt_rand( 1000, 9999 ) ) ), 0, $number );
	}// End method

	public static function validateImageUploaded($image) {

		$finfo = new \finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
        $finfo->file($image),
        array(
					'jpg' => 'image/jpeg',
					'png' => 'image/png',
					'gif' => 'image/gif'
        ),
        true
    )) {
        throw new \Exception('Invalid file format.');
    }

	}// End method

	public static function amountFormat($value) {

		$settings = AdminSettings::first();

		if ($settings->currency_position == 'left') {
			$amount = $settings->currency_symbol.number_format($value);
		} elseif($settings->currency_position == 'right') {
			$amount = number_format($value).$settings->currency_symbol;
		} else {
			$amount = $settings->currency_symbol.number_format($value);
		}

	 return $amount;

 }// END

 public static function amountFormatDecimal($value, $appyTax = null) {

	 $settings = AdminSettings::first();

	 // Aplly Taxes
	 if (auth()->check()) {
		 $isTaxable = auth()->user()->isTaxable();
  	 $taxes = 0;

  	 if ($appyTax && $isTaxable->count()) {
  		 foreach ($isTaxable as $tax) {
  				$taxes += $tax->percentage;
  		 }

  		 $valueWithTax = number_format($taxes * $value / 100, 2);
  		 $value = ($value + $valueWithTax);
  	 }
	 }// isTaxable

	 if ($settings->currency_code == 'JPY') {
		 return $settings->currency_symbol.number_format($value);
	 }

	 if ($settings->decimal_format == 'dot') {
		 $decimalDot = '.';
		 $decimalComma = ',';
	 } else {
		 $decimalDot = ',';
		 $decimalComma = '.';
	 }

	 if ($settings->currency_position == 'left') {
		 $amount = $settings->currency_symbol.number_format($value, 2, $decimalDot, $decimalComma);
	 } elseif ($settings->currency_position == 'right') {
		 $amount = number_format($value, 2, $decimalDot, $decimalComma).$settings->currency_symbol;
	 } else {
		 $amount = $settings->currency_symbol.number_format($value, 2, $decimalDot, $decimalComma);
	 }

	return $amount;

}// END

 public static function amountWithoutFormat($value) {

	 $settings = AdminSettings::first();

	 if($settings->currency_position == 'left') {
		 $amount = $settings->currency_symbol.$value;
	 } elseif($settings->currency_position == 'right') {
		 $amount = $value.$settings->currency_symbol;
	 } else {
		 $amount = $settings->currency_symbol.$value;
	 }

	return $amount;

}// END method

public static function getFileSize($filename)
{
	$headers  = get_headers($filename, 1);
	$fsize    = $headers['Content-Length'];
	$size    = static::formatBytes($fsize, 1);

	return $size;
}

public static function resolutionPreview($image, $thumbnail = false, $medium = false)
{
	$resolution = explode('x', $image);
	$lWidth = $resolution[0];
	$lHeight = $resolution[1];

	if ($lWidth > $lHeight) {
		$previewWidth = 850 / $lWidth;
	} else {
		$previewWidth = 480 / $lWidth;
	}

	if ($thumbnail) {
		if ($lWidth > $lHeight) {
			$previewWidth = 280 / $lWidth;
		} else {
			$previewWidth = 190 / $lWidth;
		}
	}

	if ($medium) {
		if ($lWidth > $lHeight) {
			$previewWidth = 640 / $lWidth;
		} else {
			$previewWidth = 480 / $lWidth;
		}
	}

	$newWidth = ceil($lWidth * $previewWidth);
	$newHeight = ceil($lHeight * $previewWidth);

	return $newWidth.'x'.$newHeight;
}

public static function cleanStr($str)
{
	$reservedSymbols = [
		'!','”','"',"'",'-','+','<','>','@','(',')','~','*',
		'/','\/','{','}','[',']','#','$','%','&','.','_',':',
		';','=','?','^','`','|', '//', '\\'
	];
	return trim(stripslashes(str_replace($reservedSymbols, '', $str)), ',');
}

public static function calculatePercentage($value, $percentage)
{
	return number_format(($value * $percentage / 100), 2);
}

public static function daysInMonth($month, $year)
{
	return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
 }

public static function PercentageIncreaseDecrease($currentPeriod, $previousPeriod)
{
	if ($currentPeriod > $previousPeriod && $previousPeriod != 0) {
		 $subtraction = $currentPeriod  - $previousPeriod;
		 $percentage = $subtraction / $currentPeriod * 100;
		 return '<small class="float-end text-success">
		 <strong><i class="bi bi-arrow-up me-1"></i> '.number_format($percentage, 2).'%</strong>
		 </small>';

	} elseif ($currentPeriod < $previousPeriod && $currentPeriod != 0) {
		$subtraction = $previousPeriod - $currentPeriod;
		$percentage = $subtraction / $currentPeriod * 100;
		return '<small class="float-end text-danger">
		<strong><i class="bi bi-arrow-down me-1"></i> '.number_format($percentage, 2).'%</strong>
		</small>';

	} elseif ($currentPeriod < $previousPeriod && $previousPeriod != 0) {
		$subtraction = $previousPeriod - $currentPeriod;
		$percentage = $subtraction / $previousPeriod * 100;
		return '<small class="float-end text-danger">
		<strong><i class="bi bi-arrow-down me-1"></i> '.number_format($percentage, 2).'%</strong>
		</small>';

	} elseif ($currentPeriod == $previousPeriod) {
		return '<small class="float-end text-muted">
		<strong>0%</strong>
		</small>';

	} else {
		 $percentage = $currentPeriod / 100 * 100;
		return '<small class="float-end text-success">
		<strong><i class="bi bi-arrow-up me-1"></i> '.number_format($percentage, 2).'%</strong>
		</small>';

	}
}// End method

	public static function formatDateChart($date){

		$day    = date('d', strtotime($date));
		$_month = date('m', strtotime($date));
		$month  = trans("months.$_month");

		$dateFormat = $month.' '.$day;

		return $dateFormat;
	}

	public static function getDatacURL($url)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		$ch = curl_exec($ch);

		return json_decode($ch);
	}

	public static function userCountry()
	{
		$ip = request()->ip();
		if (cache('userCountry-'.$ip)) {

			// Give access to Admin or staff if their country has been blocked.
			if (auth()->check() && auth()->user()->permission == 'all') {
				return 'null';
			}

			return cache('userCountry-'.$ip);
		} else {
			return 'null';
		}
	}

	public static function amountGross($amount)
	{
		$settings = AdminSettings::first();

			if ($settings->currency_code == 'JPY') {
			 $amount = round($amount);
		 } else {
			 $amount = number_format($amount, 2, '.', '');
		 }

		// Aplly Taxes
		$isTaxable = auth()->user()->isTaxable();
		$taxes = 0;

		if ($isTaxable->count()) {
			foreach ($isTaxable as $tax) {
				 $taxes += $tax->percentage;
			}

			if ($settings->currency_code == 'JPY') {
			 $amount = round($amount + ($taxes * $amount / 100));
		 } else {
			 $amount = number_format($amount + ($taxes * $amount / 100), 2, '.', '');
		 }

			return $amount;
		}// isTaxable

		return $amount;
	}

	public static function envUpdate($key, $value, $comma = false)
	{
	  $path = base_path('.env');
		$value = trim($value);
		$env = $comma ? '"'.env($key).'"' : env($key);

	   if (file_exists($path)) {

	       file_put_contents($path, str_replace(
	           $key . '=' . $env, $key . '=' . $value, file_get_contents($path)
	       ));
	   }
   }

	public static function pages()
 	{
 		$pagesLocale  = Pages::whereLang(session('locale'))->orderBy('id')->get();

 		if ($pagesLocale->count() <> 0) {
 				return $pagesLocale;
 		} else {
 			return Pages::whereLang(env('DEFAULT_LOCALE'))->orderBy('id')->get();
 		}
 	}

	public static function calculateSubscriptionDiscount($priceMonth, $planPrice)
	{
		return number_format(((($priceMonth * 12) - $planPrice)/($priceMonth * 12) * 100), 0);
	}

	public static function calculatePriceByDownloads($planPrice, $downloadsPerMonth, $monthly = false)
	{
		if ($monthly) {
			return self::amountFormatDecimal($planPrice/$downloadsPerMonth);
		} else {
			$downloadsByYear = ($planPrice/12);
			return self::amountFormatDecimal($downloadsByYear/$downloadsPerMonth);
		}
	}

	public static function calculatePriceGrossByDownloads($planPrice, $downloadsPerMonth, $monthly = false)
	{
		if ($monthly) {
			return number_format($planPrice/$downloadsPerMonth, 2, '.', '');
		} else {
			$downloadsByYear = ($planPrice/12);
			return number_format($downloadsByYear/$downloadsPerMonth, 2, '.', '');
		}
	}

	// Set interval subscriptions
	public static function planInterval($interval)
	{
		switch ($interval) {
			case 'month':
				return now()->add(1, 'month');
				break;

			case 'year':
				return now()->add(12, 'months');
				break;
		}
	}

	public static function dataIPTC($image)
	{
    $data = ['title' => '', 'tags' => ''];
    $size = getimagesize($image, $info);

    if (is_array($info)) {

			if (isset($info["APP13"])) {
				$iptc = iptcparse($info["APP13"]);
        $data['title'] = $iptc['2#005'][0] ?? null;
        $data['tags'] = $iptc['2#025'] ?? null;

				return $data;
			}
    }
		return false;
	}// End dataIPTC

}//<--- End Class
