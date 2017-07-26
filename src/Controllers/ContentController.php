<?php
namespace HelloWorld\Controllers;

use Plenty\Plugin\Controller;
use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;

/**
 * Class ContentController
 * @package HelloWorld\Controllers
 */
class ContentController extends Controller
{

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @var Response
	 */
	private $response;

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
	 * @return string
	 */
	public function outputProducts(): string
	{
		$apiKey = $this->request->get('apiKey', '');
		if (!strlen($apiKey) && $apiKey !== 'dummy') {
			$this->response->make('missing/wrong api key', Response::HTTP_BAD_REQUEST);
		}

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
			return implode('\t', $value);
		};
		$content = implode('\n', array_map($callback, $products));

		return $this->response->make($content);
	}
}
