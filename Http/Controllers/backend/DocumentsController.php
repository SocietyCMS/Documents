<?php namespace Modules\Documents\Http\Controllers\backend;

use Pingpong\Modules\Routing\Controller;

/**
 * Class DocumentsController
 * @package Modules\Documents\Http\Controllers\backend
 */
class DocumentsController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('documents::backend.index');
    }

}
