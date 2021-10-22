/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of profileMenu JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in profileMenu case)
import './styles/app.css';
import Alpine from 'alpinejs'

Alpine.data('loadMore', () => ({
        async fetchData(url, items) {
            const response = await fetch(`${url}/${items.length}`);
            const text = await response.text();
            items[items.length - 1].insertAdjacentHTML('afterend', text);
        }
    })
);

Alpine.data('commentsForm', () => ({

        /**
         * Methode ajoutant un commentaire en ajax
         * @param id
         * @return {Promise<void>}
         */
        async postComment(id) {
            this.content = new FormData(this.$el);

            const response = await fetch(`/comment/new/${id}`, {
                body: this.content,
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
    })
);

Alpine.start()
