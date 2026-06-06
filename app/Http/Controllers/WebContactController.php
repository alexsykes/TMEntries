<?php

namespace App\Http\Controllers;

use App\Mail\AdminNotifyReceived;
use App\Mail\WebContactReceived;
use App\Mail\WebContactUpdated;
use App\Models\WebContact;
use App\Rules\ReCaptchaV3;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Support\Facades\Mail;

class WebContactController extends Controller
{
    public function index()
    {
        $webcontacts = WebContact::orderBy('closed')
            ->orderByDesc('created_at')
            ->get();

        return view('webcontacts.index', compact('webcontacts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'g-recaptcha-response' => ['required', new ReCaptchaV3('submitContact')],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:254'],
            'message' => ['required'],
            'type' => ['required'],
        ]);

        $messageArray = array($data['type'], $data['message']);
        $data['message'] = json_encode($messageArray);
        $ip_address = $request->ip();
        $data['ip_address'] = $ip_address;
        $data['token'] = bin2hex(random_bytes(16));
        $webContact = WebContact::create($data);

        $address = new Address($data['email'], $data['name']);
        Mail::to($address)
            ->bcc(config('mail.from.address'))
            ->send(mailable: new WebContactReceived($webContact));

        Mail::to(config('mail.from.address'))
            ->send(mailable: new AdminNotifyReceived($webContact));

        return view('/contact-acknowledgement', compact('webContact'));
    }

    public function show(Request $request)
    {
        $webcontact = WebContact::findOrFail($request->id);
        $categories = array('Enquiry', 'Complaint', 'Spam');

        return view('webcontacts.edit', compact('webcontact', 'categories'));
    }

    public function destroy(WebContact $webContact)
    {
        $webContact->delete();

        return response()->json();
    }

    public function contactForm(Request $request)
    {
        $type = $request->type;
        return view('contact.contact-form', compact('type'));
    }

    public function adminEdit(Request $request)
    {
        $token = $request->token;
        $id = $request->id;

        $webcontact = WebContact::where('id', $id)
            ->where('token', $token)
            ->first();

        $categories = array('Enquiry', 'Complaint', 'Spam');
//dump($webContact);
        return view('webcontacts.edit', compact('webcontact', 'categories'));
    }

    public function adminSpam(Request $request)
    {
        $token = $request->token;
        $id = $request->id;

        $webContact = WebContact::where('id', $id)
            ->where('token', $token)
            ->first();

        $webContact->update(['responded_at' => now(),
            'closed' => 1,
            'category' => 'Spam'
        ]);

        abort(204);
    }

    public function update(Request $request)
    {
        $webContact = WebContact::findOrFail($request->id);
        $data = $request->validate([
            'category' => ['nullable'],
            'response' => ['nullable'],
            'action' => ['nullable'],
        ]);

        $data['closed'] = !is_null($request->closed);

        if ($request->sendResponse) {
            $data['responded_at'] = now();
        }
        $webContact->update($data);

//        Check for response
        if ($request->sendResponse) {
            info("Send response");
            Mail::to($webContact->email)
//                ->bcc(config('mail.from.address'))
                ->send(mailable: new WebContactUpdated($webContact));
        }
        return redirect('/webcontacts');
    }


}
