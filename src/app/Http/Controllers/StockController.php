<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(string $id) {
		$stock = Stock::where('productId', $id)->get();
		if ($stock) {
			return response()->json($stock, 200);
		} else {
			return response()->json(['message' => 'Not Found.'], 404);
		}
	}

    /**

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
		$data = $request->all();
		$client = new Client();
		try {
			$response = $client->request('GET', 'http://products:3081/' . $data['productId']);
		} catch (ClientException $e) {
			return response()->json(
				array_merge(
					[
						'microservice' => 'products',
					],
					json_decode($e->getResponse()->getBody()->getContents(), true)
				),
				$e->getResponse()->getStatusCode()
			);
		}

		$responseContent = json_decode($response->getBody()->getContents());

		if (empty($responseContent)) {
			return response()->json([
				'message' => 'Product not found',
			], 404);
		}

		$stock = Stock::create($data);

		return response()->json($stock, 201);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\Stock  $stock
	 * @return \Illuminate\Http\Response
	 */

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\Stock  $stock
	 * @return \Illuminate\Http\Response
	 */
	public function update(int $id, Request $request)
	{
		$stock = Stock::find($id);
		if ($stock) {
			$stock->update($request->all());
			return response()->json($stock, 200);
		} else {
			return response()->json(['message' => 'Not Found.'], 404);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\Stock  $stock
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, int $id)
	{
		$stock = Stock::find($id);
		if ($stock) {
			$stock->delete();
			return response()->json(null, 204);
		} else {
			return response()->json(['message' => 'Not Found.'], 404);
		}
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id) {
		$stock = Stock::find($id);
		if ($stock) {
			return response()->json($stock, 200);
		} else {
			return response()->json(['message' => 'Not Found.'], 404);
		}
	}
}
