<?php

namespace Modules\Documents\Http\Controllers\api;

use Illuminate\Http\Request;
use Modules\Blog\Repositories\ArticleRepository;
use Modules\Blog\Transformers\ArticleFilesTransformer;
use Modules\Core\Http\Controllers\ApiBaseController;
use Modules\Core\Http\Requests\MediaFileRequest;


/**
 * Class ArticleFileController
 * @package Modules\Blog\Http\Controllers\api
 */
class FileController extends ApiBaseController
{
    /**
     * @var PageRepository
     */
    private $article;

    /**
     * ArticleFileController constructor.
     * @param ArticleRepository $article
     */
    public function __construct(ArticleRepository $article)
    {
        parent::__construct();
        $this->article = $article;
    }

    /**
     * @param Request $request
     * @param         $slug
     * @return mixed
     */
    public function index(Request $request, $slug)
    {
        $article = $this->article->findBySlug($slug);

        return $this->response->collection($article->getMedia('files'), new ArticleFilesTransformer());
    }

    /**
     * @param MediaFileRequest $request
     * @param                  $slug
     * @return mixed
     */
    public function store(MediaFileRequest $request, $slug)
    {
        $article = $this->article->findBySlug($slug);

        $savedFile = $article->addMedia($request->files->get('file'))
            ->withCustomProperties(['mime-type' => $request->files->get('file')->getMimeType()])
            ->toMediaLibrary('files');

        $resourceUrl = app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('api.blog.article.file.show', ['article' => $slug, 'file' => $savedFile->id]);

        return $this->response->item($savedFile, new ArticleFilesTransformer());
    }

    /**
     * @param Request $request
     * @param         $slug
     * @param         $file
     * @return mixed
     */
    public function show(Request $request, $slug, $file)
    {
        $article = $this->article->findBySlug($slug);

        return $this->response->item($article->getMedia('files')->keyBy('id')->get($file), new ArticleFilesTransformer());
    }

    /**
     * @param Request $request
     * @param         $slug
     * @param         $file
     * @return mixed
     */
    public function destroy(Request $request, $slug, $file)
    {
        $article = $this->article->findBySlug($slug);
        
        $article->getMedia('files')->keyBy('id')->get($file)->delete();

        return $this->response->noContent();
    }
}
