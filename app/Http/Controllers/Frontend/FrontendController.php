<?php

namespace App\Http\Controllers\Frontend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Category;
use App\Models\Product;
use App\Models\Attribute_values;
use App\Models\Attribute;
use App\Models\CustomerCareRequest;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\Inventory;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Banner;
use App\Models\Label;
use App\Models\Video;
use App\Models\PrimaryCategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Mail\ContactUsMail;
use App\Models\WhatsappConversation;
use App\Models\MapAttributesValueToCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Counter;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Torann\GeoIP\Location;

class FrontendController extends Controller
{
    public function home(){        
        return view('frontend.index');
    }    
}
