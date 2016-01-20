<?php namespace Modules\Documents\Http\Controllers\backend;

use Modules\Core\Http\Controllers\AdminBaseController;

/**
 * Class DocumentsController
 * @package Modules\Documents\Http\Controllers\backend
 */
class DocumentsController extends AdminBaseController
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('documents::backend.index');
    }

}
