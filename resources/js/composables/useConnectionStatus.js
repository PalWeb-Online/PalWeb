import { ref, computed, onMounted, onUnmounted } from 'vue'

export function useConnectionStatus(echoInstance) {
    const browserOnline = ref(navigator.onLine)
    const socketConnected = ref(false)

    const updateBrowserStatus = () => {
        browserOnline.value = navigator.onLine
    }

    const setupSocketListeners = () => {
        if (!echoInstance?.connector || !echoInstance.connector.pusher) {
            return
        }

        const connection = echoInstance.connector.pusher.connection

        const handleStateChange = (state) => {
            socketConnected.value = state === 'connected'
        }

        connection.bind('state_change', ({ current }) => {
            handleStateChange(current)
        })

        handleStateChange(connection.state)
    }

    const status = computed(() => {
        if (!browserOnline.value) return 'offline'
        if (!socketConnected.value) return 'connecting'
        return 'online'
    })

    onMounted(() => {
        window.addEventListener('online', updateBrowserStatus)
        window.addEventListener('offline', updateBrowserStatus)
        setupSocketListeners()
    })

    onUnmounted(() => {
        window.removeEventListener('online', updateBrowserStatus)
        window.removeEventListener('offline', updateBrowserStatus)
    })

    return {
        status,
        browserOnline,
        socketConnected
    }
}
