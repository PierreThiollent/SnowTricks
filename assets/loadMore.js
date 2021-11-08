import Alpine from 'alpinejs';

Alpine.data('loadMore', () => ({
    async fetchData(url, items) {
        const response = await fetch(`${url}/${items.length}`);
        const text = await response.text();
        items[items.length - 1].insertAdjacentHTML('afterend', text);
    }
}));