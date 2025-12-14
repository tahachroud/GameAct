class GameFormManager {
    constructor(containerId = 'formContainer') {
        this.currentStep = 1;
        this.totalSteps = 3;
        this.containerId = containerId;
        this.categories = [
            'Action Shooter', 'Adventure', 'Battle Royale', 'FPS', 
            'Hero Shooter', 'MOBA', 'Open World', 'RPG', 'Sandbox', 'Sports', 'Strategy', 'racing'
        ];
        this.formData = {
            image: null,
            title: '',
            category: '',
            description: '',
            storyline: '',
            trailer: '',
            download_link: '',
            is_free: '0',
            price: '19.99'
        };
        this.validationRules = {
            image: { required: true, type: 'file' },
            title: { required: true, minLength: 3, maxLength: 100 },
            category: { required: true },
            description: { required: true, minLength: 10, maxLength: 500 },
            storyline: { maxLength: 2000 },
            price: { min: 0.99 }
        };
        this.init();
    }

    init() {
        this.buildForm();
        this.cacheElements();
        this.attachEventListeners();
        this.updateProgress();
    }

    buildForm() {
        const container = document.getElementById(this.containerId);
        if (!container) {
            console.error(`Container with id "${this.containerId}" not found`);
            return;
        }
        this.form = document.createElement('form');
        this.form.id = 'addGameForm';
        this.form.method = 'POST';
        this.form.enctype = 'multipart/form-data';
        const step1 = this.createStep1();
        this.form.appendChild(step1);
        const step2 = this.createStep2();
        this.form.appendChild(step2);
        const step3 = this.createStep3();
        this.form.appendChild(step3);
        const cardForm = document.createElement('div');
        cardForm.className = 'card-form';
        cardForm.appendChild(this.form);
        container.appendChild(cardForm);
    }

    createStep1() {
        const step = document.createElement('div');
        step.className = 'step active';
        step.id = 'step1';
        const title = document.createElement('h4');
        title.textContent = '📸 Step 1: Upload Game Cover';
        step.appendChild(title);
        const group = document.createElement('div');
        group.className = 'mb-4';
        const label = document.createElement('label');
        label.className = 'form-label';
        label.textContent = 'Game Image (JPG/PNG) *';
        group.appendChild(label);
        const input = document.createElement('input');
        input.type = 'file';
        input.className = 'form-control';
        input.id = 'gameImage';
        input.name = 'image';
        input.accept = 'image/jpeg,image/png,image/jpg,image/webp';
        input.required = true;
        group.appendChild(input);
        const fileInfo = document.createElement('div');
        fileInfo.className = 'file-info';
        fileInfo.innerHTML = '<i class="fa fa-info-circle"></i> Formats acceptés : JPG, PNG, WEBP | Taille recommandée : 800x600px';
        group.appendChild(fileInfo);
        const previewContainer = document.createElement('div');
        previewContainer.className = 'mt-3 text-center';
        const img = document.createElement('img');
        img.id = 'imagePreview';
        img.className = 'preview-img';
        img.alt = 'Preview';
        img.style.display = 'none';
        previewContainer.appendChild(img);
        group.appendChild(previewContainer);
        const errorMsg = document.createElement('div');
        errorMsg.className = 'error-msg';
        errorMsg.id = 'errImage';
        group.appendChild(errorMsg);
        step.appendChild(group);
        const buttonContainer = document.createElement('div');
        buttonContainer.className = 'text-end';
        const nextBtn = document.createElement('button');
        nextBtn.type = 'button';
        nextBtn.className = 'btn btn-primary next-btn px-4';
        nextBtn.innerHTML = 'Next <i class="fa fa-arrow-right"></i>';
        buttonContainer.appendChild(nextBtn);
        step.appendChild(buttonContainer);
        return step;
    }

    createStep2() {
        const step = document.createElement('div');
        step.className = 'step';
        step.id = 'step2';
        const title = document.createElement('h4');
        title.textContent = '📝 Step 2: Game Details';
        step.appendChild(title);
        const row = document.createElement('div');
        row.className = 'row g-3';
        const colName = document.createElement('div');
        colName.className = 'col-md-6';
        const labelName = document.createElement('label');
        labelName.className = 'form-label';
        labelName.textContent = 'Game Name *';
        const inputName = document.createElement('input');
        inputName.type = 'text';
        inputName.className = 'form-control';
        inputName.id = 'gameName';
        inputName.name = 'title';
        inputName.placeholder = 'Enter game title';
        inputName.required = true;
        const errName = document.createElement('div');
        errName.className = 'error-msg';
        errName.id = 'errName';
        colName.appendChild(labelName);
        colName.appendChild(inputName);
        colName.appendChild(errName);
        row.appendChild(colName);
        const colGenre = document.createElement('div');
        colGenre.className = 'col-md-6';
        const labelGenre = document.createElement('label');
        labelGenre.className = 'form-label';
        labelGenre.textContent = 'Genre *';
        const selectGenre = document.createElement('select');
        selectGenre.className = 'form-select';
        selectGenre.id = 'gameGenre';
        selectGenre.name = 'category';
        selectGenre.required = true;
        const optionDefault = document.createElement('option');
        optionDefault.value = '';
        optionDefault.textContent = 'Select Genre';
        selectGenre.appendChild(optionDefault);
        this.categories.forEach(cat => {
            const option = document.createElement('option');
            option.value = cat;
            option.textContent = cat;
            selectGenre.appendChild(option);
        });
        const errGenre = document.createElement('div');
        errGenre.className = 'error-msg';
        errGenre.id = 'errGenre';
        colGenre.appendChild(labelGenre);
        colGenre.appendChild(selectGenre);
        colGenre.appendChild(errGenre);
        row.appendChild(colGenre);
        step.appendChild(row);
        const descGroup = document.createElement('div');
        descGroup.className = 'mt-3';
        const labelDesc = document.createElement('label');
        labelDesc.className = 'form-label';
        labelDesc.textContent = 'Short Description *';
        const textareaDesc = document.createElement('textarea');
        textareaDesc.className = 'form-control';
        textareaDesc.id = 'gameDesc';
        textareaDesc.name = 'description';
        textareaDesc.rows = '3';
        textareaDesc.placeholder = 'Brief description of the game...';
        textareaDesc.required = true;
        const errDesc = document.createElement('div');
        errDesc.className = 'error-msg';
        errDesc.id = 'errDesc';
        descGroup.appendChild(labelDesc);
        descGroup.appendChild(textareaDesc);
        descGroup.appendChild(errDesc);
        step.appendChild(descGroup);
        const storyGroup = document.createElement('div');
        storyGroup.className = 'mt-3';
        const labelStory = document.createElement('label');
        labelStory.className = 'form-label';
        labelStory.textContent = 'Full Storyline';
        const textareaStory = document.createElement('textarea');
        textareaStory.className = 'form-control';
        textareaStory.id = 'gameStoryline';
        textareaStory.name = 'storyline';
        textareaStory.rows = '5';
        textareaStory.placeholder = 'Detailed story of the game (optional)';
        storyGroup.appendChild(labelStory);
        storyGroup.appendChild(textareaStory);
        step.appendChild(storyGroup);
        const trailerGroup = document.createElement('div');
        trailerGroup.className = 'mt-3';
        const labelTrailer = document.createElement('label');
        labelTrailer.className = 'form-label';
        labelTrailer.textContent = 'Game Trailer (YouTube Link) - Optional';
        const inputTrailer = document.createElement('input');
        inputTrailer.type = 'text';
        inputTrailer.className = 'form-control';
        inputTrailer.id = 'gameTrailer';
        inputTrailer.name = 'trailer';
        inputTrailer.placeholder = 'https://www.youtube.com/watch?v=...';
        const fileInfoTrailer = document.createElement('div');
        fileInfoTrailer.className = 'file-info';
        fileInfoTrailer.innerHTML = '<i class="fa fa-info-circle"></i> Entrez un lien YouTube valide (ex: https://www.youtube.com/watch?v=dQw4w9WgXcQ)';
        const errTrailer = document.createElement('div');
        errTrailer.className = 'error-msg';
        errTrailer.id = 'errTrailer';
        trailerGroup.appendChild(labelTrailer);
        trailerGroup.appendChild(inputTrailer);
        trailerGroup.appendChild(fileInfoTrailer);
        trailerGroup.appendChild(errTrailer);
        step.appendChild(trailerGroup);
        const linkGroup = document.createElement('div');
        linkGroup.className = 'mt-3';
        const labelLink = document.createElement('label');
        labelLink.className = 'form-label';
        labelLink.textContent = 'Download Link (URL) - Optional';
        const inputLink = document.createElement('input');
        inputLink.type = 'text';
        inputLink.className = 'form-control';
        inputLink.id = 'downloadLink';
        inputLink.name = 'download_link';
        inputLink.placeholder = 'https://example.com/download-or-page';
        const fileInfoLink = document.createElement('div');
        fileInfoLink.className = 'file-info';
        fileInfoLink.innerHTML = '<i class="fa fa-info-circle"></i> Entrez un lien complet (https://...) ou laissez vide.';
        const errLink = document.createElement('div');
        errLink.className = 'error-msg';
        errLink.id = 'errDownloadLink';
        linkGroup.appendChild(labelLink);
        linkGroup.appendChild(inputLink);
        linkGroup.appendChild(fileInfoLink);
        linkGroup.appendChild(errLink);
        step.appendChild(linkGroup);
        const buttonContainer = document.createElement('div');
        buttonContainer.className = 'mt-4 d-flex justify-content-between';
        const prevBtn = document.createElement('button');
        prevBtn.type = 'button';
        prevBtn.className = 'btn btn-secondary prev-btn';
        prevBtn.innerHTML = '<i class="fa fa-arrow-left"></i> Previous';
        const nextBtn = document.createElement('button');
        nextBtn.type = 'button';
        nextBtn.className = 'btn btn-primary next-btn';
        nextBtn.innerHTML = 'Next <i class="fa fa-arrow-right"></i>';
        buttonContainer.appendChild(prevBtn);
        buttonContainer.appendChild(nextBtn);
        step.appendChild(buttonContainer);
        return step;
    }

    createStep3() {
        const step = document.createElement('div');
        step.className = 'step';
        step.id = 'step3';
        const title = document.createElement('h4');
        title.textContent = '💰 Step 3: Pricing';
        step.appendChild(title);
        const radioGroup = document.createElement('div');
        radioGroup.className = 'mb-4';
        const freeCheckDiv = document.createElement('div');
        freeCheckDiv.className = 'form-check mb-3';
        const freeRadio = document.createElement('input');
        freeRadio.className = 'form-check-input';
        freeRadio.type = 'radio';
        freeRadio.name = 'is_free';
        freeRadio.id = 'free';
        freeRadio.value = '1';
        const freeLabel = document.createElement('label');
        freeLabel.className = 'form-check-label';
        freeLabel.htmlFor = 'free';
        freeLabel.innerHTML = '<strong>Free Game</strong> - Le jeu sera gratuit pour tous les utilisateurs';
        freeCheckDiv.appendChild(freeRadio);
        freeCheckDiv.appendChild(freeLabel);
        radioGroup.appendChild(freeCheckDiv);
        const paidCheckDiv = document.createElement('div');
        paidCheckDiv.className = 'form-check';
        const paidRadio = document.createElement('input');
        paidRadio.className = 'form-check-input';
        paidRadio.type = 'radio';
        paidRadio.name = 'is_free';
        paidRadio.id = 'paid';
        paidRadio.value = '0';
        paidRadio.checked = true;
        const paidLabel = document.createElement('label');
        paidLabel.className = 'form-check-label';
        paidLabel.htmlFor = 'paid';
        paidLabel.innerHTML = '<strong>Paid Game</strong> - Le jeu sera payant';
        paidCheckDiv.appendChild(paidRadio);
        paidCheckDiv.appendChild(paidLabel);
        radioGroup.appendChild(paidCheckDiv);
        step.appendChild(radioGroup);
        const paidOptions = document.createElement('div');
        paidOptions.id = 'paidOptions';
        const rowPrice = document.createElement('div');
        rowPrice.className = 'row g-3';
        const colPrice = document.createElement('div');
        colPrice.className = 'col-md-6';
        const labelPrice = document.createElement('label');
        labelPrice.className = 'form-label';
        labelPrice.textContent = 'Price ($) *';
        const inputPrice = document.createElement('input');
        inputPrice.type = 'number';
        inputPrice.step = '0.01';
        inputPrice.className = 'form-control';
        inputPrice.id = 'gamePrice';
        inputPrice.name = 'price';
        inputPrice.value = '19.99';
        inputPrice.min = '0.99';
        inputPrice.required = true;
        const errPrice = document.createElement('div');
        errPrice.className = 'error-msg';
        errPrice.id = 'errPrice';
        colPrice.appendChild(labelPrice);
        colPrice.appendChild(inputPrice);
        colPrice.appendChild(errPrice);
        rowPrice.appendChild(colPrice);
        paidOptions.appendChild(rowPrice);
        step.appendChild(paidOptions);
        const buttonContainer = document.createElement('div');
        buttonContainer.className = 'mt-4 d-flex justify-content-between';
        const prevBtn = document.createElement('button');
        prevBtn.type = 'button';
        prevBtn.className = 'btn btn-secondary prev-btn';
        prevBtn.innerHTML = '<i class="fa fa-arrow-left"></i> Previous';
        const submitBtn = document.createElement('button');
        submitBtn.type = 'submit';
        submitBtn.className = 'btn btn-success px-5';
        submitBtn.innerHTML = '<i class="fa fa-check"></i> Add Game';
        buttonContainer.appendChild(prevBtn);
        buttonContainer.appendChild(submitBtn);
        step.appendChild(buttonContainer);
        return step;
    }

    cacheElements() {
        this.form = document.getElementById('addGameForm');
        this.steps = document.querySelectorAll('.step');
        this.nextBtns = document.querySelectorAll('.next-btn');
        this.prevBtns = document.querySelectorAll('.prev-btn');
        this.submitBtn = this.form.querySelector('button[type="submit"]');
        this.progressBar = document.getElementById('progress');
        this.gameImage = document.getElementById('gameImage');
        this.imagePreview = document.getElementById('imagePreview');
        this.gameName = document.getElementById('gameName');
        this.gameGenre = document.getElementById('gameGenre');
        this.gameDesc = document.getElementById('gameDesc');
        this.gameStoryline = document.getElementById('gameStoryline');
        this.gameTrailer = document.getElementById('gameTrailer');
        this.downloadLink = document.getElementById('downloadLink');
        this.freeRadio = document.getElementById('free');
        this.paidRadio = document.getElementById('paid');
        this.gamePrice = document.getElementById('gamePrice');
        this.paidOptions = document.getElementById('paidOptions');
    }

    attachEventListeners() {
        this.nextBtns.forEach(btn => {
            btn.addEventListener('click', (e) => this.handleNextStep(e));
        });
        this.prevBtns.forEach(btn => {
            btn.addEventListener('click', (e) => this.handlePrevStep(e));
        });
        this.gameImage.addEventListener('change', (e) => this.handleImagePreview(e));
        this.freeRadio.addEventListener('change', () => this.togglePriceOptions());
        this.paidRadio.addEventListener('change', () => this.togglePriceOptions());
        this.gameName.addEventListener('input', (e) => {
            this.formData.title = e.target.value;
        });
        this.gameGenre.addEventListener('change', (e) => {
            this.formData.category = e.target.value;
        });
        this.gameDesc.addEventListener('input', (e) => {
            this.formData.description = e.target.value;
        });
        this.gameStoryline.addEventListener('input', (e) => {
            this.formData.storyline = e.target.value;
        });
        this.gameImage.addEventListener('change', (e) => {
            this.formData.image = e.target.files[0] || null;
        });
        this.gameTrailer.addEventListener('input', (e) => {
            this.formData.trailer = e.target.value || '';
        });
        this.downloadLink.addEventListener('input', (e) => {
            this.formData.download_link = e.target.value || '';
        });
        this.gamePrice.addEventListener('input', (e) => {
            this.formData.price = e.target.value;
        });
        this.freeRadio.addEventListener('change', (e) => {
            if (e.target.checked) this.formData.is_free = '1';
        });
        this.paidRadio.addEventListener('change', (e) => {
            if (e.target.checked) this.formData.is_free = '0';
        });
        this.form.addEventListener('submit', (e) => this.handleFormSubmit(e));
    }

    isValidYouTubeUrl(url) {
        if (!url) return true; // Optional field
        const youtubeRegex = /^(https?:\/\/)?(www\.)?(youtube\.com\/(watch\?v=|embed\/|v\/)|youtu\.be\/)[\w-]{11}(&.*)?$/;
        return youtubeRegex.test(url);
    }

    validateStep1() {
        this.clearErrors();
        let isValid = true;
        const file = this.gameImage.files[0];
        if (!file) {
            this.showError('errImage', 'Veuillez uploader une image de couverture.');
            isValid = false;
        } else {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                this.showError('errImage', 'Format non autorisé. Utilisez JPG, PNG ou WEBP.');
                isValid = false;
            }
            if (file.size > 5 * 1024 * 1024) {
                this.showError('errImage', 'La taille de l\'image ne doit pas dépasser 5MB.');
                isValid = false;
            }
        }
        return isValid;
    }

    validateStep2() {
        this.clearErrors();
        let isValid = true;
        if (!this.gameName.value.trim()) {
            this.showError('errName', 'Nom du jeu requis.');
            isValid = false;
        } else if (this.gameName.value.trim().length < 3) {
            this.showError('errName', 'Le nom doit contenir au moins 3 caractères.');
            isValid = false;
        } else if (this.gameName.value.trim().length > 100) {
            this.showError('errName', 'Le nom ne doit pas dépasser 100 caractères.');
            isValid = false;
        }
        if (!this.gameGenre.value) {
            this.showError('errGenre', 'Sélectionnez un genre.');
            isValid = false;
        }
        if (!this.gameDesc.value.trim()) {
            this.showError('errDesc', 'Description requise.');
            isValid = false;
        } else if (this.gameDesc.value.trim().length < 10) {
            this.showError('errDesc', 'La description doit contenir au moins 10 caractères.');
            isValid = false;
        } else if (this.gameDesc.value.trim().length > 500) {
            this.showError('errDesc', 'La description ne doit pas dépasser 500 caractères.');
            isValid = false;
        }
        if (this.gameTrailer.value.trim()) {
            if (!this.isValidYouTubeUrl(this.gameTrailer.value.trim())) {
                this.showError('errTrailer', 'Entrez un lien YouTube valide (ex: https://www.youtube.com/watch?v=...)');
                isValid = false;
            }
        }
        if (this.downloadLink.value.trim()) {
            const url = this.downloadLink.value.trim();
            try {
                const parsed = new URL(url);
                if (!['http:', 'https:'].includes(parsed.protocol)) {
                    this.showError('errDownloadLink', 'Entrez une URL valide (http(s)).');
                    isValid = false;
                }
            } catch (err) {
                this.showError('errDownloadLink', 'Entrez une URL valide (http(s)).');
                isValid = false;
            }
        }
        return isValid;
    }

    validateStep3() {
        this.clearErrors();
        let isValid = true;
        if (this.paidRadio.checked) {
            const price = parseFloat(this.gamePrice.value);
            if (!this.gamePrice.value || isNaN(price)) {
                this.showError('errPrice', 'Entrez un prix valide.');
                isValid = false;
            } else if (price < 0.99) {
                this.showError('errPrice', 'Prix minimum : 0.99$');
                isValid = false;
            } else if (price > 999.99) {
                this.showError('errPrice', 'Prix maximum : 999.99$');
                isValid = false;
            }
        }
        return isValid;
    }

    validateStep(stepNumber) {
        switch (stepNumber) {
            case 1:
                return this.validateStep1();
            case 2:
                return this.validateStep2();
            case 3:
                return this.validateStep3();
            default:
                return false;
        }
    }

    handleNextStep(e) {
        e.preventDefault();
        if (this.validateStep(this.currentStep)) {
            if (this.currentStep < this.totalSteps) {
                this.goToStep(this.currentStep + 1);
            }
        }
    }

    handlePrevStep(e) {
        e.preventDefault();
        if (this.currentStep > 1) {
            this.goToStep(this.currentStep - 1);
        }
    }

    goToStep(stepNumber) {
        if (stepNumber < 1 || stepNumber > this.totalSteps) return;
        document.getElementById(`step${this.currentStep}`).classList.remove('active');
        this.currentStep = stepNumber;
        document.getElementById(`step${this.currentStep}`).classList.add('active');
        this.updateProgress();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    updateProgress() {
        const progressPercent = (this.currentStep / this.totalSteps) * 100;
        this.progressBar.style.width = progressPercent + '%';
    }

    handleImagePreview(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                this.imagePreview.src = event.target.result;
                this.imagePreview.style.display = 'block';
                this.addAnimation(this.imagePreview);
            };
            reader.readAsDataURL(file);
        }
    }

    togglePriceOptions() {
        if (this.freeRadio.checked) {
            this.paidOptions.style.display = 'none';
            this.gamePrice.required = false;
        } else {
            this.paidOptions.style.display = 'block';
            this.gamePrice.required = true;
        }
    }

    showError(elementId, message) {
        const errorElement = document.getElementById(elementId);
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.style.display = 'block';
            this.addAnimation(errorElement, 'shake');
        }
    }

    clearErrors() {
        document.querySelectorAll('.error-msg').forEach(el => {
            el.textContent = '';
            el.style.display = 'none';
        });
    }

    addAnimation(element, animationType = 'fadeIn') {
        if (animationType === 'fadeIn') {
            element.style.animation = 'fadeIn 0.3s ease-in';
        } else if (animationType === 'shake') {
            element.style.animation = 'shake 0.5s ease-in-out';
        }
    }

    handleFormSubmit(e) {
        if (!this.validateStep3()) {
            e.preventDefault();
            this.showFormError('Veuillez remplir tous les champs obligatoires de l\'étape 3.');
            return false;
        }
        return true;
    }

    showFormError(message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger alert-dismissible fade show';
        alertDiv.role = 'alert';
        alertDiv.innerHTML = `
            <i class="fa fa-exclamation-triangle"></i> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        this.form.parentElement.insertBefore(alertDiv, this.form);
    }

    getFormData() {
        return this.formData;
    }

    resetForm() {
        this.form.reset();
        this.imagePreview.style.display = 'none';
        this.clearErrors();
        this.goToStep(1);
        this.formData = {
            image: null,
            title: '',
            category: '',
            description: '',
            storyline: '',
            trailer: '',
            download_link: '',
            is_free: '0',
            price: '19.99'
        };
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new GameFormManager();
});