
    document.addEventListener('DOMContentLoaded', function () {
        const inputs = document.querySelectorAll('input');

        inputs.forEach((input, index) => {
            input.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); // Stop form from submitting
                    const nextInput = inputs[index + 1];
                    if (nextInput) {
                        nextInput.focus();
                    }
                }
            });
        });
    });
