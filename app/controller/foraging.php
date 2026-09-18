<?php

class ForagingController extends BaseController {
    const INDEX_LAYOUT = 'layout';

    public function __construct()
    {
        parent::__construct(self::INDEX_LAYOUT);
    }

    public function view_list($request)
    {
        $user = UserModel::getAuthUser();

        $type = $request->params()->query('type', '');
        $habitat = $request->params()->query('habitat', '');
        $season = $request->params()->query('season', 'current');

        $month = null;
        if ($season === 'current') {
            $month = (int)date('n');
        } elseif ($season !== 'all' && $season !== '') {
            $month = (int)$season;
        }

        $categories = ForageCategoryModel::getAllFiltered(
            ($type !== '' ? $type : null),
            ($habitat !== '' ? $habitat : null),
            $month
        );

        return parent::view(['content', 'foraging'], [
            'user' => $user,
            'categories' => $categories,
            'selected_type' => $type,
            'selected_habitat' => $habitat,
            'selected_season' => $season
        ]);
    }

    public function view_category($request)
    {
        $user = UserModel::getAuthUser();
        $id = $request->arg('id');
        $category = ForageCategoryModel::getDetails($id);

        if (!$category) {
            return redirect('/foraging');
        }

        $locations = ForageLocationModel::getForCategory($id);

        $logs = [];
        foreach ($locations as $loc) {
            $logs[$loc->get('id')] = ForageLocationLogModel::getForLocation($loc->get('id'));
        }

        $photos = ForageCategoryPhotoModel::getCategoryGallery($id);

        return parent::view(['content', 'foraging_details'], [
            'user' => $user,
            'category' => $category,
            'locations' => $locations,
            'logs' => $logs,
            'photos' => $photos
        ]);
    }

    public function add_category_photo($request)
    {
        $category_id = $request->params()->query('category_id', null);
        $label = $request->params()->query('label', '');

        try {
            ForageCategoryPhotoModel::uploadPhoto($category_id, $label);
            FlashMessage::setMsg('success', __('app.foraging_photo_uploaded'));
        } catch (\Exception $e) {
            FlashMessage::setMsg('error', $e->getMessage());
        }

        return redirect('/foraging/' . $category_id);
    }

    public function remove_category_photo($request)
    {
        $id = $request->params()->query('id', null);
        $category_id = $request->params()->query('category_id', null);

        ForageCategoryPhotoModel::removePhoto($id);

        FlashMessage::setMsg('success', __('app.foraging_photo_removed'));
        return redirect('/foraging/' . $category_id);
    }

    public function set_category_photo_as_main($request)
    {
        $id = $request->params()->query('id', null);
        $category_id = $request->params()->query('category_id', null);

        ForageCategoryPhotoModel::setAsMainPhoto($id);

        FlashMessage::setMsg('success', __('app.foraging_photo_set_main'));
        return redirect('/foraging/' . $category_id);
    }

    public function create_location_log($request)
    {
        $validator = new Asatru\Controller\PostValidator([
            'location_id' => 'required',
            'entry_date' => 'required',
            'note' => 'required'
        ]);
        if (!$validator->isValid()) {
            $errorstr = '';
            foreach ($validator->errorMsgs() as $err) {
                $errorstr .= $err . '<br/>';
            }
            FlashMessage::setMsg('error', 'Invalid data given:<br/>' . $errorstr);
            return back();
        }

        $location_id = $request->params()->query('location_id', null);
        $category_id = $request->params()->query('category_id', null);
        $entry_date = $request->params()->query('entry_date', null);
        $note = $request->params()->query('note', null);

        ForageLocationLogModel::addEntry($location_id, $entry_date, $note);

        FlashMessage::setMsg('success', __('app.foraging_log_added'));
        return redirect('/foraging/' . $category_id);
    }

    public function remove_location_log($request)
    {
        $id = $request->params()->query('id', null);
        $category_id = $request->params()->query('category_id', null);

        ForageLocationLogModel::removeEntry($id);

        FlashMessage::setMsg('success', __('app.foraging_log_removed'));
        return redirect('/foraging/' . $category_id);
    }

