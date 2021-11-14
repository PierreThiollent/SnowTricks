import Alpine from 'alpinejs';

Alpine.data('commentsForm', () => ({
    /**
     * Methode ajoutant un commentaire en ajax
     * @return {Promise<void>}
     * @param trickId
     */
    async postComment(trickId) {
        const content = new FormData(this.$el);

        const response = await fetch(`/comment/new/${trickId}`, {
            body: content,
            method: 'POST'
        });

        const text = await response.text();

        if (response.ok) {
            const lastComment = document.querySelector('.comment-item:last-of-type');
            if (lastComment) {
                document.querySelector('.comment-item:last-of-type').insertAdjacentHTML('afterend', text);
            } else {
                let noComments = document.querySelector('.commentaires').querySelector('.no-comments');
                noComments.insertAdjacentHTML('beforebegin', text);
                noComments.remove();
            }
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