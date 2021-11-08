import Alpine from 'alpinejs';

Alpine.data('commentsForm', () => ({
    /**
     * Methode ajoutant un commentaire en ajax
     * @param id
     * @return {Promise<void>}
     */
    async postComment(id) {
        const content = new FormData(this.$el);

        const response = await fetch(`/comment/new/${id}`, {
            body: content,
            method: 'POST'
        });

        const text = await response.text();

        if (response.ok) {
            document.querySelector('.comment-item:last-of-type').insertAdjacentHTML('afterend', text);
        } else {
            const error = this.$el.querySelector('.error');
            error.innerText = text;
            error.classList.remove('hidden');

            setTimeout(() => {
                error.classList.add('hidden');
                error.innerText = ''
            }, 3500);
        }
    }
}));