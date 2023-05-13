<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Cart;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\Slider;
use App\Models\Product;
use App\Models\Category;
use App\Models\CustomerWishlist;
use App\Models\ProductReview;
use App\Models\User;

class Review extends Controller
{
    public function __construct()
    {
        $this->slider_model = new Slider;
        $this->product_model = new Product;
        $this->category_model = new Category;
        $this->wishlist_model = new CustomerWishlist;
        $this->productReview_model = new ProductReview;
        $this->user_model = new User;
    }

    public function product_review($id, Request $form)
    {
        $product_data = $this->product_model->where('id', $id)->first();

        if (!empty($product_data)) {

            if (Request()->isMethod('POST')) {

                $validate = validator::make($form->all(), [
                    'rating' => 'required',
                ], [], [
                    'rating' => 'Star Rating',
                ]);

                if (!$validate->fails()) {
                    $form_data['customer_id'] = Auth::id();
                    $form_data['product_id'] = $product_data->id;
                    $form_data['rating'] = $form['rating'];
                    $form_data['review'] = $form['review'];

                    $save = $this->productReview_model->create($form_data);
                    if ($save) {

                        $customer_detail = $this->user_model->where('id', $save['customer_id'])->first();
                        $review_done_data = [
                            'id' => $save['id'],
                            'product_id' => $save['product_id'],
                            'rating' => $save['rating'],
                            'review' => $save['review'],
                            'created_at' => date("d-m-Y", strtotime($save['created_at'])),
                            'customer' => $customer_detail['fname'] . " " . $customer_detail['lname'],
                        ];
                        $response = [
                            'status' => 1,
                            'message' => "Review Submited Successfully.",
                            'save_data' => $review_done_data,
                        ];
                    } else {
                        $response = [
                            'status' => 0,
                            'message' => "Something Went Wrong!",
                        ];
                    }
                } else {
                    $response = [
                        'status' => 0,
                        'message' => $validate->errors()->first(),
                    ];
                }
            }
        } else {
            $response = [
                'status' => 0,
                'message' => "Something Went Wrong!",
            ];
        }

        return response()->json($response);
    }

    public function edit_review($id, Request $form)
    {
        $review_data = $this->productReview_model->where('id', $id)->first();
        if (!empty($review_data)) {

            if (Request()->isMethod('POST')) {

                $validate = validator::make($form->all(), [
                    'rating' => 'required',
                ], [], [
                    'rating' => 'Rating',
                ]);

                if (!$validate->fails()) {

                    $form_data['rating'] = $form['rating'];
                    $form_data['review'] = $form['review'];

                    if ($this->productReview_model->where('id', $review_data->id)->update($form_data)) {

                        $review_data = $this->productReview_model->where('id', $id)->first();
                        $customer_detail = $this->user_model->where('id', $review_data['customer_id'])->first();
                        $review_done_data = [
                            'id' => $review_data['id'],
                            'product_id' => $review_data['product_id'],
                            'rating' => $review_data['rating'],
                            'review' => $review_data['review'],
                            'created_at' => date("d-m-Y", strtotime($review_data['created_at'])),
                            'customer' => $customer_detail['fname'] . " " . $customer_detail['lname'],
                        ];

                        $response = [
                            'status' => 1,
                            'save_data' => $review_done_data,
                            'message' => "Review Updated Successfully.",
                        ];
                    } else {
                        $response = [
                            'status' => 0,
                            'message' => "Something Went Wrong!",
                        ];
                    }
                } else {
                    $response = [
                        'status' => 0,
                        'message' => $validate->errors()->first(),
                    ];
                }
            } else {
                $response = [
                    'status' => 1,
                    'review_data' => $review_data,
                ];
            }
        } else {
            $response = [
                'status' => 0,
                'message' => 'Something Went Wrong!',
            ];
        }
        return response()->json($response);
    }

    public function delete_review($id)
    {
        if ($this->productReview_model->where('id', $id)->forcedelete()) {
            $response = [
                'status' => 1,
                'message' => 'Review Deleted Successfully.',
            ];
        } else {
            $response = [
                'status' => 0,
                'message' => 'Something Went Wrong!',
            ];
        }
        return response()->json($response);
    }

    public function avg_star($id)
    {
        $data['total_review'] = $this->productReview_model->where('product_id', $id)->count();
        $data['total_star'] = $this->productReview_model->where('product_id', $id)->get()->sum('rating');

        if ($data['total_review'] and $data['total_star']) {
            $data['avg'] = ($data['total_star'] / $data['total_review']);
            $response['avg'] = round($data['avg']);
            $response['total_review'] = $data['total_review'];
        } else {
            $response['avg'] = 0;
            $response['total_review'] = $data['total_review'];

        }

        return $response;
    }

}
