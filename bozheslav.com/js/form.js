export function initForm() {
    const form = document.getElementById("contactForm");
    if (!form) return;

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const btn = form.querySelector('[type="submit"]');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = 'Отправляю...';

        try {
            const response = await fetch('/contacts', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        ?? document.querySelector('input[name="_token"]')?.value,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    name:     form.querySelector('[name="name"]').value,
                    email:    form.querySelector('[name="email"]').value,
                    subject:  form.querySelector('[name="subject"]').value,
                    message:  form.querySelector('[name="message"]').value,
                    honeypot: form.querySelector('[name="honeypot"]')?.value ?? '',
                }),
            });

            if (response.ok) {
                form.reset();
                showMessage(form, 'Сообщение отправлено! Я свяжусь с вами в ближайшее время.', 'success');
            } else {
                const data = await response.json();
                const errors = data.errors ? Object.values(data.errors).flat().join(' ') : 'Ошибка отправки. Попробуйте ещё раз.';
                showMessage(form, errors, 'error');
            }
        } catch {
            showMessage(form, 'Ошибка соединения. Проверьте интернет и попробуйте снова.', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    });
}

function showMessage(form, text, type) {
    const existing = form.querySelector('.form-message');
    if (existing) existing.remove();

    const msg = document.createElement('p');
    msg.className = 'form-message';
    msg.textContent = text;
    msg.style.cssText = `
        margin-top: 0.75rem;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.9rem;
        background: ${type === 'success' ? '#d1fae5' : '#fee2e2'};
        color: ${type === 'success' ? '#065f46' : '#991b1b'};
    `;
    form.appendChild(msg);

    setTimeout(() => msg.remove(), 5000);
}