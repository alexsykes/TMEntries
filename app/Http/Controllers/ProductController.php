<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\StripeClient;

class ProductController extends Controller
{
    public function products()
    {
        $products = DB::table('products')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.products.index', compact('products'));
    }

    public function add()
    {
        $categories = array('Entry fee', 'Membership', 'Merchandise', 'Other');
        $clubs = DB::table('clubs')->select('id', 'name')
            ->orderBy('name')
            ->get();
        return view('admin.products.add', compact('categories', 'clubs'));
    }

    public function store(Request $request)
    {
//        dd($request->all());
        $attrs = $request->validate([
            'product_name' => 'required',
            'product_description' => 'required',
            'price' => 'required',
            'product_category' => 'required',
            'club_id' => 'required',
        ]);

        $attrs['price'] = 100 * $attrs['price'];

        if ($attrs['product_category'] == 'entry fee') {
            $attrs['isEntryFee'] = true;
        } else {
            $attrs['isEntryFee'] = false;
        }

//      Enter options
        $trial_id = 0;
        if (!is_null($request->trial_id)) {
            $trial_id = $request->trial_id;
        }
        $attrs['trial_id'] = $trial_id;

        $hasQuantity = false;
        if (!is_null($request->hasQuantity)) {
            $hasQuantity = $request->hasQuantity;
        }
        $attrs['hasQuantity'] = $hasQuantity;

        $required = true;
        if (!is_null($request->required)) {
            $required = false;
        }
        $attrs['required'] = $required;

        $isYouth = false;
        if (!is_null($request->isYouth)) {
            $isYouth = $request->isYouth;
        }
        $attrs['isYouth'] = $isYouth;

//      Check for options
        if (!is_null($request->options)) {
            $optionsArray = explode(',', $request->options);
            foreach ($optionsArray as $option) {
                $option = trim($option);
                info("option: $option");

                $stripe_secret_key = config('cashier.secret');
                $stripe = new StripeClient("$stripe_secret_key");

                $stripe->products->create([
                    'name' => $attrs['product_name'],
                    'description' => $attrs['product_description'] . " (" . $option . ")",
                    //            'statement_descriptor' => $trial->name,
                    'metadata' => [
                        'category' => $attrs['product_category'],
                        'required' => $attrs['required'],
                        'trialid' => $attrs['trial_id'],
                        'club_id' => $attrs['club_id'],
                        'amount' => $attrs['price'],
                        'isYouth' => $attrs['isYouth'],
                        'hasQuantity' => $attrs['hasQuantity'],
                        'options' => $option,

                    ],
                    'default_price_data' => ['currency' => 'gbp',
                        'unit_amount' => $attrs['price'],
                    ],
                ]);

            }
        } else {
            $stripe_secret_key = config('cashier.secret');
            $stripe = new StripeClient("$stripe_secret_key");
            $stripe->products->create([
                'name' => $attrs['product_name'],
                'description' => $attrs['product_description'],
                //            'statement_descriptor' => $trial->name,
                'metadata' => [
                    'category' => $attrs['product_category'],
                    'trialid' => $attrs['trial_id'],
                    'required' => $attrs['required'],
                    'club_id' => $attrs['club_id'],
                    'amount' => $attrs['price'],
                    'isYouth' => $attrs['isYouth'],
                    'hasQuantity' => $attrs['hasQuantity'],
//                    'options' => "None",
                ],
                'default_price_data' => ['currency' => 'gbp',
                    'unit_amount' => $attrs['price'],
                ],
            ]);
        }
        return redirect('/admin/products');
    }
}
