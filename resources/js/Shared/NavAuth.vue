<script setup>
import {useUserStore} from "../stores/UserStore.js";
import UserNametag from "../components/UserNametag.vue";
import {useNotifier} from "../composables/useNotifier.js";

const UserStore = useUserStore();
const Notifier = useNotifier();

const sendVerificationLink = async () => {
    try {
        const { data } = await axios.post('/api/email/verification/link');

        switch (data.outcome) {
            case 'sent':
                Notifier.notify({
                    key: 'components.nav-auth.notifications.link-sent',
                });
                break;

            case 'already_verified':
                Notifier.notify({
                    key: 'components.nav-auth.notifications.already-verified',
                    type: 'info',
                });
                break;

            default:
                Notifier.notify({
                    key: 'components.nav-auth.notifications.link-sent-error',
                    type: 'error',
                });
        }

    } catch (error) {
        Notifier.notify({
            key: 'components.nav-auth.notifications.link-sent-error',
            type: 'error',
        });
    }
};
</script>

<template>
    <div class="auth-user">
        <RouterLink class="user-avatar" :to="{ name: 'users.show', params: { user: UserStore.user.username } }">
            <img :alt="$t('nav.auth.user-avatar')"
                 :src="UserStore.user.avatar_url"/>
        </RouterLink>

        <UserNametag :user="UserStore.user"/>
    </div>
    <div class="auth-email">
        <div>{{ UserStore.user.email }}</div>
        <span v-if="UserStore.user.is_verified" class="material-symbols-rounded">
                verified
            </span>
        <template v-else>
            <span class="material-symbols-rounded unverified">
                verified_off
            </span>
            <span class="resend-prompt"
                  @click="sendVerificationLink()">
                {{ $t('nav.auth.resend-link') }}
            </span>
        </template>
    </div>
</template>

<style scoped lang="scss">
.auth-user {
    display: grid;
    grid-template-columns: 9.6rem 1fr;
    background: white;

    .user-avatar {
        margin: 0;
        border-radius: 0 0 1.6rem 0;
        overflow: hidden;

        img {
            border-radius: 0;
            padding: 0;
        }
    }

    .user-name {
        align-self: end;
        font-size: 1.6rem;
        background: white;
        padding: 1.6rem;
        z-index: 1;
    }
}

.auth-email {
    background: var(--color-pastel-light);
    border-block-end: 0.8rem solid var(--color-pastel-medium);
    padding: 0.8rem 1.2rem;
    display: flex;
    flex-flow: row wrap;
    align-items: center;
    gap: 0.8rem;
    font-weight: 700;
    color: var(--color-dark-primary);

    & > *:nth-child(1) {
        font-family: var(--mono-font);
        font-size: 1.4rem;
    }

    .material-symbols-rounded {
        font-size: 1.8rem;
        font-weight: 400;
        color: var(--color-medium-primary);

        &.unverified {
            color: var(--color-accent-medium);
        }
    }

    .resend-prompt {
        display: flex;
        justify-content: space-between;
        font-family: var(--mono-font);
        font-size: 1.4rem;
        color: var(--color-medium-primary);
        cursor: pointer;

        &:hover {
            text-decoration: 0.2rem solid underline;
        }
    }
}
</style>
