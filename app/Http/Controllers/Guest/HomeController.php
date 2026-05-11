<?php

namespace App\Http\Controllers\Guest;
use Exception;
use App\Blog;
use App\Cms;
use App\MultiImages;
use App\Product;
use App\Testimonial;
use App\Pcategory;
use App\Subpcategory;
use App\Subscriber;
use App\Enquery;
use App\EmailRecipient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Jobs\SendSubscriptionVerificationEmail;
use App\Jobs\SendEnquiryEmail;
use App\Listeners\EmailSubscribedListener;
use App\CrmSetting;
use DB;
use Mail;
use Illuminate\Support\Facades\Input;
//use Storage;
use Response;

class HomeController extends Controller
{

    private $menu;
    private $testimonial;


    public function __construct()
    {
	/*  $this->testimonial = Testimonial::where('is_active','=',1)->get();
		$this->blogsnew = Blog::where('is_active','=',1)->orderBy('id', 'desc')->take(4)->get(); */
    }

	public function homenew()
    {
        try {
            $hot_offers = Product::where('hot_offers','=',1)->where('is_active','=',1)->orderBy('id')->take(4)->get();
            $new_launches = Product::where('new_launches','=',1)->where('is_active','=',1)->orderBy('id')->take(4)->get();
            
            $slug = 'women-s-health-85';
            $cat = Pcategory::where('slug', $slug)->first();
            
            $womens_health = collect();
            if ($cat) {
                $cat_id = $cat->id;
                $womens_health = DB::table('products')
                        ->join('pcategory_product', 'products.id', '=', 'pcategory_product.product_id')
                        ->select('products.*')
                        ->where('pcategory_product.pcategory_id','=',$cat_id)
                        ->where('products.is_active','=',1)
                        ->take(4)->get();
            }
            
            $slug = 'men-health-73';
            $cat = Pcategory::where('slug', $slug)->first();
            
            $mens_health = collect();
            if ($cat) {
                $cat_id = $cat->id;
                $mens_health = DB::table('products')
                        ->join('pcategory_product', 'products.id', '=', 'pcategory_product.product_id')
                        ->select('products.*')
                        ->orWhere('products.id','=',5)
                        ->orWhere('products.id','=',6)
                        ->orWhere('products.id','=',8)
                        ->orWhere('products.id','=',207)
                        ->get();
            }
            
            $blogs = Blog::where('is_active','=',1)->orderBy('id', 'DESC')->take(3)->get();
            return view('guest/homenew', ['hot_offers' => $hot_offers, 'new_launches' => $new_launches, 'blogs'=>$blogs, 'blogsnew' => $blogs, 'womens_health' => $womens_health, 'mens_health' => $mens_health]);
        } catch (\Exception $e) {
            \Log::error('HomeController homenew error: ' . $e->getMessage());
            return view('guest/homenew', [
                'hot_offers' => collect(), 
                'new_launches' => collect(), 
                'blogs' => collect(), 
                'blogsnew' => collect(), 
                'womens_health' => collect(), 
                'mens_health' => collect()
            ]);
        }
    }

   

