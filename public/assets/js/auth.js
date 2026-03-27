/**
 * Elo 42 — Auth JS
 */
document.addEventListener('DOMContentLoaded', function() {

    // --- Password toggle ---
    document.querySelectorAll('[data-toggle-password]').forEach(button => {
        button.addEventListener('click', function() {
            const inputId = this.dataset.togglePassword;
            const input = document.getElementById(inputId);

            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                this.textContent = 'Ocultar';
            } else {
                input.type = 'password';
                this.textContent = 'Mostrar';
            }
        });
    });

    // --- Phone mask (basic) ---
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');

            if (value.length > 11) {
                value = value.substring(0, 11);
            }

            if (value.length > 6) {
                this.value = '(' + value.substring(0, 2) + ') ' + value.substring(2, 7) + '-' + value.substring(7);
            } else if (value.length > 2) {
                this.value = '(' + value.substring(0, 2) + ') ' + value.substring(2);
            } else if (value.length > 0) {
                this.value = '(' + value;
            }
        });
    });

    // --- CNPJ mask ---
    const cnpjInput = document.getElementById('org_document');
    if (cnpjInput) {
        cnpjInput.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');

            if (value.length > 14) {
                value = value.substring(0, 14);
            }

            if (value.length > 12) {
                this.value = value.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{1,2})/, '$1.$2.$3/$4-$5');
            } else if (value.length > 8) {
                this.value = value.replace(/^(\d{2})(\d{3})(\d{3})(\d{1,4})/, '$1.$2.$3/$4');
            } else if (value.length > 5) {
                this.value = value.replace(/^(\d{2})(\d{3})(\d{1,3})/, '$1.$2.$3');
            } else if (value.length > 2) {
                this.value = value.replace(/^(\d{2})(\d{1,3})/, '$1.$2');
            }
        });
    }

    // --- Password strength indicator ---
    const passwordInput = document.getElementById('password');
    if (passwordInput && passwordInput.closest('.auth-form')) {
        const strengthBar = document.createElement('div');
        strengthBar.className = 'password-strength';
        strengthBar.innerHTML = '<div class="password-strength__bar"><div class="password-strength__fill"></div></div><span class="password-strength__text"></span>';
        passwordInput.closest('.form-password').after(strengthBar);

        passwordInput.addEventListener('input', function() {
            const value = this.value;
            let strength = 0;
            let text = '';

            if (value.length >= 8) strength++;
            if (value.length >= 12) strength++;
            if (/[A-Z]/.test(value)) strength++;
            if (/[0-9]/.test(value)) strength++;
            if (/[^A-Za-z0-9]/.test(value)) strength++;

            const fill = strengthBar.querySelector('.password-strength__fill');
            const label = strengthBar.querySelector('.password-strength__text');

            const levels = [
                { width: '0%',   color: '#ccc',     text: '' },
                { width: '20%',  color: '#EF4444',  text: 'Muito fraca' },
                { width: '40%',  color: '#F59E0B',  text: 'Fraca' },
                { width: '60%',  color: '#F59E0B',  text: 'Razoável' },
                { width: '80%',  color: '#10B981',  text: 'Boa' },
                { width: '100%', color: '#059669',  text: 'Forte' },
            ];

            const level = levels[strength] || levels[0];
            fill.style.width = level.width;
            fill.style.background = level.color;
            label.textContent = value.length > 0 ? level.text : '';
            label.style.color = level.color;
        });
    }

    // --- Form submit loading state ---
    document.querySelectorAll('.auth-form form').forEach(form => {
        form.addEventListener('submit', function() {
            const btn = this.querySelector('.auth-form__submit');
            if (btn) {
                btn.disabled = true;
                btn.style.opacity = '0.7';
                btn.textContent = 'Processando...';
            }
        });
    });

});
