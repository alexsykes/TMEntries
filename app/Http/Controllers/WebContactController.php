<?php

namespace App\Http\Controllers;

use App\Mail\WebContactReceived;
use App\Mail\WebContactUpdated;
use App\Models\WebContact;
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:254'],
            'message' => ['required'],
        ]);

        $ip_address = $request->ip();
        $data['ip_address'] = $ip_address;
        $webContact = WebContact::create($data);

        $address = new Address($data['email'], $data['name']);
        Mail::to($address)
            ->bcc(config('mail.from.address'))
            ->send(mailable: new WebContactReceived($webContact));
        return view('/contact-acknowledgement', compact('webContact'));
    }

    public function show(Request $request)
    {
        $webcontact = WebContact::findOrFail($request->id);
        $categories = array('Enquiry', 'Complaint', 'Spam');

        return view('webcontacts.edit', compact('webcontact', 'categories'));
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
                ->bcc(config('mail.from.address'))
                ->send(mailable: new WebContactUpdated($webContact));
        }
        return redirect('/webcontacts');
    }

    public function destroy(WebContact $webContact)
    {
        $webContact->delete();

        return response()->json();
    }

    public function contactForm()
    {
        return view('contact.contact-form');
    }


}
