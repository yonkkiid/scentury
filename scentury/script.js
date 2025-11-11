// Основной JavaScript файл для сайта Scentury

document.addEventListener('DOMContentLoaded', function() {
    // Инициализация всех функций
    initMobileMenu();
    initConstructor();
    initCatalogFilters();
    initFormValidation();
    initSmoothScrolling();
});

// Мобильное меню
function initMobileMenu() {
    const mobileToggle = document.querySelector('.mobile-menu-toggle');
    const nav = document.querySelector('.nav');
    
    if (mobileToggle && nav) {
        mobileToggle.addEventListener('click', function() {
            nav.classList.toggle('active');
            mobileToggle.classList.toggle('active');
        });
    }
}

// Конструктор ароматов
function initConstructor() {
    const noteItems = document.querySelectorAll('.note-item');
    const selectedNotesList = document.querySelector('.selected-notes-list');
    const clearButton = document.getElementById('clear-notes');
    const sendButton = document.getElementById('send-request');
    const scentNameInput = document.getElementById('scent-name');
    
    let selectedNotes = {
        top: [],
        heart: [],
        base: []
    };
    
    // Обработка клика по нотам
    noteItems.forEach(item => {
        item.addEventListener('click', function() {
            const noteName = this.dataset.note;
            const noteType = this.dataset.type;
            
            // Переключение выбора ноты
            this.classList.toggle('selected');
            
            if (this.classList.contains('selected')) {
                // Добавление ноты
                if (!selectedNotes[noteType].includes(noteName)) {
                    selectedNotes[noteType].push(noteName);
                }
            } else {
                // Удаление ноты
                const index = selectedNotes[noteType].indexOf(noteName);
                if (index > -1) {
                    selectedNotes[noteType].splice(index, 1);
                }
            }
            
            updateSelectedNotesDisplay();
        });
    });
    
    // Обновление отображения выбранных нот
    function updateSelectedNotesDisplay() {
        const emptyMessage = selectedNotesList.querySelector('.selected-notes-empty');
        if (emptyMessage) {
            emptyMessage.remove();
        }
        
        // Очистка списка
        selectedNotesList.innerHTML = '';
        
        // Проверка, есть ли выбранные ноты
        const hasNotes = Object.values(selectedNotes).some(notes => notes.length > 0);
        
        if (!hasNotes) {
            selectedNotesList.innerHTML = '<div class="selected-notes-empty">Выберите ноты для создания аромата</div>';
            return;
        }
        
        // Отображение выбранных нот по категориям
        Object.keys(selectedNotes).forEach(type => {
            if (selectedNotes[type].length > 0) {
                const typeNames = {
                    top: 'Верхние',
                    heart: 'Сердечные',
                    base: 'Базовые'
                };
                
                const typeDiv = document.createElement('div');
                typeDiv.innerHTML = `<strong>${typeNames[type]}:</strong>`;
                selectedNotesList.appendChild(typeDiv);
                
                selectedNotes[type].forEach(note => {
                    const noteDiv = document.createElement('div');
                    noteDiv.className = 'selected-note';
                    noteDiv.textContent = note;
                    selectedNotesList.appendChild(noteDiv);
                });
            }
        });
    }
    
    // Очистка выбранных нот
    if (clearButton) {
        clearButton.addEventListener('click', function() {
            // Сброс визуального состояния
            noteItems.forEach(item => {
                item.classList.remove('selected');
            });
            
            // Сброс данных
            selectedNotes = {
                top: [],
                heart: [],
                base: []
            };
            
            updateSelectedNotesDisplay();
        });
    }
    
    // Отправка заявки
    if (sendButton) {
        sendButton.addEventListener('click', function() {
            const scentName = scentNameInput ? scentNameInput.value.trim() : '';
            const hasNotes = Object.values(selectedNotes).some(notes => notes.length > 0);
            
            if (!hasNotes) {
                alert('Пожалуйста, выберите хотя бы одну ноту для создания аромата');
                return;
            }
            
            if (!scentName) {
                alert('Пожалуйста, введите название вашего аромата');
                return;
            }
            
            // Подготовка данных для передачи в форму контактов
            const selectedNotesText = prepareSelectedNotesText();
            
            // Переход на страницу контактов с данными
            const params = new URLSearchParams();
            params.set('scent_name', scentName);
            params.set('selected_notes', selectedNotesText);
            
            window.location.href = `contacts.php?${params.toString()}`;
        });
    }
    
    // Подготовка текста выбранных нот
    function prepareSelectedNotesText() {
        let text = '';
        
        Object.keys(selectedNotes).forEach(type => {
            if (selectedNotes[type].length > 0) {
                const typeNames = {
                    top: 'Верхние',
                    heart: 'Сердечные',
                    base: 'Базовые'
                };
                
                text += `${typeNames[type]}: ${selectedNotes[type].join(', ')}\n`;
            }
        });
        
        return text.trim();
    }
    
    // Заполнение формы контактов данными из конструктора
    if (window.location.search) {
        const params = new URLSearchParams(window.location.search);
        const scentName = params.get('scent_name');
        const selectedNotes = params.get('selected_notes');
        
        if (scentName) {
            const scentNameField = document.getElementById('scent-name');
            if (scentNameField) {
                scentNameField.value = scentName;
            }
        }
        
        if (selectedNotes) {
            const selectedNotesField = document.getElementById('selected-notes');
            if (selectedNotesField) {
                selectedNotesField.value = selectedNotes;
            }
        }
    }
}

