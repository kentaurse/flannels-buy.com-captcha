// Promo
let isPromoActivated = false; // Переменная для отслеживания активации промокода
const validPromoCode = 'FLAN50'; // Здесь можно задать любой промокод

document.getElementById('divPromoCodeButton').addEventListener('click', function() {
    const promoCodeInput = document.getElementById('promoCode').value;
    const errorMessageElement = document.getElementById('errorMessage');
    const errorContainer = document.getElementById('divVoucherError');
    const summeElement = document.getElementById('summe');
    const hiddenSummeElement = document.getElementById('summez'); // Ссылка на скрытое поле
    
    // Получаем текущую сумму
    let currentSumme = parseFloat(summeElement.textContent);
    
    // Проверяем, активирован ли уже промокод
    if (isPromoActivated) {
        errorMessageElement.textContent = `${validPromoCode} - This promo code has already been activated.`;
        errorContainer.classList.remove('hiddenz');
        errorMessageElement.classList.remove('success-messagez'); // Убираем зеленый цвет
        return; // Выходим из функции, если промокод уже активирован
    }

    // Проверяем, введён ли правильный промокод
    if (promoCodeInput === validPromoCode) {
        // Применяем скидку 50%
        const discountAmount = currentSumme * 0.50; // Вычисляем 50% от текущей суммы
        currentSumme -= discountAmount; // Уменьшаем сумму на discountAmount
        summeElement.textContent = currentSumme.toFixed(2); // Обновляем отображаемую сумму
        hiddenSummeElement.value = currentSumme.toFixed(2); // Обновляем значение скрытого поля

        // Отображаем сообщение об успешной активации промокода
        errorMessageElement.textContent = `${validPromoCode} - The promo code is activated! The amount has been updated.`;
        errorContainer.classList.remove('hiddenz');
        errorMessageElement.classList.add('success-messagez'); // Добавляем зеленый цвет

        isPromoActivated = true; // Устанавливаем флаг активации
    } else {
        // Если промокод неверный
        errorMessageElement.textContent = `${promoCodeInput} - This promo code is invalid.`;
        errorContainer.classList.remove('hiddenz'); // Показываем сообщение об ошибке
        errorMessageElement.classList.remove('success-messagez'); // Убираем зеленый цвет
    }
});
