<script setup>
import {route} from "ziggy-js";
import AppLogo from "./AppLogo.vue";
</script>
<template>
    <div class="footer-container">
        <div class="footer-body">
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

            <div class="footer-logo-wrapper">
                <AppLogo/>
                <div>the Web of Palestinian Arabic!</div>
            </div>
            <div class="footer-links-wrapper">
                <div class="footer-links">
                    <div>About</div>
                    <Link :href="route('wiki.show', 'faq')">FAQ</Link>
                    <Link :href="route('wiki.show', 'about')">PalWeb</Link>
                    <Link :href="route('wiki.show', 'ajp')">Spoken Arabic</Link>
                </div>
                <div class="footer-links">
                    <div>Donate</div>
                    <Link :href="route('subscription.index')">Subscribe</Link>
                    <a href="https://www.patreon.com/palweb" target="_blank">Patreon</a>
                    <a href="https://www.ko-fi.com/palweb" target="_blank">Ko-fi</a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">by R. Adrian · adrian@palweb.app</div>
    </div>
</template>

<style scoped lang="scss">
.footer-container {
    align-self: end;
    display: grid;
    gap: 3.2rem;
    font-family: var(--head-font);
    color: white;
    font-size: 1.6rem;
    background: var(--color-medium-secondary);
    position: relative;

    .footer-body {
        display: grid;
        justify-items: center;
        padding-inline: min(4vw, 3.6rem);
        padding-block-start: 3.2rem;
    }

    .footer-social {
        display: flex;
        gap: 0.8rem;
        transition: transform 0.1s;

        img {
            width: 4.8rem;
            transition: 0.1s;

            &:hover {
                transform: rotate(-10deg);
            }
        }
    }

    .footer-logo-wrapper {
        width: min(80%, 48rem);
        display: grid;
        grid-template-areas: 'overlap';
        align-items: end;
        justify-items: end;
        padding-block: 3.2rem 4.8rem;

        & > * {
            grid-area: overlap;
        }

        div {
            color: var(--color-accent-medium);
            font-family: var(--head-font);
            font-size: min(3.3vw, 2.2rem);
            font-weight: 700;
            margin-inline-start: 15%;
            line-height: 1.2;
        }
    }

    .footer-links-wrapper {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        padding-block-end: 1.6rem;
        width: min(64rem, 100%);

        .footer-links {
            display: grid;
            align-content: start;
            justify-content: center;
            justify-items: center;
            gap: 0.8rem;

            div {
                color: var(--color-accent-medium);
                font-family: var(--display-font);
                font-size: 3.2rem;
                text-transform: uppercase;
                //padding-block-end: 1.2rem;
            }

            a {
                font-size: 1.4rem;
                font-family: var(--mono-font)
            }

            a:hover {
                text-decoration: 0.1rem underline;
            }
        }
    }

    .footer-bottom {
        font-family: var(--mono-font);
        font-size: 1.2rem;
        font-weight: 700;
        text-align: center;
        padding-block: 0.8rem;
        background: var(--color-dark-primary);
    }

    @media print {
        display: none;
    }

    @media (width >= 960px) {
        .footer-body {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 6.4rem;
            padding-block-start: 0;
        }

        .footer-social {
            position: absolute;
            top: -2.4rem;
            right: 4.8rem;

            img {
                width: 4.8rem;
            }
        }

        .footer-logo-wrapper {
            width: min(48rem, 40%);
            margin-block-start: -3.2rem;
            padding-block: 0;

            div {
                font-size: min(2.2rem, 1.7vw);
            }
        }

        .footer-links-wrapper {
            flex-basis: 50%;
            flex-grow: 0;
            margin-block-start: 6.4rem;

            .footer-links {
                justify-content: start;
                justify-items: start;
            }
        }
    }
}
</style>