    /**
     * Show the Blogs Homepage.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$blogs = Blog::active()->orderBy('created_at', 'desc')->simplePaginate(app('global_settings')[2]['setting_value']);
        $hot_offers = Product::where('hot_offers','=',1)->where('is_active','=',1)->get();
        $new_launches = Product::where('new_launches','=',1)->where('is_active','=',1)->orderBy('id', 'desc')->take(4)->get();

        
        $slug = 'women-s-health-85';
        $cat = Pcategory::where('slug', $slug)->first();
        $cat_id = $cat->id;

        
        $cat_list = DB::table('products')
                ->join('pcategory_product', 'products.id', '=', 'pcategory_product.product_id')
                ->select('products.*')
                ->where('pcategory_product.pcategory_id','=',$cat_id)
                ->where('products.is_active','=',1)
                ->take(4)->get();
        

        $slug = 'men-health-73';
        $cat = Pcategory::where('slug', $slug)->first();
        $cat_id = $cat->id;

        //$val = [5,6,8,207];
        $cat_list1 = DB::table('products')
                ->join('pcategory_product', 'products.id', '=', 'pcategory_product.product_id')
                ->select('products.*')
                //->where('pcategory_product.pcategory_id','=',$cat_id)
                ->orWhere('products.id','=',5)
                ->orWhere('products.id','=',6)
                ->orWhere('products.id','=',8)
                ->orWhere('products.id','=',207)
                //->whereIn('products.id','=',$val)
                ->get();
        

        $testimonial = Testimonial::where('is_active','=',1)->orderBy('id', 'desc')->take(4)->get();

        // echo '<pre>';
        // print_r($menu);
        // die;

        return view('guest/home', ['menu' => $this->menu,'testimonials' => $testimonial,'hot_offers' => $hot_offers, 'new_launches' => $new_launches, 'more_products' => $cat_list,'blogsnew' => $this->blogsnew, 'more_products' => $cat_list, 'more_products1' => $cat_list1]);
    }
    /**
     * Show the Blogs Homepage.
     *
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Request $request)
    {
        // Validate data
        $validatedData = $request->validate([
            'email' => 'required|email|unique:subscribers'
        ]);

        if ($validatedData) {
            // Save Subscriber
            $subscriber = new Subscriber;
            $subscriber->email = $request->email;
            $subscriber->confirmation_token = md5(uniqid($request->email, true));
            $subscriber->save();
            // Automatic Send Email for confirmation
            event(new EmailSubscribedListener($subscriber));
            SendSubscriptionVerificationEmail::dispatch($subscriber);
            
            // Send to CRM
            CrmSetting::sendLead([
                'email' => $request->email,
                'source' => 'Newsletter Subscription',
                'name' => 'Newsletter Subscriber',
                'message' => 'Subscribed to newsletter',
            ]);
            
            // Return Success Message
            return response()->json('Check Your Email Inbox for confirmation', 200);
        }
        // return error
        return response()->json('Unable to Subscribe', 422);
    }

    public function subscribeVerify($token)
    {
        $subscriber = Subscriber::where('confirmation_token', $token)->first();
        $subscriber->is_active = 1;
        if ($subscriber->save()) {
            return view('guest.subscribe-confirmation', ['subscriber' => $subscriber]);
        }
    }

    public function catgorylistshow($slug)
    {
        $count = Pcategory::where('slug', $slug)->count();
        //echo $count;
        //die;
        $cat_list = array();
        $cat_name = '';
        $subcount = Subpcategory::where('slug', $slug)->count();
        //echo $subcount;
        //die;
        $meta_title = '';
        $meta_keywords = '';
        $meta_description = '';
        if($subcount > 0)
        {
            $cat = Subpcategory::where('slug', $slug)->first();
            $cat_name = $cat->name;
            $meta_title = $cat->meta_title;
            $meta_keywords = $cat->meta_keywords;
            $meta_description = $cat->meta_description;


            $sub_id = $cat->id;
            $cat_id = $cat->pcategories_id;
            $cat_list = DB::table('products')
                        ->join('pcategory_product', 'products.id', '=', 'pcategory_product.product_id')
                        ->join('product_subpcategory', 'products.id', '=', 'product_subpcategory.product_id')
                        ->select('products.*')
                        ->where('pcategory_product.pcategory_id','=',$cat_id)
                        ->where('product_subpcategory.subpcategory_id','=',$sub_id)
                        ->where('products.is_active','=',1)
                        ->where('products.deleted_at','=',null)
                        ->get();
        }
        else if($count > 0)
        {

            $cat = Pcategory::where('slug', $slug)->first();
            $cat_id = $cat->id;
            $cat_name = $cat->name;
            $meta_title = $cat->meta_title;
            $meta_keywords = $cat->meta_keywords;
            $meta_description = $cat->meta_description;

            $cat_list = Subpcategory::where('pcategories_id', $cat->id)->get();

            if(count($cat_list) > 0)
            {
                $cat_list = Subpcategory::where('pcategories_id', $cat->id)->get();
            }
            else
            {
                $cat_list = DB::table('products')
                        ->join('pcategory_product', 'products.id', '=', 'pcategory_product.product_id')
                        ->select('products.*')
                        ->where('pcategory_product.pcategory_id','=',$cat_id)
                        ->where('products.is_active','=',1)
                        ->where('products.deleted_at','=',null)
                        ->get();
            }

        }
        return view('guest.categorylist',['cat_list' => $cat_list,'cat_name' => $cat_name,'meta_title' => $meta_title, 'meta_description' => $meta_description, 'meta_keywords' => $meta_keywords]);
    }

    public function contactus()
    {
        return view('guest.contactus');
    }

    public function getDocument($id)  
    {
        //echo $id;
        //die;                                                                                                                                                    
        //$document = Document::findOrFail($id);

        $filePath = 'pdf/rsm enterprises brouchers 1.pdf';

        //$filePath = Storage::get('pdf/rsm enterprises brouchers 1.pdf');

        //asset('pdf/rsm enterprises brouchers 1.pdf');
        // file not found
        if( ! Storage::exists($filePath) ) {
          abort(404);
        }

        $pdfContent = Storage::get($filePath);

        // for pdf, it will be 'application/pdf'
        $type       = Storage::mimeType($filePath);

        $fileName   = 'rsm enterprises brouchers 1';
        //echo $fileName;die;
                return Response::make($pdfContent, 200, [
          'Content-Type'        => $type,
          'Content-Disposition' => 'inline; filename="'.$fileName.'"'
        ]);
    }

    public function others()
    {
        return view('guest.others',['menu' => $this->menu, 'testimonials' => $this->testimonial,'blogsnew' => $this->blogsnew]);
    }

    public function postcontactus(Request $request)
    {
        try {
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $remoteip = $_SERVER['REMOTE_ADDR'];
            $data = [
                    'secret' => config('services.recaptcha.secret'),
                    'response' => $request->get('recaptcha'),
                    'remoteip' => $remoteip
                ];

            // Use cURL instead of file_get_contents (works even when allow_url_fopen=0)
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $result = curl_exec($ch);
            curl_close($ch);

            $resultJson = json_decode($result);

            if (!$resultJson || $resultJson->success != true) {
                    return back()->withErrors(['captcha' => 'ReCaptcha Error']);
              }
            if ($resultJson->score >= 0.3) {
                 
                    $rules = [
                        'name' => 'required|max:255',
                        'email' => 'required|email',
                        'phone' => ['required'],
                        'message' => 'required',
                        'countryCode'=>'required'
                    ];
            
                    $messages = [
                        'name.required' => 'The name field is required.',
                        'email.required' => 'The email field is required.',
                        'email.email' => 'Please enter a valid email address.',
                        'phone.regex' => 'Please enter a valid phone number.',
                        'message.required' => 'The message field is required.',
                    ];
                    $validatedData = $request->validate($rules, $messages);
            
                    // Save to enqueries table
                    $enquery = new Enquery;
                    $enquery->country = $request->countryCode;
                    $enquery->name = $request->name . ' ' . ($request->last_name ?? '');
                    $enquery->email = $request->email;
                    $enquery->phone = $request->phone;
                    $enquery->product_name = 'Contact Form';
                    $enquery->message = $request->message;
                    $enquery->status = 'sent'; // Mark as sent immediately
                    $enquery->save();
                    
                    // Get active email recipients
                    $recipients = EmailRecipient::getActiveEmails();
                    if (empty($recipients)) {
                        $recipients = ['rsmmultilinkenquiry@gmail.com', 'kumarshivam827@gmail.com'];
                    }
                    
                    // Use PHP mail() for instant delivery - No SMTP delays
                    $to = implode(', ', $recipients);
                    $subject = 'Contact Us - New Enquiry';
                    $message = "Name: " . $request->name . " " . ($request->last_name ?? '') . "\n";
                    $message .= "Email: " . $request->email . "\n";
                    $message .= "Phone: " . $request->countryCode . " " . $request->phone . "\n";
                    $message .= "Message: " . $request->message . "\n";
                    
                    $headers = "From: query@rsmmultilink.com\r\n";
                    $headers .= "Reply-To: " . $request->email . "\r\n";
                    $headers .= "X-Mailer: PHP/" . phpversion();
                    
                    // Send email using PHP mail() - Fast and reliable
                    @mail($to, $subject, $message, $headers);
                    
                    \Log::info('Contact form submitted', ['to' => $to, 'from' => $request->email]);
                    
                    // Send to CRM
                    CrmSetting::sendLead([
                        'name' => $request->name . ' ' . ($request->last_name ?? ''),
                        'email' => $request->email,
                        'phone' => $request->countryCode . ' ' . $request->phone,
                        'message' => $request->message,
                        'source' => 'Contact Form',
                        'country_code' => $request->countryCode,
                    ]);
            
                    return redirect()->route('thank-you');     
                        
            } else {
                 
                    return back()->withErrors(['captcha' => 'ReCaptcha Error']);
            }   
        } catch (\Exception $err) {
            return back()->withErrors(['captcha' => 'Something went wrong. Please try again.']);
        }
    }


    public function submitEnquireModal(Request $request)
    {
		$rules = [
            'name' => 'required|max:255',
            'email' => 'required|email',
           /* 'phone' => ['required', 'regex:/^(\+?\d{1,3}[- ]?)?\d{10}$/'],*/
            'message' => 'required',
        ];

        $messages = [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'phone.regex' => 'Please enter a valid phone number.',
            'message.required' => 'The message field is required.',
        ];
        $validatedData = $request->validate($rules, $messages);
       
        if ($validatedData) {
            // Save Enquiry
            $enquery = new Enquery;
            $enquery->country = $request->countryCode;
            $enquery->name = $request->name;
            $enquery->email = $request->email;
            $enquery->phone = $request->phone;
            $enquery->product_name = $request->product;
            $enquery->message = $request->message;
            $enquery->status = 'sent'; // Mark as sent immediately
            $enquery->save();
		
		    // Get active email recipients
            $recipients = EmailRecipient::getActiveEmails();
            if (empty($recipients)) {
                $recipients = ['rsmmultilinkenquiry@gmail.com', 'kumarshivam827@gmail.com'];
            }
            
            // Use PHP mail() for instant delivery - No SMTP delays
            $to = implode(', ', $recipients);
            $subject = 'Enquire Now - Product Enquiry';
            $message = "Name: " . $request->name . "\n";
            $message .= "Email: " . $request->email . "\n";
            $message .= "Phone: " . $request->countryCode . " " . $request->phone . "\n";
            $message .= "Product: " . $request->product . "\n";
            $message .= "Message: " . $request->message . "\n";
            
            $headers = "From: query@rsmmultilink.com\r\n";
            $headers .= "Reply-To: " . $request->email . "\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();
            
            // Send email using PHP mail() - Fast and reliable
            @mail($to, $subject, $message, $headers);
            
            \Log::info('Enquiry submitted', ['to' => $to, 'from' => $request->email, 'product' => $request->product]);
            
            // Send to CRM
            CrmSetting::sendLead([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->countryCode . ' ' . $request->phone,
                'message' => $request->message,
                'product' => $request->product,
                'source' => 'Enquiry Modal',
                'country_code' => $request->countryCode,
            ]);
            
			echo 'true';
		}else{
			echo "validation failed";
		}
	}
	public function categorylist()
    {
        $cat_general = Pcategory::where('type', 0)->where('is_active', 1)->orderBy('display_order', 'asc')->get();
        $cat_trade = Pcategory::where('type', 1)->where('is_active', 1)->orderBy('display_order', 'asc')->get();

        return view('guest.categorylistmain',['cat_general' => $cat_general,'cat_trade' => $cat_trade]);
    }

    public function productshow($slug)
    {
        $product_details = array();
        $subcount = Product::where('slug', $slug)->count();

          

        if($subcount > 0)
        {
            $product_details = Product::where('slug', $slug)->where('is_active','=',1)->first();
            // dd($product_details);

            $multi_images = MultiImages::where('product_id', $product_details->id)->get();


            $meta_title = $product_details->meta_title;
            $meta_keywords = $product_details->meta_keywords;
            $meta_description = $product_details->meta_description;
            $tags = explode(',', $product_details->tags);  
            return view('guest.productDetail',['product_details' => $product_details,'meta_title' => $meta_title, 'meta_description' => $meta_description, 'meta_keywords' => $meta_keywords, 'tags' => $tags, 'multi_images' => $multi_images]);
        }
        return redirect()->route('homepage');

    }


    public function pages($pages)
    {    
        
        $subcount = Cms::where('slug', $pages)->count();
        if($subcount > 0)
        {
            $pages_details = Cms::where('slug', $pages)->first();
            $meta_title = $pages_details->meta_title;
            $meta_keywords = $pages_details->meta_keywords;
            $meta_description = $pages_details->meta_description;
            return view('guest.pages',['pages_details' => $pages_details, 'meta_title' => $meta_title, 'meta_description' => $meta_description, 'meta_keywords' => $meta_keywords]);
        }
        return back();
    }

    public function searchpage(Request $request)
    {
        if($request->tags != '')
        {
            $search = Product::orWhere('tags', 'like', '%' . $request->s . '%')->where('is_active','=',1)->paginate(8);
        }
        else
        {
            $search = Product::orWhere('title', 'like', '%' . $request->s . '%')->where('is_active','=',1)->paginate(8);
        }
        return view('guest.searchpage',['search' => $search->appends(Input::except('page'))]);
    }

    public function thankYouPage(){
        return view('guest/thank-you');
    }
}


