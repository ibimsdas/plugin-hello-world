<?php
namespace HelloWorld\Controllers;

use Plenty\Plugin\Controller;
use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;
use Plenty\Plugin\Log\Loggable;

/**
 * Class ContentController
 * @package HelloWorld\Controllers
 */
class ContentController extends Controller
{

	use Loggable;

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @var Response
	 */
	private $response;

	/**
	 * @var array
	 */
	private $errors = [
		'I would like a table near to the window.',
		'Excuse me, this is my stop.',
		'Her name is Elisabeth.',
		'I advise you to go to hospital.',
		'This is my father.',
		'Can you tell me where I can buy some cheese, please.'
	];

	/**
	 * ContentController constructor.
	 *
	 * @param Request $request
	 * @param Response $response
	 */
	public function __construct(Request $request, Response $response)
	{
		$this->request = $request;
		$this->response = $response;
	}

	/**
	 * @return Response
	 */
	public function outputProducts()
	{
		$apiKey = $this->request->get('apiKey', '');
		if (!strlen($apiKey) || $apiKey !== 'dummy') {
			$data = [
				'error' => [
					'code' => time(),
					'message' => $this->errors[rand(0, 5)]
				]
			];
			return $this->response->json($data, Response::HTTP_BAD_REQUEST);
		}

		// HTTP-Headers
		$headers = [
			'Content-Type' => 'text/csv; charset=UTF-8'
		];

		$products = [
			['item_number', 'name', 'lang', 'price', 'recommendation'],
			['000-000-001', 'Produkt 1', 'de_DE', 1000, ''],
			['000-000-002', 'Produkt 2', 'de_DE', 501, ''],
			['000-000-003', 'Produkt 3', 'de_DE', 10, ''],
			['000-000-004', 'Produkt 4', 'de_DE', 999, ''],
			['000-000-005', 'Starprodukt', 'de_DE', 10, '000-000-001,000-000-003,000-000-004'],
			['000-000-005', 'Probe 1', 'de_DE', null, '']
		];

		$callback = function($value) {
			return implode(";", $value);
		};
		$content = implode("\n", array_map($callback, $products));

		return $this->response->make($content, Response::HTTP_OK, $headers);
	}
}
