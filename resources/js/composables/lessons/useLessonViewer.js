import {useLessonLoader} from "./useLessonLoader.js";
import {useDocumentResourceViewer} from "../documents/useDocumentResourceViewer.js";

export function useLessonViewer() {
    const lessonLoader = useLessonLoader();

    const {
        load: loadLesson,
        reload: reloadLesson,
    } = useDocumentResourceViewer({
        fetchModel: (lessonId) => lessonLoader.fetchLesson(lessonId, {
            include: 'show',
        }),
        resetModel: lessonLoader.setLesson,
        getBlocks: (document) => document?.skills?.flatMap((skill) => skill.blocks ?? []) ?? [],
    });

    return {
        ...lessonLoader,
        loadLesson,
        reloadLesson,
    };
}
