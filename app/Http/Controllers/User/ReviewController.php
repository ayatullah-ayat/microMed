<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $maxId    = request()->max_id ?? 0;
        $limit    = request()->limit ?? 1;
        $operator = request()->operator ?? '<';

        $comments = $product->comments()
                    ->with('product','commentedBy', 'commentedBy.profile')
                    ->where('id', $operator, $maxId)
                    ->where('is_approved', 1)
                    ->latest()
                    ->limit($limit)
                    ->get();

        $lastId         = 0;
        $isLastRecord   = false;
        $lastData       = $product->comments()->where('is_approved', 1)->first();
        if($lastData){
            $lastId = $lastData->id;
        }

        $maxId          = 0;
        $html           = "";
        foreach ($comments as $comment):

            $maxId = $comment->id;
            if($lastId == $maxId) $isLastRecord = true;

            if($comment->commentedBy):
            $commentByName = $comment->commentedBy->username ?? $comment->commentedBy->name;
            $html .= "<div class=\"col tabs-product-comments d-flex\">
                
                <div class=\"reviw-person\">
                    {$this->profileImage($comment)}
                </div>

                <div class=\"comment-text\">
                    <h3> {$commentByName}</h3>
                    <ul class=\"tabs-product-review mb-2 list-unstyled d-flex\">
                        {$this->renderRattings($comment->ratting)}
                    </ul>

                    <p>{$comment->body}</p>
                </div>

            </div>";
            endif;

        endforeach;

        return response()->json([
            'html'  => $html,
            'max_id'=> $maxId,
            'isLast'=> $isLastRecord
        ]);

    }


    function renderRattings($ratting){

        $rattingHTML = "";
        for ($start=0; $start < 5; $start++):
            $rattingClass = $start < $ratting ? 'solid' : 'regular';
            $rattingHTML .= "<li><i class=\"fa-{$rattingClass} fa-star\"></i></li>";           
        endfor; 

        return $rattingHTML;
    }


    function profileImage($comment){
        $image = null;
        if($comment->commentedBy->profile):
            $imageSRC = asset($comment->commentedBy->profile->photo ?? 'assets/frontend/img/comment/comment.png');
            $image  = "<img src=\"{$imageSRC}\" alt=\"review person\">";
        else:
            $imageSRC = asset('assets/frontend/img/comment/comment.png');
            $image  = "<img src=\"{$imageSRC}\" alt=\"review person\">";
        endif;

        return $image;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ReviewRequest $request, Product $product)
    {
        try {

            $data = $request->only('ratting','comment','product_id');
            $review = $product->comments()->create([
                'ratting'       => $data['ratting'],
                'body'          => $data['comment'],
                'commented_by'  => auth()->guard('web')->user()->id ?? null
            ]);

            return response()->json([
                'success'   => true,
                'msg'       => 'Review Added Successfully',
                'data'      => $review->with('product','commentedBy', 'commentedBy.profile')->orderByDesc('reviews.id')->first()
            ]);

        } catch (\Exception $th) {
            return response()->json([
                'success'   => false,
                'msg'       => $th->getMessage(),
                'data'      => null
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
