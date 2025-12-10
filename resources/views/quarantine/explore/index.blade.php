@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel" class="with-feature">
        <h1>{{ __('explore') }}</h1>
        <div class="hero-blurb">Dive into topics & power up your Arabic vocabulary!</div>
    </div>
@endsection

@section('content')

    <div class="portal-grid">
        <x-portal-item subtitle="explore" maintitle="Health" :link="route('explore.show', 'health')"
                       blurb="Don't get tongue-tied at your next visit to the doctor. Learn essential medical terms to stay safe & healthy no matter where you are."
                       img="https://upload.wikimedia.org/wikipedia/commons/a/ab/Physical_examination.jpg"
        />
        <x-portal-item subtitle="explore" maintitle="education" :link="route('explore.show', 'education')"
                       blurb="Navigate the academic world with ease! Discover essential vocabulary for school & university so you can talk about all your favorite subjects."
                       img="https://upload.wikimedia.org/wikipedia/commons/2/26/Andrew_Classroom_De_La_Salle_University.jpeg"
        />
        <x-portal-item subtitle="explore" maintitle="language" :link="route('explore.show', 'language')"
                       blurb="Unlock the literary treasures of language! Delve into a realm of words, exploring expressions that shape stories and culture."
                       img="https://upload.wikimedia.org/wikipedia/commons/3/37/Delphine%2C_Madame_de_Sta%C3%ABl%2C_Paris%2C_1803_04.jpg"
        />
        <x-portal-item subtitle="explore" maintitle="animals" :link="route('explore.show', 'animals')"
                       blurb="Get to know the animal kingdom in Arabic, from the birds & the bees to familiar pets, farm animals & exotic creatures!"
                       img="https://upload.wikimedia.org/wikipedia/commons/7/71/2010-kodiak-bear-1.jpg"
        />
        <x-portal-item subtitle="explore" maintitle="earth" :link="route('explore.show', 'earth')"
                       blurb="Learn about the ecosystems & natural features that make life unique on this pale blue dot — then reach for the stars."
                       img="https://upload.wikimedia.org/wikipedia/commons/0/0d/Africa_and_Europe_from_a_Million_Miles_Away.png"
        />
        <x-portal-item subtitle="explore" maintitle="plants" :link="route('explore.show', 'plants')"
                       blurb="Get ready for the farmer's market or a hike through the Palestinian hills by leafing through words for common trees, herbs, fruits & vegetables."
                       img="https://upload.wikimedia.org/wikipedia/commons/4/47/Jungle.jpg"
        />
        <x-portal-item subtitle="explore" maintitle="food" :link="route('explore.show', 'food')"
                       blurb="Whet your appetite for language! Explore tantalizing terms for food and drink, spicing up your culinary conversations."
                       img="https://upload.wikimedia.org/wikipedia/commons/b/b8/Pr%C3%A9paration_du_mansaf-Jordanie_%288%29.jpg"
        />
    </div>
@endsection
