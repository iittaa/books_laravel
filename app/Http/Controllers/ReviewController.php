<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::where("status", 1)->orderBy("created_at", "DESC")->paginate(9);
        
        // dd($reviews);
        
    	return view("index", compact("reviews"));
    }
    
    public function create()
    {
        return view("review");
    }
    
    public function store(Request $request)
    {
        $validationData = $request->validate([
            "title" => "required | max:255",
            "body" => "required",
            "image" => "mimes:jpeg,png,jpg,gif,svg | max:2048"
        ]);
        
        
        if ($request->hasFile("image")) {
            $request->file("image")->store("/public/images");
            
            Review::create([
                "user_id" => Auth::id(),
                "title" => $request->title,
                "body" => $request->body,
                "image" => $request->file("image")->hashName(),
            ]);
        } else {
            Review::create([
                "user_id" => Auth::id(),
                "title" => $request->title,
                "body" => $request->body,
            ]);
        }
        
        return redirect()->route("index")->with("flash_message", "投稿が完了しました。");
    }
    
    public function show($id)
    {
        $review = Review::where("id", $id)->where("status", 1)->first();
        
        return view("show", compact("review"));
    }
    
    
}
