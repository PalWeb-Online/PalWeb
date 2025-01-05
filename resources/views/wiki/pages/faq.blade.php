<x-page-head title="{{ __('FAQ') }}">
    <x-link :href="route('wiki.index')">{{ __('wiki') }}</x-link>
    <x-link :href="route('wiki.show', 'faq')">{{ __('FAQ') }}</x-link>
</x-page-head>

<x-faq question="Do you give private lessons?">
    <p>No, sorry.</p>
</x-faq>

<x-faq question="Do I need to pay to access the content on the site?">
    <p>Some areas of the site are locked to users with a free account or to subscribed users. Refer to the <a
            href="{{ route('dashboard.subscription') }}">Subscription</a> Portal for more details on the perks of
        each
        tier.</p>
</x-faq>

<x-faq question="How can I preview the content of the Academy section to know if I want to pay for that?">
    <p>I give a general tour of the site in the <a href="https://www.youtube.com/watch?v=lJGDKswxg4w" target="_blank">May
            Day 2023 Launch</a> video. Note that the site has changed significantly in the time since, so I will be
        creating another tour in the near future. Unfortunately, there is no free trial at this time.</p>
</x-faq>

<x-faq question="I'm subscribed, but some of the content says Coming Soon. Define Soon.">
    <p>Please refer to the <b>Release Notes</b> on the front page for a timeline of upcoming updates.</p>
</x-faq>

<x-faq question="I've subscribed on Patreon. Why can't I access the content on PalWeb?">
    <p>In order to gain full access to the content on PalWeb, you need to get a subscription to PalWeb — not to Patreon.
        I cannot refund money paid to Patreon, so you will need to find a way to cancel the charge with your bank.</p>
</x-faq>

<x-faq question="Why do you still have a Patreon then?">
    <p>Well, there's an archive of blog posts & resources I've uploaded over the years that aren't available
        anywhere else. But mainly it's because some folks are generous & want to support me in other ways. If you're
        one of them, you can also donate to my <a href="https://ko-fi.com/a_abdulbaha" target="_blank">Ko-fi</a>.
    </p>
</x-faq>
