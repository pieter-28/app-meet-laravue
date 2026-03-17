export function formatDateID(date: string) {
    const options: Intl.DateTimeFormatOptions = {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    };
    return new Date(date).toLocaleDateString('id-ID', options);
}

export function formatDateTimeID(date: string) {
    const options: Intl.DateTimeFormatOptions = {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    };
    return new Date(date).toLocaleDateString('id-ID', options);
}

export function formatTimeID(date: string) {
    const options: Intl.DateTimeFormatOptions = {
        hour: '2-digit',
        minute: '2-digit',
    };
    return new Date(date).toLocaleDateString('id-ID', options);
}
