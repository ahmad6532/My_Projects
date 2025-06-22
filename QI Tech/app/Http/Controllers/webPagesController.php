<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class webPagesController extends Controller
{
    //
    public function home_page(Request $request)
    {
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0]; // Assuming format is subdomain.domain.com
        $user = Auth::guard('web')->user();
        if (isset($subdomain) && $subdomain !== 'dev' && $subdomain !== 'qi-tech' && $subdomain !== 'localhost:8000' && $subdomain !== '127' && $subdomain !== 'www') {
            return redirect()->route('login');
        }
        return view('home_page');
    }
    public function book_session_page(Request $request)
    {
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0]; // Assuming format is subdomain.domain.com
        $user = Auth::guard('web')->user();
        if (isset($subdomain) && $subdomain !== 'dev' && $subdomain !== 'qi-tech' && $subdomain !== 'localhost:8000' && $subdomain !== '127' && $subdomain !== 'www') {
            return redirect()->route('login');
        }
        return view('book_session_page');
    }
    public function pharmacy_page(Request $request)
    {
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0]; // Assuming format is subdomain.domain.com
        $user = Auth::guard('web')->user();
        if (isset($subdomain) && $subdomain !== 'dev' && $subdomain !== 'qi-tech' && $subdomain !== 'localhost:8000' && $subdomain !== '127' && $subdomain !== 'www') {
            return redirect()->route('login');
        }
        return view('pharmacy_page');
    }
    public function support_page(Request $request)
    {
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0]; // Assuming format is subdomain.domain.com
        $user = Auth::guard('web')->user();
        if (isset($subdomain) && $subdomain !== 'dev' && $subdomain !== 'qi-tech' && $subdomain !== 'localhost:8000' && $subdomain !== '127' && $subdomain !== 'www') {
            return redirect()->route('login');
        }
        return view('support_page');
    }
    public function confirm_page(Request $request)
    {
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0]; // Assuming format is subdomain.domain.com
        $user = Auth::guard('web')->user();
        if (isset($subdomain) && $subdomain !== 'dev' && $subdomain !== 'qi-tech' && $subdomain !== 'localhost:8000' && $subdomain !== '127' && $subdomain !== 'www') {
            return redirect()->route('login');
        }
        return view('confirm_page');
    }
    public function send_in_touch_mail(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'telephone' => 'required|string',
            'code' => 'required|string',
            'email' => 'required|email',
            'message' => 'required|string',
        ];

        $messages = [
            'name.required' => 'The name field is required.',
            'telephone.required' => 'The telephone field is required.',
            'code.required' => 'The code field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'message.required' => 'The message field is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('/#Contact-Us')->withErrors($validator);
        }
        $number = $this->formatPhoneNumber($request->telephone, $request->code);

        $content = "
                    <html>
                        <head>
                            <title>Get in Touch Email</title>
                        </head>
                        <body>
                            <p>Hello,</p>
                            <p>A new message has been received from the Qi Tech homepage:</p>
                            <p><strong>Name:</strong> $request->name </p>
                            <p><strong>Email:</strong> $request->email</p>
                            <p><strong>Telephone:</strong> $number </p>
                            <p><strong>Message:</strong> $$request->message</p>
                            <p>Thank you!</p>
                        </body>
                    </html>
                ";

        $res = Mail::raw('', function ($message) use ($request, $number, $content) {
            $message->to('support@qi-tech.co.uk')
                ->subject('Get in Touch Email')
                ->html($content)
                ->from($request->email, $request->name);
        });
        if ($res) {
            return redirect('/#Contact-Us')->with('success', 'Email sent successfully.');
        } else {
            return redirect('/#Contact-Us')->withErrors('error', 'Failed to send email.');
        }

    }

    
    public function job_apply(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'job_title' => 'required|string',
            'email' => 'required|email',
            'message' => 'required|string',
            'attachment' => 'required|file|max:2048', // Max size: 2MB (2048 KB)
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
    
        $content = "
            <html>
                <head>
                    <title>Get in Touch Email</title>
                </head>
                <body>
                    <p>A new message has been received from the Qi Tech homepage:</p>
                    <p><strong>Job Vacancy Name:</strong> $request->job_title </p>
                    <p><strong>Name:</strong> $request->name </p>
                    <p><strong>Email:</strong> $request->email</p>
                    <p><strong>Message:</strong> $request->message</p>
                    <p><strong>Attachment:</strong> Please download the attached file</p>
                    <p>Thank you!</p>
                </body>
            </html>
        ";
    
        // Send email with attachment
        $res = Mail::send([], [], function ($message) use ($request, $content) {
            // Get the file contents as MIME data
            $fileContents = file_get_contents($request->file('attachment')->getRealPath());
            
            // Attach the file as MIME data
            $message->to('asif@qi-tech.co.uk')
                ->subject('Job Application from Qitech')
                ->attachData($fileContents, $request->file('attachment')->getClientOriginalName())
                ->html($content)
                ->from($request->email, $request->name);
        });
    
        if ($res) {
            return redirect('about-us#tab-link-tab-7')->with('success', 'Email sent successfully.');
        } else {
            return redirect('about-us#tab-link-tab-7')->with('error', 'Failed to send email.');
        }
    }
    

    public function formatPhoneNumber($phoneNumber, $countryCode)
    {
        if (Str::startsWith($phoneNumber, '+')) {
            if (substr($phoneNumber, 1, 2) !== $countryCode) {
                $phoneNumber = '+' . $countryCode . ltrim(substr($phoneNumber, 3), '0');
            }
        } else {
            $phoneNumber = '+' . $countryCode . ltrim($phoneNumber, '0');
        }

        return $phoneNumber;
    }

}
