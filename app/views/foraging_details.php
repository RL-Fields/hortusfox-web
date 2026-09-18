<h1>{{ $category->get('name') }}</h1>

@include('flashmsg.php')

<div class="box">
	@if ($category->get('photo'))
	<img src="{{ abs_photo($category->get('photo')) }}" alt="photo" style="float:right; max-width:150px; max-height:150px; margin-left:15px; border-radius:4px;">
	@endif
	@if (($category->get('season_start_month')) && ($category->get('season_end_month')))
	<p><strong>{{ __('app.foraging_season') }}:</strong> {{ ForageCategoryModel::monthName($category->get('season_start_month')) }} &ndash; {{ ForageCategoryModel::monthName($category->get('season_end_month')) }}</p>
	@endif
        <p>
        @if ($category->get('harvested'))
        <span class="tag is-success">{{ __('app.foraging_harvested_yes') }}</span>
        @else
        <span class="tag is-light">{{ __('app.foraging_harvested_no') }}</span>
        @endif
        @if ($category->get('type'))
        <span class="tag is-info">{{ ForageCategoryModel::typeName($category->get('type')) }}</span>
        @endif
        @if ($category->get('habitat'))
        <span class="tag is-link">{{ ForageCategoryModel::habitatName($category->get('habitat')) }}</span>
        @endif
        </p>
	@if ($category->get('grocy_name'))
	<p><strong>{{ __('app.foraging_grocy_name') }}:</strong> {{ $category->get('grocy_name') }}</p>
	@endif
	@if ($category->get('notes'))
	<p>{{ $category->get('notes') }}</p>
	@endif
	@if ((!$category->get('season_start_month')) && (!$category->get('notes')) && (!$category->get('grocy_name')))
	<p class="has-text-grey">{{ __('app.foraging_no_details') }}</p>
	@endif
</div>

<div class="margin-vertical">
	<div class="action-strip action-strip-left">
		<div class="is-inline-block is-action-button-margin"><a class="button" href="javascript:void(0);" onclick="document.getElementById('foraging-edit-category-form').classList.toggle('is-hidden');">{{ __('app.foraging_edit') }}</a></div>
		<div class="is-inline-block is-action-button-margin"><a class="button is-danger" href="javascript:void(0);" onclick="if (confirm('{{ __('app.foraging_confirm_delete') }}')) { document.getElementById('foraging-remove-category-form').submit(); }">{{ __('app.foraging_remove') }}</a></div>
		<div class="is-inline-block is-action-button-margin"><a class="is-default-link is-fixed-button-link" href="{{ url('/foraging') }}">{{ __('app.foraging_back') }}</a></div>
	</div>
</div>

<form id="foraging-remove-category-form" method="POST" action="{{ url('/foraging/remove') }}" class="is-hidden">
@csrf
	<input type="hidden" name="id" value="{{ $category->get('id') }}">
</form>

