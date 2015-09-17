<?php
namespace App\Http\Controllers\Organisation;

use App\Http\Controllers\AdminController;
use Illuminate\Contracts\Validation\Factory;
use Input;

/**
 * @todo move this controller to the hulpverleners controller, as these are actually more like resource methods
 *       to the user instead of a actual resource on itself.
 * Class LocationController
 *
 * @package App\Http\Controllers\Organisation
 */
class LocationController extends AdminController
{

    /**
     * @var \App\Organisation\Location
     */
    protected $location;

    protected $organisation;

    public function __construct(\App\Organisation\Organisation $organisation, \App\Organisation\Location $location)
    {
        $this->organisation = $organisation;

        $this->location = $location;
    }

    public function index($organisationid)
    {
        $organisation = $this->organisation->with(array('locations'))->find($organisationid);

        return $organisation->locations->toJson();
    }

    public function store($organisationid, Factory $validator)
    {
        $input = Input::all();

        $input['organisation_id'] = $organisationid;

        $validator = $validator->make($input, $this->location->rules());

        if ($validator->fails()) {
            return array(
                'status' => 'error',
                'errors' => $validator->messages()->toArray()
            );
        } else {
            $location = $this->location->create($input);

            return array(
                'status'   => 'oke',
                'location' => $location->toArray()
            );
        }
    }
}