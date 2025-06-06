<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class InertiaBaseController extends Controller
{
    protected $model;
    protected $storeRequestClass;
    protected $updateRequestClass;
    protected $folderPath;
    protected $routeName;
    protected $resourceClass;
    protected $CollectionClass;


    public function index(Request $request)
    {
        $items = $this->model::getData($request->all());

        //return new $this->CollectionClass($items);

        return inertia($this->folderPath . '/Index', [
            'data' => new $this->CollectionClass($items),
            'filters' => request()->all(['search', 'per_page'])
        ]);
    }

    public function create()
    {
        return Inertia::render($this->folderPath . "/Create");
    }

    public function store(Request $request)
    {
        // Validate using the request class directly
        $validatedData = $request->validate((new $this->storeRequestClass)->rules());
        $this->model::create($validatedData);
        if ($this->routeName) {
            return redirect()->route($this->routeName)
                ->with('success', 'Data created successfully.');
        }
        return redirect()->back()
            ->with('success', 'Data Updated successfully.');

    }

    public function show($id)
    {
        try {
            return Inertia::render($this->folderPath . '/Show', [
                'item' => new $this->resourceClass($this->model::findOrFail($id))
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'data not found'], 404);
        }
    }

    public function edit($id)
    {
        return Inertia::render($this->folderPath . '/Edit', [
            'item' => new $this->resourceClass($this->model::findOrFail($id))
        ]);
    }


    public function update(Request $request, $id)
    {
        $item = $this->model::findOrFail($id);
        //
        $validatedData = $request->validate((new $this->updateRequestClass)->rules());
        //
        $item->update($validatedData);
        //
        if ($this->routeName) {
            return redirect()->route($this->routeName)
            ->with('success', 'Data Updated successfully.');
        }
        return redirect()->back()
            ->with('success', 'Data Updated successfully.');
    }

    public function destroy($id)
    {
        $item = $this->model::findOrFail($id);
        $item->delete();

        if ($this->routeName) {
            return redirect()->route($this->routeName)
            ->with('success', 'Data deleted successfully.');
        }
        return redirect()->back()
            ->with('success', 'Data deletedp successfully.');
    }

    protected function validateRequest(Request $request, $requestClass)
    {
        $requestInstance = new $requestClass($request->all());
        return $requestInstance->validate();
    }

    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data'    => $result,
        ];

        return response()->json($response);
    }

    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
            'code'    => $code,
        ];

        if (! empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
