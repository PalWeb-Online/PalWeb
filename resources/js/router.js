import {createRouter, createWebHistory, RouterView} from 'vue-router';
import {useUserStore} from './stores/UserStore.js';
import {useNavigationStore} from './stores/NavigationStore.js';

// Academy
const UnitsIndex = () => import('./Pages/Academy/Units/Index.vue');
const UnitShow = () => import('./Pages/Academy/Units/Show.vue');
const LessonShow = () => import('./Pages/Academy/Lessons/Show.vue');
const ActivityShow = () => import('./Pages/Academy/Activities/Show.vue');
const ActivitySession = () => import('./Pages/Academy/Activities/Activity.vue');
const DialogsIndex = () => import('./Pages/Academy/Dialogs/Index.vue');
const DialogShow = () => import('./Pages/Academy/Dialogs/Show.vue');
const ScoresIndex = () => import('./Pages/Academy/Scores/Index.vue');
const ScoresHistory = () => import('./Pages/Academy/Scores/History.vue');

// Library
const TermsIndex = () => import('./Pages/Library/Terms/Index.vue');
const TermShow = () => import('./Pages/Library/Terms/Show.vue');
const TermRoot = () => import('./Pages/Library/Terms/Root.vue');
const SentencesIndex = () => import('./Pages/Library/Sentences/Index.vue');
const SentenceShow = () => import('./Pages/Library/Sentences/Show.vue');
const DecksIndex = () => import('./Pages/Library/Decks/Index.vue');
const DeckShow = () => import('./Pages/Library/Decks/Show.vue');
const AudiosIndex = () => import('./Pages/Library/Audios/Index.vue');
const SpeakerShow = () => import('./Pages/Library/Audios/Speaker.vue');

// Hub
const UsersIndex = () => import('./Pages/Community/Users/Index.vue');
const UserShow = () => import('./Pages/Community/Users/Show.vue');
const UserEdit = () => import('./Pages/Community/Users/Edit.vue');

// Workbench
const DeckMasterIndex = () => import('./Pages/Workbench/DeckMaster/Index.vue');
const DeckMasterBuild = () => import('./Pages/Workbench/DeckMaster/Build.vue');
const DeckMasterStudy = () => import('./Pages/Workbench/DeckMaster/Study.vue');
const CardDealerIndex = () => import('./Pages/Workbench/CardDealer/Index.vue');
const CardDealerCards = () => import('./Pages/Workbench/CardDealer/Cards.vue');
const CardDealerReview = () => import('./Pages/Workbench/CardDealer/Review.vue');
const SoundBooth = () => import('./Pages/Workbench/SoundBooth/SoundBooth.vue');

// Office
const WordLoggerIndex = () => import('./Pages/Office/WordLogger/Index.vue');
const WordLoggerTerm = () => import('./Pages/Office/WordLogger/Term.vue');
const SpeechMaker = () => import('./Pages/Office/SpeechMaker/Index.vue');
const SpeechMakerDialog = () => import('./Pages/Office/SpeechMaker/Dialog.vue');
const SpeechMakerSentence = () => import('./Pages/Office/SpeechMaker/Sentence.vue');
const LessonPlannerCourse = () => import('./Pages/Office/LessonPlanner/Course.vue');
const LessonPlannerUnit = () => import('./Pages/Office/LessonPlanner/Unit.vue');
const LessonPlannerDecks = () => import('./Pages/Office/LessonPlanner/Decks.vue');
const LessonPlannerLesson = () => import('./Pages/Office/LessonPlanner/Lesson.vue');
const LessonPlannerActivity = () => import('./Pages/Office/LessonPlanner/Activity.vue');
const ViewFeedback = () => import('./Pages/Office/ViewFeedback.vue');

// Wiki
const WikiShow = () => import('./Pages/Wiki/Show.vue');
const WikiEdit = () => import('./Pages/Wiki/Edit.vue');

