<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(string $id) {
		$stock = Stock::where('productId', $id)->get();
		if ($stock) {
			return response()->json($stock, 200);
		} else {
			return response()->json(['message' => 'Not Found.'], 404);
		}
	}

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

	public function destroy(int $id)
	{
		$stock = Stock::find($id);
		if ($stock) {
			$stock->delete();
			return response()->json(null, 204);
		} else {
			return response()->json(['message' => 'Not Found.'], 404);
		}
	}

    public function edit(int $id) {
		$stock = Stock::find($id);
		if ($stock) {
			return response()->json($stock, 200);
		} else {
			return response()->json(['message' => 'Not Found.'], 404);
		}
	}
}
