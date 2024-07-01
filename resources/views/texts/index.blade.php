@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel" class="with-feature">
        <h1>{{ __('texts') }}</h1>
        <div class="hero-blurb">follow along with these videos & practice your listening!</div>
    </div>
@endsection

@section('content')

    <div class="portal-grid">
        <x-portal-item subtitle="text" maintitle="Coronavirus!" :link="route('texts.show', '01')"
                       blurb="The Abu Sanad family offers some advice & talk about how they're going to stay safe & healthy during the COVID-19 pandemic."
                       img="https://upload.wikimedia.org/wikipedia/commons/f/f6/Coronavirus_SARS-CoV-2.jpg"
        />
        <x-portal-item subtitle="text" maintitle="Who’s the Boy?" :link="route('texts.show', '02')"
                       blurb="When Rana mentions someone named Jimmy & is delivered a bouquet of flowers, her mother jumps to conclusions."
                       img="https://upload.wikimedia.org/wikipedia/commons/5/5d/Flower_Bouquet-2.jpg"
        />
        <x-portal-item subtitle="text" maintitle="Stranger Danger" :link="route('texts.show', '03')"
                       blurb="Sanad learns about stranger danger the hard way after Sally saves him from getting kidnapped."
                       img="https://upload.wikimedia.org/wikipedia/commons/2/2e/1971_Ford_Capri_1600_GT_%289995427183%29.jpg"
        />
        <x-portal-item subtitle="text" maintitle="Not Like Us" :link="route('texts.show', '04')"
                       blurb="Some of the parents in the neighborhood aren't too pleased that their children are playing with the new kids from a different part of town."
                       img="https://upload.wikimedia.org/wikipedia/commons/thumb/4/4c/The_opulent_breakfast_room_of_the_McFaddin-Ward_House_in_Beaumont%2C_Texas%2C_now_a_house_museum%2C_was_built_in_1905-1906_in_the_striking_and_distinctive_Beaux-Arts_Colonial_style_LCCN2014631199.tif/lossy-page1-1280px-thumbnail.tif.jpg"
        />
        <x-portal-item subtitle="text" maintitle="Lazy Sanad" :link="route('texts.show', '05')"
                       blurb="Sanad's parents get involved when his grades start to slip, but his father trusts that he can catch up."
                       img="https://upload.wikimedia.org/wikipedia/commons/b/b7/Sloth_Climbing_in_Front_of_Another_%2817915910338%29.jpg"
        />
        <x-portal-item subtitle="text" maintitle="the Big Game" :link="route('texts.show', '06')"
                       blurb="Everyone is late to the game — literally! But not everyone in town is a responsible driver, especially not when they're in a rush!"
                       img="https://upload.wikimedia.org/wikipedia/commons/1/13/Boys_playing_street_football_in_Egypt.jpg"
        />
        <x-portal-item subtitle="text" maintitle="True Friends" :link="route('texts.show', '07')"
                       blurb="Not just anyone should be your friend. Sally & Sanad learn to stay true to themselves despite the bad influences around them."
                       img="https://upload.wikimedia.org/wikipedia/commons/b/b2/Two_little_Lao_girls_hugging.jpg"
        />
        <x-portal-item subtitle="text" maintitle="Thief!" :link="route('texts.show', '08')"
                       blurb="Is someone stealing from the Abu Sanad family? Abu Sanad sets out to investigate what's really going on."
                       img="https://upload.wikimedia.org/wikipedia/commons/9/9a/Balaclava_3_hole_black.jpg"
        />
    </div>

@endsection
