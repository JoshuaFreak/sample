<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ClassificationLevel;
use App\Models\Level;
use App\Models\PercentageLevel;
use Illuminate\Http\Request;
use Datatables;

class PercentageLevelController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$percentage_level_list = PercentageLevel::all();
		return view('percentage_level.index', compact('percentage_level_list'));
	}

	public function getCreate() {
		return view('percentage_level.create');
	}

	public function data() {
		$percentage_level_list = PercentageLevel::join('level', 'percentage_level.level_id', '=', 'level.id')
			->join('classification_level', 'level.classification_level_id', '=', 'classification_level.id')
			->select(['percentage_level.id', 'level.level_name', 'level.level_code', 'percentage_level.range_from', 'percentage_level.range_to', 'classification_level.classification_level_name'])
			->orderBy('percentage_level.id');

		return Datatables::of($percentage_level_list)
			->remove_column('id')
			->make();

	}


}
