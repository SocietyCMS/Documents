<?php namespace Modules\Documents\Http\Controllers\backend;

use Pingpong\Modules\Routing\Controller;

class DocumentsController extends Controller {
	
	public function index()
	{
		return view('documents::backend.index');
	}
	
}