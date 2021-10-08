/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of profileMenu JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in profileMenu case)
import './styles/app.css';
import Alpine from 'alpinejs'

Alpine.data('loadMorePosts', () => ({
        fetchPosts() {
            const offset = document.getElementsByClassName('trick-item').length;
            fetch('/load-posts/' + offset, {
                headers: {
                    "Content-Type": "text/html; charset=utf-8"
                },
            })
                .then(data => data.text())
                .then(result => document.getElementById('tricks').innerHTML += result)
                .catch(error => {
                    console.log('une erreur s\'est produite', error);
                });
        }
    })
);

Alpine.start()
