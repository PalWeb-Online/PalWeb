<script setup>
import {route} from "ziggy-js";
</script>
<template>
    <div class="footer-container">
        <div class="footer-social">
            <a href="https://github.com/PalWeb-Online" target="_blank">
                <img src="/img/github.svg" alt="GitHub"/>
            </a>
            <a href="https://www.youtube.com/@PalWebTV" target="_blank">
                <img src="/img/youtube.svg" alt="YouTube"/>
            </a>
            <a href="https://twitter.com/palweb_app" target="_blank">
                <img src="/img/twitter.svg" alt="Twitter"/>
            </a>
            <a href="https://www.instagram.com/palweb.app" target="_blank">
                <img src="/img/instagram.svg" alt="Instagram"/>
            </a>
            <a href="https://discord.gg/3Wf7Q6RCjV" target="_blank">
                <img src="/img/discord.svg" alt="Discord"/>
            </a>
        </div>

        <div class="footer-body">
            <div class="footer-hero">
                <div class="footer-hero-title">PalWeb!</div>
                <div class="footer-hero-subtitle">PalWeb, the Web of Palestinian Arabic, is an open-source
                    language-learning
                    web app providing database-powered Palestinian Arabic learning tools.
                </div>
            </div>

            <div class="footer-links-wrapper">
                <div class="footer-links">
                    <div class="footer-links-title">About</div>
                    <Link :href="route('wiki.show', 'faq')">FAQ</Link>
                    <Link :href="route('wiki.show', 'about')">PalWeb</Link>
                    <Link :href="route('wiki.show', 'ajp')">Spoken Arabic</Link>
                </div>
                <div class="footer-links">
                    <div class="footer-links-title">Donate</div>
                    <Link :href="route('subscription.index')">Subscribe</Link>
                    <a href="https://www.patreon.com/palweb" target="_blank">Patreon</a>
                    <a href="https://www.ko-fi.com/palweb" target="_blank">Ko-fi</a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">by R. Adrian · adrian@abdulbaha.xyz</div>
    </div>
</template>
