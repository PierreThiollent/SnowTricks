import Alpine from 'alpinejs';

Alpine.data('imageUpload', (trickId = null) => ({
        files: [],

        async init() {
            if (trickId !== null) {
                let images = await fetch(`/trick/get-images/${trickId}`);
                images = await images.json();
                images.map(async image => {
                    let fileBlob = await fetch(`/images/tricks/${image}`);
                    let file = new File([await fileBlob.blob()], image);
                    this.files.push(file);
                });

                setTimeout(() => this.setInputFiles(), 100);
            }
        },

        updateFiles() {
            let uploadedFiles = Array.from(this.$event.target.files);
            this.files = this.files.concat(uploadedFiles).slice(0, 3);

            this.setInputFiles();
        },

        setInputFiles() {
            const targetFiles = new DataTransfer();
            this.files.forEach(file => targetFiles.items.add(file));
            this.$refs.input.files = targetFiles.files;
        },

        deleteFile(file) {
            this.files = this.files.filter(f => f !== file);
            this.setInputFiles();
        },
    })
);