<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\PaymentGateways;
use App\Models\User;
use App\Models\AdminSettings;

class InstallController extends Controller
{
    public function __construct() {
      $this->middleware('role');
    }

    public function install($addon)
    {
      //<-------------- Install BulkUpload --------------->
      if ($addon == 'bulk-upload') {

          // Controller
          $filePathController = 'bulk-upload/BulkUploadController.php';
          $pathController = app_path('Http/Controllers/BulkUploadController.php');

          if (\File::exists($filePathController)) {
            rename($filePathController, $pathController);
          }//<--- IF FILE EXISTS

          // View
          $filePathView = 'bulk-upload/bulk-upload.blade.php';
          $pathView = resource_path('views/admin/bulk-upload.blade.php');

          if (\File::exists($filePathView)) {
            rename($filePathView, $pathView);
          }//<--- IF FILE EXISTS

          // View Layout
          $filePathView2 = 'bulk-upload/layout.blade.php';
          $pathView2 = resource_path('views/admin/layout.blade.php');

          if (\File::exists($filePathView2)) {
            rename($filePathView2, $pathView2);
          }//<--- IF FILE EXISTS

        $indexPath = 'bulk-upload/index.php';
        unlink($indexPath);

        rmdir('bulk-upload');

        return redirect('panel/admin/bulk-upload');
    }
  }//<---------------------- End Install BulkUpload

}
