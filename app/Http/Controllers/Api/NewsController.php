<?php

namespace App\Http\Controllers\Api;

use App\Models\News;
use Illuminate\Http\Request;
use Validator;
use Super;
use App\Http\Controllers\Controller;

class NewsController extends Controller
{
    public function __construct(Request $request)
    {
        //empty object
        $this->empty_object = new \stdClass();
    }


    /*
    * Get All News
    * parameter : -
    * cases : News found - no news
    * return : if news found return array of objects- if news not fount return empty array
    * /news
    */
    public function index()
    {
        $news = News::orderBy('date', 'desc')
            ->orderBy('title', 'desc')
            ->get();

        return Super::jsonResponse(true, 200, 'News found successfully', [], $news);
    }

    /*
     * News Show
     * parameter : { news_id: integer}
     * cases :  news_id found successfully - news_id not found
     * return : if news found return news object - if news not found return empty object
     * /news/show
     */
    public function show(Request $request)
    {
        if (!$request->isMethod('POST')) {
            return Super::jsonResponse(false, 405, 'Method Not Allowed', [], $this->empty_object);
        }
        // Validation area
        $validator = Validator::make($request->all(), [
            'news_id' => 'required|exists:news,id',
        ]);
        if ($validator->fails()) {
            return Super::jsonResponse(false, 400, $validator->errors()->first(), $validator->errors(), $this->empty_object);
        }
        // get news to requested id
        $news = News::find($request->news_id);

        return Super::jsonResponse(true, 200, 'News found successfully', [], $news);
    }

    /*
   * Add News
   * parameter : { title: string, short_desc: string, date: date, text: text }
   * cases :  News added successfully - Failed to add news
   * return : if news added return news object - if failed add news return empty object
   * /news/create
   */
    public function create(Request $request)
    {
        if (!$request->isMethod('POST')) {
            return Super::jsonResponse(false, 405, 'Method Not Allowed', [], $this->empty_object);
        }
        // Validation area
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'short_desc' => 'required',
            'date' => 'required|date',
            'text' => 'required',
        ]);
        if ($validator->fails()) {
            return Super::jsonResponse(false, 400, $validator->errors()->first(), $validator->errors(), $this->empty_object);
        }

        // create new news
        try {
            $create_news = News::create($request->all());
        } catch (\Exception $ex) {
            return Super::jsonResponse(false, 404, 'Failed to add news', [], $this->empty_object);
        }
        // return this response if add news successfully
        return Super::jsonResponse(true, 200, 'News found successfully', [], $create_news);
    }

    /*
  * Edit News
  * parameter : {news_id: integer (title: string, short_desc: string, date: date, text: text)->optional }
  * cases :  News edited successfully - Failed to edit news
  * return : if news edited return news object after edit - if failed add news return empty object
  * /news/edit
  */

    public function edit(Request $request)
    {
        if (!$request->isMethod('PUT')) {
            return Super::jsonResponse(false, 405, 'Method Not Allowed', [], $this->empty_object);
        }
        // Validation area
        $validator = Validator::make($request->all(), [
            'news_id' => 'required|exists:news,id',
        ]);
        if ($validator->fails()) {
            return Super::jsonResponse(false, 400, $validator->errors()->first(), $validator->errors(), $this->empty_object);
        }
        // get news to requested id
        $news = News::find($request->news_id);

        // try edit requested news
        try {
            $edit_news = $news->update($request->all());
        } catch (\Exception $ex) {
            return Super::jsonResponse(false, 404, 'Failed to update news', [], $this->empty_object);
        }
        // return this response if edit news successfully
        return Super::jsonResponse(true, 200, 'News updated successfully', [], $edit_news);
    }

    /*
     * delete News
     * parameter : {news_id: integer  }
     * cases :  News deleted successfully - Failed to delete news
     * return : empty object
     * /news/delete
     */
    public function delete(Request $request)
    {
        if (!$request->isMethod('DELETE')) {
            return Super::jsonResponse(false, 405, 'Method Not Allowed', [], $this->empty_object);
        }
        // Validation area
        $validator = Validator::make($request->all(), [
            'news_id' => 'required|exists:news,id',
        ]);
        if ($validator->fails()) {
            return Super::jsonResponse(false, 400, $validator->errors()->first(), $validator->errors(), $this->empty_object);
        }
        // get news to requested id
        $news = News::find($request->news_id);

        // try delete requested news
        try {
            $delete_news = $news->delete();
        } catch (\Exception $ex) {
            return Super::jsonResponse(false, 404, 'Failed to delete news', [], $this->empty_object);
        }
        // return this response if deleted news
        return Super::jsonResponse(true, 200, 'News deleted successfully', [], $this->empty_object);
    }
}
