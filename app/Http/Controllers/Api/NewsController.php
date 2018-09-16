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
    * if you want to sort by date use localhost:8000/api/news?sortByDate=1
    * if you want to sort by title use localhost:8000/api/news?sortByTitle=1
    * if you want to sort by date and title use localhost:8000/api/news?sortByDate=1&sortByTitle=1
    * if you want to filter by date use localhost:8000/api/news?filterByDate=1978-06-21
    * if you want to filter by title use localhost:8000/api/news?filterByTitle=e
    * if you want to filter by date and title use localhost:8000/api/news?filterByDate=1978-06-21&filterByTitle=k
    */
    public function index()
    {
        $sortByDate = $sortByTitle = $filterByDate = $filterByTitle = null;

        if (isset($_GET['sortByDate']) && $_GET['sortByDate'] == 1) {
            $sortByDate = 'date';
        }
        if (isset($_GET['sortByTitle']) && $_GET['sortByTitle'] == 1) {
            $sortByTitle = 'title';
        }
        if (isset($_GET['filterByDate'])){
            $filterByDate = $_GET['filterByDate'];
        }
        if (isset($_GET['filterByTitle'])){
            $filterByTitle = $_GET['filterByTitle'];
        }
        $news = News::when($sortByDate, function ($query, $sortByDate) {
            return $query->orderBy($sortByDate);
        })->when($sortByTitle, function ($query, $sortByTitle) {
            return $query->orderBy($sortByTitle);
        })->when($filterByDate, function ($query, $filterByDate) {
            return $query->where('date', 'like', '%' . $filterByDate . '%');
        })->when($filterByTitle, function ($query, $filterByTitle) {
            return $query->where('title', 'like', '%' . $filterByTitle . '%');
        },function ($query) {
            return $query->orderBy('date');
        })->get();

        return Super::jsonResponse(true, 200, 'News found successfully', [], $news);
    }

    /*
     * News Show
     * parameter : { news_id: integer}
     * cases :  news_id found successfully - news_id not found
     * return : if news found return news object - if news not found return empty object
     * /news/show
     * example : localhost:8000/api/news/show?news_id=5
     */
    public function show(Request $request)
    {
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
  * example : localhost:8000/api/news/edit?news_id=8&title=naguib mahfouz&short_desc=Egyptian writer who won the 1988 Nobel Prize for Literature.
     &text=He is regarded as one of the first contemporary writers of Arabic literature, along with Tawfiq el-Hakim, to explore themes of existentialism.
    &date=1911-12-11
  */

    public function edit(Request $request)
    {
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
        return Super::jsonResponse(true, 200, 'News updated successfully', [], $news);
    }

    /*
     * delete News
     * parameter : {news_id: integer  }
     * cases :  News deleted successfully - Failed to delete news
     * return : empty object
     * /news/delete
     * example : localhost:8000/api/news/delete?news_id=8
     */
    public function delete(Request $request)
    {
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
