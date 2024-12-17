<form class="search-tools" method="GET" action="{{ route('audios.index') }}">
    <div class="search-filters">
        <div class="filter-container">
            <div class="filter-name">{{ __('dialect') }}</div>
            <select name="dialect" onchange="this.form.submit()">
                <option value=""></option>
                @foreach ($dialects as $dialect)
                    <option value="{{ $dialect->id }}" {{ request('dialect') == $dialect->id ? 'selected' : '' }}>
                        {{ $dialect->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-container">
            <div class="filter-name">{{ __('location') }}</div>
            <select name="location" onchange="this.form.submit()">
                <option value=""></option>
                @foreach ($locations as $location)
                    <option
                        value="{{ $location->id }}" {{ request('location') == $location->id ? 'selected' : '' }}>
                        {{ $location->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-container">
            <div class="filter-name">{{ __('gender') }}</div>
            <select name="gender" onchange="this.form.submit()">
                <option value=""></option>
                <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                <option
                    value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
            </select>
        </div>

        <div class="filter-container">
            <div class="filter-name">{{ __('sort') }}</div>
            <select name="sort" onchange="this.form.submit()">
                <option
                    value="latest" {{ $currentSort === 'latest' ? 'selected' : '' }}>{{ __('by Latest') }}</option>
                <option
                    value="fluency" {{ $currentSort === 'fluency' ? 'selected' : '' }}>{{ __('by Fluency') }}</option>
            </select>
        </div>
    </div>
</form>