// Фильтры каталога
function initCatalogFilters() {
    const priceFilter = document.getElementById('price-filter');
    const typeFilter = document.getElementById('type-filter');
    const resetButton = document.getElementById('reset-filters');
    const catalogItems = document.querySelectorAll('.catalog-item');
    
    function filterItems() {
        const priceValue = priceFilter ? priceFilter.value : 'all';
        const typeValue = typeFilter ? typeFilter.value : 'all';
        
        catalogItems.forEach(item => {
            const itemPrice = parseInt(item.dataset.price);
            const itemType = item.dataset.type;
            
            let showItem = true;
            
            // Фильтр по цене
            if (priceValue !== 'all') {
                switch (priceValue) {
                    case '0-2500':
                        showItem = showItem && itemPrice <= 2500;
                        break;
                    case '2500-3000':
                        showItem = showItem && itemPrice > 2500 && itemPrice <= 3000;
                        break;
                    case '3000+':
                        showItem = showItem && itemPrice > 3000;
                        break;
                }
            }
            
            // Фильтр по типу
            if (typeValue !== 'all') {
                showItem = showItem && itemType === typeValue;
            }
            
            // Показать/скрыть элемент
            item.style.display = showItem ? 'block' : 'none';
        });
    }
    
    // Обработчики событий
    if (priceFilter) {
        priceFilter.addEventListener('change', filterItems);
    }
    
    if (typeFilter) {
        typeFilter.addEventListener('change', filterItems);
    }
    
    if (resetButton) {
        resetButton.addEventListener('click', function() {
            if (priceFilter) priceFilter.value = 'all';
            if (typeFilter) typeFilter.value = 'all';
            filterItems();
        });
    }
}

// Валидация форм
function initFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
            }
        });
    });
}

function validateForm(form) {
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        const value = field.value.trim();
        
        if (!value) {
            showFieldError(field, 'Это поле обязательно для заполнения');
            isValid = false;
        } else {
            clearFieldError(field);
            
            // Дополнительная валидация
            if (field.type === 'email' && !isValidEmail(value)) {
                showFieldError(field, 'Введите корректный email адрес');
                isValid = false;
            }
            
            if (field.type === 'tel' && !isValidPhone(value)) {
                showFieldError(field, 'Введите корректный номер телефона');
                isValid = false;
            }
        }
    });
    
    return isValid;
}

function showFieldError(field, message) {
    clearFieldError(field);
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.textContent = message;
    errorDiv.style.color = '#dc3545';
    errorDiv.style.fontSize = '0.875rem';
    errorDiv.style.marginTop = '0.25rem';
    
    field.parentNode.appendChild(errorDiv);
    field.style.borderColor = '#dc3545';
}

function clearFieldError(field) {
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
    field.style.borderColor = '#e9ecef';
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function isValidPhone(phone) {
    const phoneRegex = /^[\+]?[0-9\s\-\(\)]{10,}$/;
    return phoneRegex.test(phone);
}

// Плавная прокрутка
function initSmoothScrolling() {
    const links = document.querySelectorAll('a[href^="#"]');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                e.preventDefault();
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// AJAX отправка форм
function initAjaxForms() {
    const forms = document.querySelectorAll('form[action="process-form.php"]');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
            // Показываем состояние загрузки
            submitButton.textContent = 'Отправка...';
            submitButton.disabled = true;
            
            fetch('process-form.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage(data.message, 'success');
                    form.reset();
                } else {
                    showMessage(data.errors.join('<br>'), 'error');
                }
            })
            .catch(error => {
                showMessage('Произошла ошибка при отправке формы', 'error');
                console.error('Error:', error);
            })
            .finally(() => {
                submitButton.textContent = originalText;
                submitButton.disabled = false;
            });
        });
    });
}

function showMessage(message, type) {
    // Удаляем существующие сообщения
    const existingMessages = document.querySelectorAll('.message');
    existingMessages.forEach(msg => msg.remove());
    
    const messageDiv = document.createElement('div');
    messageDiv.className = `message message-${type}`;
    messageDiv.innerHTML = message;
    
    // Стили для сообщения
    messageDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 6px;
        color: white;
        font-weight: 500;
        z-index: 10000;
        max-width: 400px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    
    if (type === 'success') {
        messageDiv.style.backgroundColor = '#28a745';
    } else {
        messageDiv.style.backgroundColor = '#dc3545';
    }
    
    document.body.appendChild(messageDiv);
    
    // Автоматическое скрытие через 5 секунд
    setTimeout(() => {
        messageDiv.remove();
    }, 5000);
}

// Инициализация AJAX форм при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    initAjaxForms();
});

// Анимации при прокрутке
function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Наблюдаем за элементами, которые должны анимироваться
    const animatedElements = document.querySelectorAll('.scent-card, .blog-post, .catalog-item, .step, .testimonial');
    
    animatedElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
}

// Инициализация анимаций при прокрутке
document.addEventListener('DOMContentLoaded', function() {
    initScrollAnimations();
});

