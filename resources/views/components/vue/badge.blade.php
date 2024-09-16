@if($badge)
    @php
        $badgeObject = [
            'name' => $badge->name,
            'description' => $badge->description,
            'image' => asset('img/badges/'.$badge->image),
            'enabled' => $user->badges->contains($badge->id),
        ];
    @endphp

    <div data-vue-component="BadgeItem"
         data-props="{{ json_encode([
                 'badge' => $badgeObject,
             ]) }}"
    >
    </div>
@endif