<div id="foraging-edit-category-form" class="box is-hidden">
	<form method="POST" action="{{ url('/foraging/edit') }}">
		@csrf
		<input type="hidden" name="id" value="{{ $category->get('id') }}">
		<div class="field">
			<label class="label">{{ __('app.foraging_category_name') }}</label>
			<div class="control">
				<input type="text" class="input" name="name" value="{{ $category->get('name') }}" required>
			</div>
		</div>
		<div class="field">
			<label class="label">{{ __('app.foraging_grocy_name') }}</label>
			<div class="control">
				<input type="text" class="input" name="grocy_name" value="{{ $category->get('grocy_name') }}">
			</div>
		</div>
                <div class="field">
                        <label class="label">{{ __('app.foraging_filter_type') }}</label>
                        <div class="control">
                                <div class="select">
                                        <select name="type">
                                                <option value="">{{ __('app.foraging_not_set') }}</option>
                                                @foreach (ForageCategoryModel::getTypes() as $key => $label)
                                                <option value="{{ $key }}" {{ ($category->get('type') === $key) ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                        </select>
                                </div>
                        </div>
                </div>
                <div class="field">
                        <label class="label">{{ __('app.foraging_filter_habitat') }}</label>
                        <div class="control">
                                <div class="select">
                                        <select name="habitat">
                                                <option value="">{{ __('app.foraging_not_set') }}</option>
                                                @foreach (ForageCategoryModel::getHabitats() as $key => $label)
                                                <option value="{{ $key }}" {{ ($category->get('habitat') === $key) ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                        </select>
                                </div>
                        </div>
                </div>
		<div class="field">
			<div class="control">
				<label class="checkbox">
					<input type="checkbox" name="harvested" value="1" {{ ($category->get('harvested')) ? 'checked' : '' }}>
					{{ __('app.foraging_harvested') }}
				</label>
			</div>
		</div>
		<div class="field">
			<label class="label">{{ __('app.foraging_season') }}</label>
			<div class="control is-flex">
				<div class="select">
					<select name="season_start_month">
						<option value="">{{ __('app.foraging_not_set') }}</option>
						@foreach ([1,2,3,4,5,6,7,8,9,10,11,12] as $num)
						<option value="{{ $num }}" {{ ($category->get('season_start_month') == $num) ? 'selected' : '' }}>{{ ForageCategoryModel::monthName($num) }}</option>
						@endforeach
					</select>
				</div>
				&nbsp;{{ __('app.foraging_to') }}&nbsp;
				<div class="select">
					<select name="season_end_month">
						<option value="">{{ __('app.foraging_not_set') }}</option>
						@foreach ([1,2,3,4,5,6,7,8,9,10,11,12] as $num)
						<option value="{{ $num }}" {{ ($category->get('season_end_month') == $num) ? 'selected' : '' }}>{{ ForageCategoryModel::monthName($num) }}</option>
						@endforeach
					</select>
				</div>
			</div>
		</div>
		<div class="field">
			<label class="label">{{ __('app.foraging_notes') }}</label>
			<div class="control">
				<textarea class="textarea" name="notes">{{ $category->get('notes') }}</textarea>
			</div>
		</div>
		<div class="field">
			<div class="control">
				<button type="submit" class="button is-success">{{ __('app.foraging_save') }}</button>
			</div>
		</div>
	</form>
</div>

<div id="foraging-map" style="height: 400px; margin-bottom: 20px;"></div>

<p>{{ __('app.foraging_map_hint') }} <a class="button is-small" href="javascript:void(0);" onclick="document.getElementById('foraging-add-location-form').classList.remove('is-hidden');">{{ __('app.foraging_add_location_manually') }}</a></p>

<div id="foraging-add-location-form" class="box is-hidden">
	<form method="POST" action="{{ url('/foraging/location/create') }}">
		@csrf
		<input type="hidden" name="category_id" value="{{ $category->get('id') }}">
		<div class="field">
			<label class="label">{{ __('app.foraging_location_name') }}</label>
			<div class="control">
				<input type="text" class="input" name="name">
			</div>
		</div>
		<div class="field">
			<label class="label">{{ __('app.foraging_latitude') }}</label>
			<div class="control">
				<input type="text" class="input" id="foraging-new-lat" name="latitude" required>
			</div>
		</div>
		<div class="field">
			<label class="label">{{ __('app.foraging_longitude') }}</label>
			<div class="control">
				<input type="text" class="input" id="foraging-new-lng" name="longitude" required>
			</div>
		</div>
		<div class="field">
			<label class="label">{{ __('app.foraging_last_visited') }}</label>
			<div class="control">
				<input type="date" class="input" name="last_visited">
			</div>
		</div>
		<div class="field">
			<label class="label">{{ __('app.foraging_notes') }}</label>
			<div class="control">
				<textarea class="textarea" name="notes"></textarea>
			</div>
		</div>
		<div class="field">
			<div class="control">
				<button type="submit" class="button is-success">{{ __('app.foraging_add_location') }}</button>
				<a class="button" href="javascript:void(0);" onclick="document.getElementById('foraging-add-location-form').classList.add('is-hidden');">{{ __('app.foraging_cancel') }}</a>
			</div>
		</div>
	</form>
</div>

<h2>{{ __('app.foraging_photos') }}</h2>

<div class="margin-vertical">
	<a class="button is-small" href="javascript:void(0);" onclick="document.getElementById('foraging-add-photo-form').classList.toggle('is-hidden');">{{ __('app.foraging_upload_photo') }}</a>
</div>

<div id="foraging-add-photo-form" class="box is-hidden">
	<form method="POST" action="{{ url('/foraging/category/photo/add') }}" enctype="multipart/form-data">
		@csrf
		<input type="hidden" name="category_id" value="{{ $category->get('id') }}">
		<div class="field">
			<label class="label">{{ __('app.foraging_photo') }}</label>
			<div class="control">
				<input type="file" name="photo" accept="image/*" required>
			</div>
		</div>
		<div class="field">
			<label class="label">{{ __('app.foraging_photo_label') }}</label>
			<div class="control">
				<input type="text" class="input" name="label">
			</div>
		</div>
		<div class="field">
			<div class="control">
				<button type="submit" class="button is-success">{{ __('app.foraging_upload_photo') }}</button>
				<a class="button" href="javascript:void(0);" onclick="document.getElementById('foraging-add-photo-form').classList.add('is-hidden');">{{ __('app.foraging_cancel') }}</a>
			</div>
		</div>
	</form>
</div>

@if ((isset($photos)) && (is_countable($photos)) && (count($photos) > 0))
<div style="display:flex; flex-wrap:wrap; gap:10px;">
	@foreach ($photos as $photo)
	<div class="box" style="width:160px;">
		<a href="{{ abs_photo($photo->get('original')) }}" target="_blank">
			<img src="{{ abs_photo($photo->get('thumb')) }}" alt="photo" style="max-width:120px; max-height:120px; display:block;">
		</a>
		@if ($photo->get('label'))
		<p>{{ $photo->get('label') }}</p>
		@endif
		<form method="POST" action="{{ url('/foraging/category/photo/setmain') }}" style="display:inline;">
			@csrf
			<input type="hidden" name="id" value="{{ $photo->get('id') }}">
			<input type="hidden" name="category_id" value="{{ $category->get('id') }}">
			<button type="submit" class="button is-small">{{ __('app.foraging_set_main_photo') }}</button>
		</form>
		<form method="POST" action="{{ url('/foraging/category/photo/remove') }}" style="display:inline;">
			@csrf
			<input type="hidden" name="id" value="{{ $photo->get('id') }}">
			<input type="hidden" name="category_id" value="{{ $category->get('id') }}">
			<button type="submit" class="button is-small is-danger" onclick="return confirm('{{ __('app.foraging_confirm_delete') }}');">{{ __('app.foraging_remove') }}</button>
		</form>
	</div>
	@endforeach
</div>
@else
<p class="has-text-grey">{{ __('app.foraging_no_photos') }}</p>
@endif

<h2>{{ __('app.foraging_locations') }}</h2>

@if ((isset($locations)) && (is_countable($locations)) && (count($locations) > 0))
	@foreach ($locations as $loc)
	<div class="box">
		<div class="is-pulled-right">
			<a class="button is-small" href="javascript:void(0);" onclick="document.getElementById('foraging-edit-location-form-{{ $loc->get('id') }}').classList.toggle('is-hidden');">{{ __('app.foraging_edit') }}</a>
			<a class="button is-small is-danger" href="javascript:void(0);" onclick="if (confirm('{{ __('app.foraging_confirm_delete') }}')) { document.getElementById('foraging-remove-location-form-{{ $loc->get('id') }}').submit(); }">{{ __('app.foraging_remove') }}</a>
		</div>
		<form id="foraging-remove-location-form-{{ $loc->get('id') }}" method="POST" action="{{ url('/foraging/location/remove') }}" class="is-hidden">
			@csrf
			<input type="hidden" name="id" value="{{ $loc->get('id') }}">
			<input type="hidden" name="category_id" value="{{ $category->get('id') }}">
		</form>
		@if ($loc->get('name'))
		<strong>{{ $loc->get('name') }}</strong>
		<p class="has-text-grey">{{ $loc->get('latitude') }}, {{ $loc->get('longitude') }}</p>
		@else
		<strong>{{ $loc->get('latitude') }}, {{ $loc->get('longitude') }}</strong>
		@endif
		@if ($loc->get('last_visited'))
		<p>{{ __('app.foraging_last_visited') }}: {{ $loc->get('last_visited') }}</p>
		@endif
		@if ($loc->get('notes'))
		<p>{{ $loc->get('notes') }}</p>
		@endif
		<p class="margin-top"><strong>{{ __('app.foraging_history') }}</strong> <a class="button is-small" href="javascript:void(0);" onclick="document.getElementById('foraging-add-log-form-{{ $loc->get('id') }}').classList.toggle('is-hidden');">{{ __('app.foraging_add_log_entry') }}</a></p>
		@if ((isset($logs[$loc->get('id')])) && (is_countable($logs[$loc->get('id')])) && (count($logs[$loc->get('id')]) > 0))
		<ul class="foraging-log-list">
			@foreach ($logs[$loc->get('id')] as $log)
			<li>
				<form method="POST" action="{{ url('/foraging/location/log/remove') }}" class="is-pulled-right">
					@csrf
					<input type="hidden" name="id" value="{{ $log->get('id') }}">
					<input type="hidden" name="category_id" value="{{ $category->get('id') }}">
					<button type="submit" class="button is-small is-danger" onclick="return confirm('{{ __('app.foraging_confirm_delete') }}');">{{ __('app.foraging_remove') }}</button>
				</form>
				<strong>{{ $log->get('entry_date') }}</strong> &mdash; {{ $log->get('note') }}
			</li>
			@endforeach
		</ul>
		@else
		<p class="has-text-grey">{{ __('app.foraging_no_log_entries') }}</p>
		@endif
		<div id="foraging-add-log-form-{{ $loc->get('id') }}" class="box is-hidden">
			<form method="POST" action="{{ url('/foraging/location/log/create') }}">
				@csrf
				<input type="hidden" name="location_id" value="{{ $loc->get('id') }}">
				<input type="hidden" name="category_id" value="{{ $category->get('id') }}">
				<div class="field">
					<label class="label">{{ __('app.foraging_log_date') }}</label>
					<div class="control">
						<input type="date" class="input" name="entry_date" required>
					</div>
				</div>
				<div class="field">
					<label class="label">{{ __('app.foraging_log_note') }}</label>
					<div class="control">
						<textarea class="textarea" name="note" required></textarea>
					</div>
				</div>
				<div class="field">
					<div class="control">
						<button type="submit" class="button is-success">{{ __('app.foraging_save') }}</button>
						<a class="button" href="javascript:void(0);" onclick="document.getElementById('foraging-add-log-form-{{ $loc->get('id') }}').classList.add('is-hidden');">{{ __('app.foraging_cancel') }}</a>
					</div>
				</div>
			</form>
		</div>
		<div id="foraging-edit-location-form-{{ $loc->get('id') }}" class="box is-hidden">
			<form method="POST" action="{{ url('/foraging/location/edit') }}">
				@csrf
				<input type="hidden" name="id" value="{{ $loc->get('id') }}">
				<input type="hidden" name="category_id" value="{{ $category->get('id') }}">
				<input type="hidden" name="latitude" value="{{ $loc->get('latitude') }}">
				<input type="hidden" name="longitude" value="{{ $loc->get('longitude') }}">
				<div class="field">
					<label class="label">{{ __('app.foraging_location_name') }}</label>
					<div class="control">
						<input type="text" class="input" name="name" value="{{ $loc->get('name') }}">
					</div>
				</div>
				<div class="field">
					<label class="label">{{ __('app.foraging_last_visited') }}</label>
					<div class="control">
						<input type="date" class="input" name="last_visited" value="{{ $loc->get('last_visited') }}">
					</div>
				</div>
				<div class="field">
					<label class="label">{{ __('app.foraging_notes') }}</label>
					<div class="control">
						<textarea class="textarea" name="notes">{{ $loc->get('notes') }}</textarea>
					</div>
				</div>
				<div class="field">
					<div class="control">
						<button type="submit" class="button is-success">{{ __('app.foraging_save') }}</button>
						<a class="button" href="javascript:void(0);" onclick="document.getElementById('foraging-edit-location-form-{{ $loc->get('id') }}').classList.add('is-hidden');">{{ __('app.foraging_cancel') }}</a>
					</div>
				</div>
			</form>
		</div>
	</div>
	@endforeach
@else
	<p>{{ __('app.foraging_no_locations') }}</p>
@endif

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
(function() {
	var locations = [];
	@foreach ($locations as $loc)
	locations.push({ id: {{ $loc->get('id') }}, lat: {{ $loc->get('latitude') }}, lng: {{ $loc->get('longitude') }} });
	@endforeach

	function initForagingMap() {
		var container = document.getElementById('foraging-map');
		if (!container || container._leaflet_id) {
			return;
		}

		var startLat = locations.length ? locations[0].lat : 54.2833;
		var startLng = locations.length ? locations[0].lng : -0.4;
		var map = L.map(container).setView([startLat, startLng], locations.length ? 13 : 6);
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; OpenStreetMap contributors',
			maxZoom: 19
		}).addTo(map);

		locations.forEach(function(loc) {
			L.marker([loc.lat, loc.lng]).addTo(map);
		});

		map.on('click', function(e) {
			document.getElementById('foraging-new-lat').value = e.latlng.lat.toFixed(7);
			document.getElementById('foraging-new-lng').value = e.latlng.lng.toFixed(7);
			document.getElementById('foraging-add-location-form').classList.remove('is-hidden');
		});

		map.invalidateSize();
	}

	function waitForLeafletThenInit() {
		if (window.L) {
			initForagingMap();
		} else {
			setTimeout(waitForLeafletThenInit, 100);
		}
	}

	/* Vue mounts using the current DOM as its template and then replaces it wholesale,
	   which destroys any map initialized before that replacement happens even though
	   the replacement looks visually identical. Deferring past that replacement (which
	   is synchronous and fast) lets us attach to the DOM node that actually persists. */
	setTimeout(waitForLeafletThenInit, 300);
})();
</script>