    public function create_category($request)
    {
        $validator = new Asatru\Controller\PostValidator([
            'name' => 'required'
        ]);
        if (!$validator->isValid()) {
            $errorstr = '';
            foreach ($validator->errorMsgs() as $err) {
                $errorstr .= $err . '<br/>';
            }
            FlashMessage::setMsg('error', 'Invalid data given:<br/>' . $errorstr);
            return back();
        }

        $name = $request->params()->query('name', null);
        $season_start_month = $request->params()->query('season_start_month', '');
        $season_end_month = $request->params()->query('season_end_month', '');
        $notes = $request->params()->query('notes', null);
        $grocy_name = $request->params()->query('grocy_name', null);
        $harvested = ($request->params()->query('harvested', '0') === '1') ? 1 : 0;
        $type = $request->params()->query('type', '');
        $habitat = $request->params()->query('habitat', '');

        ForageCategoryModel::addCategory($name, ($season_start_month !== '' ? $season_start_month : null), ($season_end_month !== '' ? $season_end_month : null), $notes, ($grocy_name !== '' ? $grocy_name : null), $harvested, ($type !== '' ? $type : null), ($habitat !== '' ? $habitat : null));

        FlashMessage::setMsg('success', __('app.foraging_category_added'));
        return redirect('/foraging');
    }

    public function edit_category($request)
    {
        $id = $request->params()->query('id', null);
        $existing = ForageCategoryModel::getDetails($id);

        $name = $request->params()->query('name', null);
        $season_start_month = $request->params()->query('season_start_month', '');
        $season_end_month = $request->params()->query('season_end_month', '');
        $notes = $request->params()->query('notes', null);
        $grocy_name = $request->params()->query('grocy_name', null);
        $harvested = ($request->params()->query('harvested', '0') === '1') ? 1 : 0;
        $type = $request->params()->query('type', ($existing ? $existing->get('type') : ''));
        $habitat = $request->params()->query('habitat', ($existing ? $existing->get('habitat') : ''));

        ForageCategoryModel::editCategory($id, $name, ($season_start_month !== '' ? $season_start_month : null), ($season_end_month !== '' ? $season_end_month : null), $notes, ($grocy_name !== '' ? $grocy_name : null), $harvested, ($type !== '' ? $type : null), ($habitat !== '' ? $habitat : null));

        FlashMessage::setMsg('success', __('app.foraging_category_updated'));
        return redirect('/foraging/' . $id);
    }

    public function remove_category($request)
    {
        $id = $request->params()->query('id', null);
        ForageCategoryModel::removeCategory($id);

        FlashMessage::setMsg('success', __('app.foraging_category_removed'));
        return redirect('/foraging');
    }

    public function create_location($request)
    {
        $validator = new Asatru\Controller\PostValidator([
            'category_id' => 'required',
            'latitude' => 'required',
            'longitude' => 'required'
        ]);
        if (!$validator->isValid()) {
            $errorstr = '';
            foreach ($validator->errorMsgs() as $err) {
                $errorstr .= $err . '<br/>';
            }
            FlashMessage::setMsg('error', 'Invalid data given:<br/>' . $errorstr);
            return back();
        }

        $category_id = $request->params()->query('category_id', null);
        $name = $request->params()->query('name', null);
        $latitude = $request->params()->query('latitude', null);
        $longitude = $request->params()->query('longitude', null);
        $notes = $request->params()->query('notes', null);
        $last_visited = $request->params()->query('last_visited', '');

        ForageLocationModel::addLocation($category_id, $name, $latitude, $longitude, $notes, ($last_visited !== '' ? $last_visited : null));

        FlashMessage::setMsg('success', __('app.foraging_location_added'));
        return redirect('/foraging/' . $category_id);
    }

    public function edit_location($request)
    {
        $id = $request->params()->query('id', null);
        $category_id = $request->params()->query('category_id', null);
        $name = $request->params()->query('name', null);
        $latitude = $request->params()->query('latitude', null);
        $longitude = $request->params()->query('longitude', null);
        $notes = $request->params()->query('notes', null);
        $last_visited = $request->params()->query('last_visited', '');

        ForageLocationModel::editLocation($id, $name, $latitude, $longitude, $notes, ($last_visited !== '' ? $last_visited : null));

        FlashMessage::setMsg('success', __('app.foraging_location_updated'));
        return redirect('/foraging/' . $category_id);
    }

    public function remove_location($request)
    {
        $id = $request->params()->query('id', null);
        $category_id = $request->params()->query('category_id', null);

        ForageLocationModel::removeLocation($id);

        FlashMessage::setMsg('success', __('app.foraging_location_removed'));
        return redirect('/foraging/' . $category_id);
    }
}
