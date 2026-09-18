<h1>{{ __('app.foraging') }}</h1>

@include('flashmsg.php')

<div class="margin-vertical">
        <div class="action-strip action-strip-left">
                <div class="is-inline-block is-action-button-margin"><a class="button is-success" href="javascript:void(0);" onclick="document.getElementById('foraging-add-category-form').classList.toggle('is-hidden');">{{ __('app.foraging_add_category') }}</a></div>
        </div>
</div>

<div id="foraging-add-category-form" class="box is-hidden">
        <form method="POST" action="{{ url('/foraging/create') }}">
                @csrf
                <div class="field">
                        <label class="label">{{ __('app.foraging_category_name') }}</label>
                        <div class="control">
                                <input type="text" class="input" name="name" required>
                        </div>
                </div>
                <div class="field">
                        <label class="label">{{ __('app.foraging_grocy_name') }}</label>
                        <div class="control">
                                <input type="text" class="input" name="grocy_name">
                        </div>
                </div>
                <div class="field">
                        <label class="label">{{ __('app.foraging_filter_type') }}</label>
                        <div class="control">
                                <div class="select">
                                        <select name="type">
                                                <option value="">{{ __('app.foraging_not_set') }}</option>
                                                @foreach (ForageCategoryModel::getTypes() as $key => $label)
                                                <option value="{{ $key }}">{{ $label }}</option>
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
                                                <option value="{{ $key }}">{{ $label }}</option>
                                                @endforeach
                                        </select>
                                </div>
                        </div>
                </div>
                <div class="field">
                        <label class="label">{{ __('app.foraging_season') }}</label>
                        <div class="control is-flex">
                                <div class="select">
                                        <select name="season_start_month">
                                                <option value="">{{ __('app.foraging_not_set') }}</option>
                                                @foreach ([1,2,3,4,5,6,7,8,9,10,11,12] as $num)
                                                <option value="{{ $num }}">{{ ForageCategoryModel::monthName($num) }}</option>
                                                @endforeach
                                        </select>
                                </div>
                                &nbsp;{{ __('app.foraging_to') }}&nbsp;
                                <div class="select">
                                        <select name="season_end_month">
                                                <option value="">{{ __('app.foraging_not_set') }}</option>
                                                @foreach ([1,2,3,4,5,6,7,8,9,10,11,12] as $num)
                                                <option value="{{ $num }}">{{ ForageCategoryModel::monthName($num) }}</option>
                                                @endforeach
                                        </select>
                                </div>
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
                                <button type="submit" class="button is-success">{{ __('app.foraging_add_category') }}</button>
                        </div>
                </div>
        </form>
</div>

<div class="box">
        <form method="GET" action="{{ url('/foraging') }}">
                <div class="field is-grouped" style="flex-wrap: wrap; gap: 12px;">
                        <div class="control" style="width: 100%; max-width: 280px;">
                                <label class="label">{{ __('app.foraging_filter_type') }}</label>
                                <div class="select" style="width: 100%;">
                                        <select name="type" onchange="this.form.submit()" style="width: 100%;">
                                                <option value="">{{ __('app.foraging_filter_all_types') }}</option>
                                                @foreach (ForageCategoryModel::getTypes() as $key => $label)
                                                <option value="{{ $key }}" {{ (($selected_type ?? '') === $key) ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                        </select>
                                </div>
                        </div>
                        <div class="control" style="width: 100%; max-width: 280px;">
                                <label class="label">{{ __('app.foraging_filter_habitat') }}</label>
                                <div class="select" style="width: 100%;">
                                        <select name="habitat" onchange="this.form.submit()" style="width: 100%;">
                                                <option value="">{{ __('app.foraging_filter_all_habitats') }}</option>
                                                @foreach (ForageCategoryModel::getHabitats() as $key => $label)
                                                <option value="{{ $key }}" {{ (($selected_habitat ?? '') === $key) ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                        </select>
                                </div>
                        </div>
                        <div class="control" style="width: 100%; max-width: 280px;">
                                <label class="label">{{ __('app.foraging_filter_season') }}</label>
                                <div class="select" style="width: 100%;">
                                        <select name="season" onchange="this.form.submit()" style="width: 100%;">
                                                <option value="current" {{ (($selected_season ?? 'current') === 'current') ? 'selected' : '' }}>{{ __('app.foraging_season_current') }}</option>
                                                <option value="all" {{ (($selected_season ?? '') === 'all') ? 'selected' : '' }}>{{ __('app.foraging_season_all') }}</option>
                                                @foreach ([1,2,3,4,5,6,7,8,9,10,11,12] as $num)
                                                <option value="{{ $num }}" {{ (($selected_season ?? '') == $num) ? 'selected' : '' }}>{{ ForageCategoryModel::monthName($num) }}</option>
                                                @endforeach
                                        </select>
                                </div>
                        </div>
                </div>
        </form>
</div>

@if ((isset($categories)) && (is_countable($categories)) && (count($categories) > 0))
        @foreach ($categories as $cat)
        <div class="box is-flex">
                @if ($cat->get('photo'))
                <div class="is-flex-shrink-0" style="margin-right: 12px;">
                        <img src="{{ abs_photo($cat->get('photo')) }}" style="width:56px;height:56px;object-fit:cover;border-radius:4px;">
                </div>
                @endif
                <div class="is-flex-grow-1">
                        <a href="{{ url('/foraging/' . $cat->get('id')) }}"><strong>{{ $cat->get('name') }}</strong></a>
                        @if ($cat->get('harvested'))
                        <span class="tag is-success">{{ __('app.foraging_harvested_yes') }}</span>
                        @else
                        <span class="tag is-light">{{ __('app.foraging_harvested_no') }}</span>
                        @endif
                        @if ($cat->get('type'))
                        <span class="tag is-info">{{ ForageCategoryModel::typeName($cat->get('type')) }}</span>
                        @endif
                        @if ($cat->get('habitat'))
                        <span class="tag is-link">{{ ForageCategoryModel::habitatName($cat->get('habitat')) }}</span>
                        @endif
                        @if (($cat->get('season_start_month')) && ($cat->get('season_end_month')))
                        <span class="is-pulled-right">{{ __('app.foraging_season') }}: {{ ForageCategoryModel::monthName($cat->get('season_start_month')) }} &ndash; {{ ForageCategoryModel::monthName($cat->get('season_end_month')) }}</span>
                        @endif
                </div>
        </div>
        @endforeach
@else
        <p>{{ __('app.foraging_no_categories') }}</p>
@endif
