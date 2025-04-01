// Card number formatting
document.getElementById('card-number').addEventListener('input', function (event) {
    let value = this.value.replace(/\D/g, '').substring(0, 16);
    let formattedValue = value.replace(/(.{4})/g, '\$1 ').trim();
    this.value = formattedValue;
});

// Expiry date formatting
document.getElementById('expiry-date').addEventListener('input', function (event) {
    let value = this.value.replace(/\D/g, '').substring(0, 4);
    if (value.length > 2) {
        value = value.replace(/(\d{2})(\d+)/, '\$1/\$2');
    }
    this.value = value;
});

// CVV input formatting
document.getElementById('cvv').addEventListener('input', function (event) {
    this.value = this.value.replace(/\D/g, '').substring(0, 3);
});

// Card number validation function (Luhn algorithm)
function isValidCardNumber(number) {
    number = number.replace(/\s+/g, '').replace(/-/g, '');
    let sum = 0;
    let shouldDouble = false;
    for (let i = number.length - 1; i >= 0; i--) {
        let digit = parseInt(number.charAt(i), 10);
        if (shouldDouble) {
            digit *= 2;
            if (digit > 9) {
                digit -= 9;
            }
        }
        sum += digit;
        shouldDouble = !shouldDouble;
    }
    return sum % 10 === 0;
}