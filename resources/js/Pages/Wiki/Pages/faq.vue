<script setup>
import {route} from "ziggy-js";
import Wiki from "../Wiki.vue";
import Layout from "../../../Shared/Layout.vue";
import AppTip from "../../../components/AppTip.vue";

defineOptions({
    layout: [Layout, Wiki],
})
</script>

<template>
    <div class="wiki-content-block">
        <AppTip question="Do you give private lessons?">
            <p>No, sorry.</p>
        </AppTip>

        <AppTip question="Do I need to pay to access the content on the site?">
            <p>Only for the <b>Academy</b> section & features related to Quizzes & Scores. Some other areas & features
                of the site require you to at least create a free account. Refer to the
                <Link :href="route('subscription.index')">Subscription</Link>
                page for more information about access privileges.
            </p>
        </AppTip>

        <AppTip question="How can I preview the paid content to know if I want to pay for that?">
            <p>Unfortunately, there is no free trial at this time. However, you can follow PalWeb on social media to see
                more discussion of the Academy features. On
                <a href="https://www.youtube.com/@PalWebTV" target="_blank">YouTube</a>,
                for instance, I have posted walkthrough videos of many of the Dialogs.</p>
        </AppTip>

        <AppTip question="I'm subscribed, but some of the paid content is not available yet. When will it be available?">
            <p>Please refer to the
                <Link :href="route('wiki.show', 'release-notes')">Release Notes</Link>
                for a timeline of upcoming updates.
            </p>
        </AppTip>

        <AppTip question="I've subscribed on Patreon. Why can't I access the content on PalWeb?">
            <p>In order to gain full access to the content on PalWeb, you need to get a subscription to PalWeb — not to
                Patreon.
                I cannot refund money paid to Patreon, so you will need to find a way to cancel the charge with your
                bank.</p>
        </AppTip>

        <AppTip question="Why do you still have a Patreon then?">
            <p>There's an archive of blog posts & resources I've uploaded over the years that aren't available
                anywhere else. But mainly it's because some folks are generous & want to support me in other ways. If
                you're
                one of them, you can also donate to my <a href="https://ko-fi.com/a_abdulbaha" target="_blank">Ko-fi</a>.
            </p>
        </AppTip>
    </div>
</template>