// Auth & General
const Home = () => import('./Pages/Home.vue');
const ComingSoon = () => import('./Pages/ComingSoon.vue');
const Subscription = () => import('./Pages/Auth/Subscription.vue');
const NewPassword = () => import('./Pages/Auth/NewPassword.vue');
const ErrorPage = () => import('./Pages/Error.vue');
const ThemeTesting = () => import('./Pages/ThemeTesting.vue');

const identifier = (value) => {
    if (value === undefined || value === null || value === '') {
        return null;
    }
    
    const numeric = Number(value);
    return Number.isNaN(numeric) ? value : numeric;
};

const router = createRouter({
    history: createWebHistory('/'),
    routes: [
        {
            path: '/',
            name: 'homepage',
            component: Home,
            meta: {
                title: 'Home',
                section: 'library',
            },
        },
        {
            path: '/offline',
            name: 'offline',
            component: ErrorPage,
            props: { status: 911 },
            meta: { title: 'Offline' },
        },
        {
            path: '/coming-soon',
            name: 'coming-soon',
            component: ComingSoon,
            meta: { title: 'Coming Soon' },
        },
        {
            path: '/subscription',
            name: 'subscription.index',
            component: Subscription,
            meta: {
                title: 'Subscribe',
                section: 'account',
            },
        },
        
        // Auth
        {
            path: '/reset-password/:token',
            name: 'password.reset',
            component: NewPassword,
            props: route => ({
                token: route.params.token ?? '',
                email: route.query.email ?? '',
            }),
            meta: {
                title: 'Reset Password',
                section: 'account',
            },
        },
        {
            path: '/change-password',
            name: 'password.edit',
            component: NewPassword,
            meta: {
                requiresAuth: true,
                title: 'Change Password',
                section: 'account',
            },
        },
        
        // Wiki
        {
            path: '/wiki',
            component: RouterView,
            meta: { section: 'wiki' },
            children: [
                {
                    path: '',
                    name: 'wiki.index',
                    redirect: {
                        name: 'wiki.show',
                        params: { slug: 'about' },
                    },
                },
                {
                    path: 'edit/:page?',
                    name: 'wiki.edit',
                    component: WikiEdit,
                    props: route => ({
                        pageId: identifier(route.params.page),
                    }),
                    meta: {
                        roles: ['admin'],
                        title: 'Edit Wiki',
                    },
                },
                {
                    path: ':slug',
                    name: 'wiki.show',
                    component: WikiShow,
                    props: route => ({
                        pageSlug: String(route.params.slug),
                    }),
                    meta: { title: 'Wiki' },
                },
            ],
        },
        
        // Library
        {
            path: '/library',
            component: RouterView,
            meta: { section: 'library' },
            children: [
                {
                    path: 'terms',
                    component: RouterView,
                    children: [
                        {
                            path: '',
                            name: 'terms.index',
                            component: TermsIndex,
                            meta: { title: 'Dictionary' },
                        },
                        {
                            path: 'random',
                            name: 'terms.random',
                            component: TermShow,
                            props: { termSlug: 'random' },
                            meta: { title: 'Dictionary' },
                        },
                        {
                            path: ':term',
                            name: 'terms.show',
                            component: TermShow,
                            props: route => ({ termSlug: String(route.params.term) }),
                            meta: { title: 'Dictionary' },
                        },
                    ],
                },
                {
                    path: 'roots/:root',
                    name: 'roots.show',
                    component: TermRoot,
                    props: route => ({ rootId: identifier(route.params.root) }),
                    meta: { title: 'Dictionary' },
                },
                {
                    path: 'sentences',
                    component: RouterView,
                    children: [
                        {
                            path: '',
                            name: 'sentences.index',
                            component: SentencesIndex,
                            meta: { title: 'Library: Corpus' },
                        },
                        {
                            path: 'random',
                            name: 'sentences.random',
                            component: SentenceShow,
                            props: { sentenceId: 'random' },
                            meta: { title: 'Library: Corpus' },
                        },
                        {
                            path: ':sentence',
                            name: 'sentences.show',
                            component: SentenceShow,
                            props: route => ({ sentenceId: identifier(route.params.sentence) }),
                            meta: { title: 'Library: Corpus' },
                        },
                    ],
                },
                {
                    path: 'decks',
                    component: RouterView,
                    meta: {
                        requiresAuth: true,
                        requiresVerified: true,
                    },
                    children: [
                        {
                            path: '',
                            name: 'decks.index',
                            component: DecksIndex,
                            meta: { title: 'Library: Decks' },
                        },
                        {
                            path: 'random',
                            name: 'decks.random',
                            component: DeckShow,
                            props: { deckId: 'random' },
                            meta: { title: 'Library: Decks' },
                        },
                        {
                            path: ':deck',
                            name: 'decks.show',
                            component: DeckShow,
                            props: route => ({ deckId: identifier(route.params.deck) }),
                            meta: { title: 'Library: Decks' },
                        },
                    ],
                },
                {
                    path: 'audios',
                    component: RouterView,
                    meta: {
                        requiresAuth: true,
                        requiresVerified: true,
                    },
                    children: [
                        {
                            path: '',
                            name: 'audios.index',
                            component: AudiosIndex,
                            meta: { title: 'Library: Audios' },
                        },
                        {
                            path: ':speaker',
                            name: 'speaker.show',
                            component: SpeakerShow,
                            props: route => ({ speakerId: identifier(route.params.speaker) }),
                            meta: { title: 'Library: Audios' },
                        },
                    ],
                },
            ],
        },
        
        // Hub
        {
            path: '/hub',
            component: RouterView,
            meta: {
                requiresAuth: true,
                section: 'community',
            },
            children: [
                {
                    path: 'users',
                    component: RouterView,
                    children: [
                        {
                            path: '',
                            name: 'users.index',
                            component: UsersIndex,
                            meta: { title: 'Community Hub' },
                        },
                        {
                            path: ':user',
                            name: 'users.show',
                            component: UserShow,
                            props: route => ({
                                userUsername: String(route.params.user),
                            }),
                            meta: { title: route => `Pal: ${route.params.user}` },
                        },
                        {
                            path: ':user/edit',
                            name: 'users.edit',
                            component: UserEdit,
                            props: route => ({
                                userId: identifier(route.params.user),
                            }),
                            meta: { title: 'Edit Profile' },
                        },
                    ],
                },
            ],
        },
        
        // Academy
        {
            path: '/academy',
            component: RouterView,
            meta: {
                requiresAuth: true,
                requiresVerified: true,
                roles: ['student', 'admin'],
                section: 'academy',
            },
            children: [
                {
                    path: 'units',
                    component: RouterView,
                    children: [
                        {
                            path: '',
                            name: 'units.index',
                            component: UnitsIndex,
                            meta: { title: 'Academy: Lessons' },
                        },
                        {
                            path: ':unit',
                            name: 'units.show',
                            component: UnitShow,
                            props: route => ({ unitId: identifier(route.params.unit) }),
                            meta: { title: route => `Academy: Unit ${route.params.unit}` },
                        },
                    ],
                },
                {
                    path: 'lessons/:lesson',
                    name: 'lessons.show',
                    component: LessonShow,
                    props: route => ({ lessonId: identifier(route.params.lesson) }),
                    meta: { title: route => `Academy: Lesson ${route.params.lesson}` },
                },
                {
                    path: 'activities/:activity',
                    name: 'activities.show',
                    component: ActivityShow,
                    props: route => ({ activityId: identifier(route.params.activity) }),
                    meta: { title: 'Academy: Activities' },
                },
                {
                    path: 'activities/:activity/activity',
                    name: 'activities.activity',
                    component: ActivitySession,
                    props: route => ({ activityId: identifier(route.params.activity) }),
                    meta: { title: 'Activity' },
                },
                {
                    path: 'dialogs',
                    component: RouterView,
                    children: [
                        {
                            path: '',
                            name: 'dialogs.index',
                            component: DialogsIndex,
                            meta: { title: 'Academy: Dialogs' },
                        },
                        {
                            path: ':dialog',
                            name: 'dialogs.show',
                            component: DialogShow,
                            props: route => ({ dialogId: identifier(route.params.dialog) }),
                            meta: { title: 'Academy: Dialogs' },
                        },
                    ],
                },
                {
                    path: 'scores',
                    component: RouterView,
                    children: [
                        {
                            path: '',
                            name: 'scores.index',
                            component: ScoresIndex,
                            meta: { title: 'Academy: Scores' },
                        },
                        {
                            path: ':scorable_type/:scorable_id',
                            name: 'scores.history',
                            component: ScoresHistory,
                            props: route => ({
                                scorable_type: route.params.scorable_type,
                                scorable_id: identifier(route.params.scorable_id),
                                scoreId: identifier(route.query.score),
                            }),
                            meta: { title: 'Academy: Score History' },
                        },
                    ],
                },
            ],
        },
        
        // Workbench
        {
            path: '/workbench',
            component: RouterView,
            meta: {
                requiresAuth: true,
                requiresVerified: true,
                section: 'workbench',
            },
            children: [
                {
                    path: 'deck-master',
                    component: RouterView,
                    children: [
                        {
                            path: '',
                            name: 'deck-master.index',
                            component: DeckMasterIndex,
                            props: route => ({ mode: route.query.mode ?? 'build' }),
                            meta: { title: 'Deck Master' },
                        },
                        {
                            path: 'build/:deck?',
                            name: 'deck-master.build',
                            component: DeckMasterBuild,
                            props: route => ({ deckId: identifier(route.params.deck) }),
                            meta: { title: 'Deck Master: Build Deck' },
                        },
                        {
                            path: 'study/:deck',
                            name: 'deck-master.study',
                            component: DeckMasterStudy,
                            props: route => ({ deckId: identifier(route.params.deck) }),
                            meta: { title: 'Deck Master: Study' },
                        },
                    ],
                },
                {
                    path: 'card-dealer',
                    component: RouterView,
                    meta: {
                        roles: ['student', 'admin'],
                        section: 'academy',
                    },
                    children: [
                        {
                            path: '',
                            name: 'card-dealer.index',
                            component: CardDealerIndex,
                            props: route => ({
                                scope: route.query.scope,
                                deckId: identifier(route.query.deck),
                            }),
                            meta: { title: 'Card Dealer' },
                        },
                        {
                            path: 'cards',
                            name: 'card-dealer.cards',
                            component: CardDealerCards,
                            meta: { title: 'Card Dealer: Cards' },
                        },
                        {
                            path: 'review',
                            name: 'card-dealer.review',
                            component: CardDealerReview,
                            props: route => ({
                                scope: route.query.scope,
                                deckId: identifier(route.query.deck),
                            }),
                            meta: { title: 'Card Dealer: Review' },
                        },
                    ],
                },
                {
                    path: 'sound-booth',
                    name: 'sound-booth.index',
                    component: SoundBooth,
                    meta: { title: 'Sound Booth' },
                },
            ],
        },
        
        // Office
        {
            path: '/office',
            component: RouterView,
            meta: {
                requiresAuth: true,
                requiresVerified: true,
                roles: ['admin'],
                section: 'office',
            },
            children: [
                {
                    path: 'word-logger',
                    component: RouterView,
                    children: [
                        {
                            path: '',
                            name: 'word-logger.index',
                            component: WordLoggerIndex,
                            meta: { title: 'Word Logger' },
                        },
                        {
                            path: 'term/:term?',
                            name: 'word-logger.term',
                            component: WordLoggerTerm,
                            props: route => ({ termId: identifier(route.params.term) }),
                            meta: { title: 'Word Logger: Build Term' },
                        },
                    ],
                },
                {
                    path: 'speech-maker',
                    component: RouterView,
                    children: [
                        {
                            path: '',
                            name: 'speech-maker.index',
                            component: SpeechMaker,
                            meta: { title: 'Speech Maker' },
                        },
                        {
                            path: 'dialog/:dialog?',
                            name: 'speech-maker.dialog',
                            component: SpeechMakerDialog,
                            props: route => ({ dialogId: identifier(route.params.dialog) }),
                            meta: { title: 'Speech Maker: Build Dialog' },
                        },
                        {
                            path: 'dialog/:dialog/sentence',
                            name: 'speech-maker.dialog-sentence',
                            component: SpeechMakerSentence,
                            props: route => ({ initialDialogId: identifier(route.params.dialog) }),
                            meta: { title: 'Speech Maker: Build Sentence' },
                        },
                        {
                            path: 'sentence/:sentence?',
                            name: 'speech-maker.sentence',
                            component: SpeechMakerSentence,
                            props: route => ({ sentenceId: identifier(route.params.sentence) }),
                            meta: { title: 'Speech Maker: Build Sentence' },
                        },
                    ],
                },
                {
                    path: 'lesson-planner',
                    component: RouterView,
                    children: [
                        {
                            path: '',
                            name: 'lesson-planner.index',
                            component: LessonPlannerCourse,
                            meta: { title: 'Academy: Lessons' },
                        },
                        {
                            path: 'unit/:unit?',
                            name: 'lesson-planner.unit',
                            component: LessonPlannerUnit,
                            props: route => ({ unitId: identifier(route.params.unit) }),
                            meta: { title: route => `Lesson Planner: Unit ${route.params.unit ?? ''}`.trim() },
                        },
                        {
                            path: 'unit/:unit/decks',
                            name: 'lesson-planner.unit-decks',
                            component: LessonPlannerDecks,
                            props: route => ({ unitId: identifier(route.params.unit) }),
                            meta: { title: route => `Lesson Planner: Unit ${route.params.unit} Decks` },
                        },
                        {
                            path: 'unit/:unit/lesson',
                            name: 'lesson-planner.unit-lesson',
                            component: LessonPlannerLesson,
                            props: route => ({ initialUnitId: identifier(route.params.unit) }),
                            meta: { title: 'Lesson Planner: Lesson' },
                        },
                        {
                            path: 'lesson/:lesson?',
                            name: 'lesson-planner.lesson',
                            component: LessonPlannerLesson,
                            props: route => ({ lessonId: identifier(route.params.lesson) }),
                            meta: { title: route => `Lesson Planner: Lesson ${route.params.lesson ?? ''}`.trim() },
                        },
                        {
                            path: 'lesson/:lesson/activity',
                            name: 'lesson-planner.lesson-activity',
                            component: LessonPlannerActivity,
                            props: route => ({ initialLessonId: identifier(route.params.lesson) }),
                            meta: { title: 'Academy: Lessons' },
                        },
                    ],
                },
                {
                    path: 'feedback',
                    name: 'feedback.index',
                    component: ViewFeedback,
                    meta: { title: 'to-Do List' },
                },
            ],
        },
        {
            path: '/theme-testing',
            name: 'theme-testing',
            component: ThemeTesting,
            meta: {
                requiresAuth: true,
                roles: ['admin'],
                title: 'Theme Testing',
            },
        },
    ],
});

router.beforeEach(async (to) => {
    const UserStore = useUserStore();
    const NavigationStore = useNavigationStore();
    
    if (to.matched.some(record => record.meta.requiresAuth) && !UserStore.isUser) {
        NavigationStore.showSignIn = true;
        return false;
    }
    
    const requiredRoles = to.matched.reduce((roles, record) => {
        return record.meta.roles || roles;
    }, null);
    
    if (requiredRoles && UserStore.isUser) {
        const hasRole = requiredRoles.some(role => {
            if (role === 'admin') return UserStore.isAdmin;
            if (role === 'student') return UserStore.isStudent;
            if (role === 'pal') return UserStore.isUser;
            return false;
        });
        
        if (!hasRole) {
            return { name: 'subscription.index' };
        }
    }
    
    // if (to.meta.requiresSubscription && !UserStore.isSubscribed) {
    //     return { name: 'subscription.index' };
    // }
});

router.afterEach((to) => {
    const title = typeof to.meta.title === 'function'
        ? to.meta.title(to)
        : to.meta.title;
    document.title = title
        ? `${title} | PalWeb`
        : 'PalWeb';
});

export default router;
