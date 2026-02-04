// Структурно правильный JavaScript модуль
const SimpleApp = (function() {
    // Приватные переменные
    let config = {};
    const colors = {};

    // Приватные функции
    function showAlert(type) {
        const color = colors[type] || '#333';
        const message = `Кнопка: ${type}\nЦвет: ${color}\nВремя: ${config.currentTime || new Date().toLocaleTimeString()}`;
        alert(message);
    }

    function showColor(colorName) {
        const color = colors[colorName];
        if (color) {
            alert(`Цвет: ${colorName}\nHEX: ${color}`);
        } else {
            alert(`Цвет "${colorName}" не найден`);
        }
    }

    function logConfig() {
        console.group('SimpleApp Configuration:');
        console.log('Version:', config.version);
        console.log('Time:', config.currentTime);
        console.log('SCSS Compiled:', config.scssCompiled);
        console.table(colors);
        console.groupEnd();
    }

    function setupEventListeners() {
        // Находим все кнопки с data-action атрибутами
        document.querySelectorAll('[data-action]').forEach(button => {
            const action = button.dataset.action;
            const color = button.dataset.color;

            button.addEventListener('click', () => {
                if (action === 'show-alert') {
                    showAlert(color || 'primary');
                } else if (action === 'show-color') {
                    showColor(color);
                }
            });
        });
    }

    // Публичные методы
    return {
        // Инициализация приложения
        init: function(userConfig) {
            config = { ...userConfig };
            Object.assign(colors, config.colors || {});

            console.log('SimpleApp initialized');
            logConfig();
            setupEventListeners();

            // Динамически добавляем информацию о SCSS переменных
            this.addSCSSInfo();
        },

        // Публичные функции для вызова из HTML
        showAlert: showAlert,
        showColor: showColor,

        // Добавляем информацию о SCSS переменных в футер
        addSCSSInfo: function() {
            const footer = document.querySelector('footer');
            if (footer) {
                const infoDiv = document.createElement('div');
                infoDiv.className = 'scss-vars-info';
                infoDiv.style.cssText = 'margin-top: 10px; padding: 10px; background: rgba(255,255,255,0.1); font-size: 12px;';
                infoDiv.innerHTML = `
                    <strong>SCSS Variables Loaded:</strong><br>
                    • $colors map with ${Object.keys(colors).length} colors<br>
                    • $spacing: small(10px), medium(20px), large(30px)<br>
                    • $font-sizes: small(14px), normal(16px), large(24px)
                `;
                footer.appendChild(infoDiv);
            }
        },

        // Утилиты
        getColor: function(name) {
            return colors[name];
        },

        getConfig: function() {
            return { ...config };
        }
    };
})();

// Экспортируем для глобального доступа
window.SimpleApp = SimpleApp;

// Автоматическая инициализация при загрузке DOM
document.addEventListener('DOMContentLoaded', function() {
    // Если конфиг не передан из Smarty, используем значения по умолчанию
    if (!window.SimpleAppInitialized) {
        SimpleApp.init({
            version: '1.0.0',
            currentTime: new Date().toLocaleTimeString(),
            scssCompiled: new Date().toISOString().split('T')[0],
            colors: {
                primary: '#2c3e50',
                accent: '#e74c3c',
                success: '#27ae60',
                warning: '#f39c12',
                danger: '#c0392b'
            }
        });
    }
});