import {ref} from 'vue';
import {route} from 'ziggy-js';
import {useResourceLoader} from '../resources/useResourceLoader.js';

export function useUserLoader() {
    const decks = ref({
        data: [],
        meta: { links: [] },
    });
    const badges = ref([]);
    const speaker = ref(null);
    const filters = ref({ sort: 'latest' });
    
    const {
        model: user,
        notFound: userNotFound,
        isLoading: isLoadingUser,
        setModel: setUser,
        fetchModel: fetchUser,
    } = useResourceLoader({
        getUrl: (userIdentifier, options = {}) => route(options.routeName, {
            user: userIdentifier,
            ...(options.params ?? {}),
        }),
        extractModel: (response) => response.data.user ?? null,
        extractMeta: (response) => ({
            decks: response.data.decks ?? {
                data: [],
                meta: { links: [] },
            },
            badges: response.data.badges ?? [],
            speaker: response.data.speaker ?? null,
            filters: response.data.filters ?? { sort: 'latest' },
        }),
        onSetModel: (meta = {}) => {
            decks.value = meta.decks ?? {
                data: [],
                meta: { links: [] },
            };
            badges.value = meta.badges ?? [];
            speaker.value = meta.speaker ?? null;
            filters.value = meta.filters ?? { sort: 'latest' };
        },
    });
    
    const fetchShowUser = (userUsername, options = {}) => fetchUser(userUsername, {
        ...options,
        routeName: 'api.users.show',
    });
    
    const fetchEditUser = (userId, options = {}) => fetchUser(userId, {
        ...options,
        routeName: 'api.users.edit',
    });
    
    return {
        user,
        decks,
        badges,
        speaker,
        filters,
        userNotFound,
        isLoadingUser,
        setUser,
        fetchUser,
        fetchShowUser,
        fetchEditUser,
    };
}
