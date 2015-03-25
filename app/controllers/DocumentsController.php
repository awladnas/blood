<?php namespace LifeLi\controllers;
use Illuminate\Support\Facades\Auth;
use LifeLi\models\Documents\Document;
class DocumentsController extends BaseController {

	/**
	 * Document Repository
	 *
	 * @var Document
	 */
	protected $document;

	public function __construct(Document $document)
	{
		$this->document = $document;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$documents = $this->document->all();

		return \View::make('documents.index', compact('documents'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return \View::make('documents.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = \Input::all();
		$validation = \Validator::make($input, Document::$rules);

		if ($validation->passes())
		{
			$this->document->create($input);

			return \Redirect::route('admin.documents.index')
                ->with('message', 'Successfully created.');
		}

		return \Redirect::route('admin.documents.create')
			->withInput()
			->withErrors($validation)
			->with('message', 'There were validation errors.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$document = $this->document->findOrFail($id);

		return \View::make('documents.show', compact('document'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$document = $this->document->find($id);

		if (is_null($document))
		{
			return \Redirect::route('documents.index');
		}

		return \View::make('documents.edit', compact('document'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$input = array_except(\Input::all(), '_method');
		$validation = \Validator::make($input, Document::$rules);

		if ($validation->passes())
		{
			$document = $this->document->find($id);
			$document->update($input);

			return \Redirect::route('admin.documents.show', $id)
                ->with('message', 'Successfully Updated.');;
		}

		return \Redirect::route('admin.documents.edit', $id)
			->withInput()
			->withErrors($validation)
			->with('message', 'There were validation errors.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->document->find($id)->delete();

		return \Redirect::route('admin.documents.index')
            ->with('message', 'Successfully deleted.');;
	}

    public function lists()
    {
        $documents = $this->document->all();

        return \View::make('documents.lists', compact('documents'));
    }

}
