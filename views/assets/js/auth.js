import UserModule from './user.js';

export default (() => {
    const init = () => {

        const form = document.getElementById("login-form");

        form.addEventListener('submit', (event) => {
            event.preventDefault();

            clearErrorMessages();

            const formData = new FormData(event.target);

            const validationRules = {
                "name": ['required', 'min:3'],
                "email": ['required', 'min:6']
            };

            let isValid = true;
            for (const [inputName, rules] of Object.entries(validationRules)) {
                let element = form.querySelector(`[name="${inputName}"]`);
                isValid = validateInput(formData, inputName, rules, element) && isValid;
            }

            if (isValid) {

                const submitButton = form.querySelector('.card__action');

                submitButton.setAttribute('disabled', 'disabled');

                UserModule.authenticate(Object.fromEntries(formData)).then((response) => {

                    if (response.data.success) {
                        // Set the token as a cookie with an expiration of 1 day
                        document.cookie = `token=${response.data.token}; expires=${new Date(Date.now() + 3000000).toUTCString()}; path=/`;

                        window.location.href = 'dashboard.html';

                        return;
                    }

                    submitButton.removeAttribute('disabled');
                    const span = createErrorElement("login");

                    span.textContent = response.data.error;

                    submitButton.after(span)
                })

            }
        })
    }

    const createErrorElement = (name) => {
        let errorElement = document.getElementById(`${name}-error`);

        if (!errorElement) {
            errorElement = document.createElement("span");
            errorElement.classList.add("error-message");
            errorElement.id = `${name}-error`;
        }

        return errorElement;
    }

    function validateInput(formData, inputName, rules, errorElement) {
        const inputValue = formData.get(inputName);

        for (const rule of rules) {
            const [ruleName, ruleValue] = rule.split(':');

            switch (ruleName) {
                case 'required':
                    if (inputValue.trim() === '') {
                        displayErrorMessage(errorElement, `Field is required.`, inputName);
                        return false;
                    }
                    break;
                case 'min':
                    if (inputValue.length < parseInt(ruleValue, 10)) {
                        displayErrorMessage(errorElement, `Field must be at least ${ruleValue} characters long.`, inputName);
                        return false;
                    }
                    break;
            }
        }

        return true;
    }

    const displayErrorMessage = (element, message, inputName) => {
        const span = createErrorElement(`${inputName}`);
        element.after(span);

        span.textContent = message;
    };

    const clearErrorMessages = () => {
        const errorElements = document.querySelectorAll(".error-message");
        errorElements.forEach((element) => {
            element.textContent = "";
        });
    }

    return {
        init
    }
})();
