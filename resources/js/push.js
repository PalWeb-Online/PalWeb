export async function enableWebPush() {
    if (!('serviceWorker' in navigator)) {
        throw new Error('Service workers are not supported in this browser.');
    }

    if (!('PushManager' in window)) {
        throw new Error('Push notifications are not supported in this browser. If you are using Safari, download the app via "Add to Home Screen" to enable push notifications.');
    }

    const permission = await Notification.requestPermission();

    if (permission !== 'granted') {
        throw new Error('Notification permission was not granted.');
    }

    const registration = await navigator.serviceWorker.ready;

    const vapidPublicKey = import.meta.env.VITE_WEB_PUSH_VAPID_PUBLIC_KEY;

    if (!vapidPublicKey) {
        throw new Error('Missing VITE_WEB_PUSH_VAPID_PUBLIC_KEY.');
    }

    const subscription = await registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array(vapidPublicKey),
    });

    const response = await fetch('/push-subscriptions', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        },
        body: JSON.stringify(subscription.toJSON()),
    });

    const contentType = response.headers.get('content-type') || '';
    const rawBody = await response.text();

    if (!response.ok) {
        throw new Error(`Failed to save push subscription (${response.status}).`);
    }

    if (!contentType.includes('application/json')) {
        throw new Error('Server returned HTML instead of JSON. Check console/network logs.');
    }

    return JSON.parse(rawBody);
}

export async function disableWebPush() {
    if (!('serviceWorker' in navigator)) {
        throw new Error('Service workers are not supported in this browser.');
    }

    const registration = await navigator.serviceWorker.ready;
    const subscription = await registration.pushManager.getSubscription();

    if (!subscription) {
        return { message: 'No active push subscription found.' };
    }

    const endpoint = subscription.endpoint;

    const response = await fetch('/push-subscriptions', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        },
        body: JSON.stringify({ endpoint }),
    });

    const contentType = response.headers.get('content-type') || '';
    const rawBody = await response.text();

    if (!response.ok) {
        throw new Error(`Failed to delete push subscription (${response.status}).`);
    }

    await subscription.unsubscribe();

    if (!contentType.includes('application/json')) {
        throw new Error('Server returned HTML instead of JSON.');
    }

    return JSON.parse(rawBody);
}

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding)
        .replace(/-/g, '+')
        .replace(/_/g, '/');

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }

    return outputArray;
}
