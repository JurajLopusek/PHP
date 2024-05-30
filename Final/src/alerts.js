
function updateContent(langData) {
    document.querySelectorAll('[localize]').forEach(element => {
        const key = element.getAttribute('localize');
        element.textContent = langData[key];
    });
}

function setLanguagePreference(lang) {
    localStorage.setItem('language', lang);
    location.reload();
}

async function fetchLanguageData(lang) {
    const response = await fetch(`language/${lang}.json`);
    return response.json();
}

async function changeLanguage(lang) {
    await setLanguagePreference(lang);

    const langData = await fetchLanguageData(lang);
    updateContent(langData);
}

var usedLanguage;

document.addEventListener('DOMContentLoaded', async () => {
    const userPreferredLanguage = localStorage.getItem('language') || 'en';
    const langData = await fetchLanguageData(userPreferredLanguage);
    usedLanguage = langData;
    updateContent(langData);

    const urlParams = new URLSearchParams(window.location.search);
    const actionResult = urlParams.get('action');

    function getLocalizedErrorMessage(key) {
        return usedLanguage[key] || '';
    }

    switch (actionResult) {

        case 'login-success':
            Swal.fire({
                title: getLocalizedErrorMessage("success"),
                text: getLocalizedErrorMessage("logged_in"),
                icon: 'success',
                background: '#FFFFFF', // --accent-color
                color: '#000000', // --secondary-color
                confirmButtonColor: '#FF6A00', // --primary-color
                confirmButtonText: 'Ok'
            });
            break;
        case 'login-failed':
            Swal.fire({
                title: getLocalizedErrorMessage("failed"),
                text: getLocalizedErrorMessage("failed_login"),
                icon: 'error',
                background: '#FFFFFF',
                color: '#000000',
                confirmButtonColor: '#FF6A00',
                confirmButtonText: 'Ok'
            });
            break;
        case 'logout-success':
            Swal.fire({
                title: getLocalizedErrorMessage("logged_out"),
                text: getLocalizedErrorMessage("logged_out_s"),
                icon: 'info',
                background: '#FFFFFF',
                color: '#000000',
                confirmButtonColor: '#FF6A00',
                confirmButtonText: 'Ok'
            });
            break;
        case 'register-success':
            Swal.fire({
                title: getLocalizedErrorMessage("signup_success"),
                text: getLocalizedErrorMessage("signedup_end_logedin"),
                icon: 'success',
                background: '#FFFFFF',
                color: '#000000',
                confirmButtonColor: '#FF6A00',
                confirmButtonText: 'Ok'
            });
            break;
        case 'register-failed':
            Swal.fire({
                title: getLocalizedErrorMessage("signup_failed"),
                text: getLocalizedErrorMessage("signup_again"),
                icon: 'error',
                background: '#FFFFFF',
                color: '#000000',
                confirmButtonColor: '#FF6A00',
                confirmButtonText: 'Ok'
            });
            break;
        case 'code-not-found':
            Swal.fire({
                title: getLocalizedErrorMessage("q_does_not_exists"),
                text: getLocalizedErrorMessage("eneter_code"),
                icon: 'error',
                background: '#FFFFFF',
                color: '#000000',
                confirmButtonColor: '#FF6A00',
                confirmButtonText: 'Ok'
            });
            break;
        case 'ques-not-active':
            Swal.fire({
                title: getLocalizedErrorMessage("q_not_active"),
                text: getLocalizedErrorMessage("eneter_code"),
                icon: 'error',
                background: '#FFFFFF',
                color: '#000000',
                confirmButtonColor: '#FF6A00',
                confirmButtonText: 'Ok'
            });
            break;
        case 'ques-copy-success':
            Swal.fire({
                title: getLocalizedErrorMessage("q_copy"),
                text: getLocalizedErrorMessage("q_suc_copy"),
                icon: 'success',
                background: '#FFFFFF',
                color: '#000000',
                confirmButtonColor: '#FF6A00',
                confirmButtonText: 'Ok'
            });
            break;
        case 'ques-update-success':
            Swal.fire({
                title: getLocalizedErrorMessage("q_update"),
                text: getLocalizedErrorMessage("q_suc_up"),
                icon: 'success',
                background: '#FFFFFF',
                color: '#000000',
                confirmButtonColor: '#FF6A00',
                confirmButtonText: 'Ok'
            });
            break;
        case 'ques-delete-success':
            Swal.fire({
                title: getLocalizedErrorMessage("q_delete"),
                text: getLocalizedErrorMessage("q_suc_del"),
                icon: 'success',
                background: '#FFFFFF',
                color: '#000000',
                confirmButtonColor: '#FF6A00',
                confirmButtonText: 'Ok'
            });
            break;
        case 'username-taken':
            Swal.fire({
                title: getLocalizedErrorMessage("username_exists"),
                text: getLocalizedErrorMessage("eneter_code"),
                icon: 'error',
                background: '#FFFFFF',
                color: '#000000',
                confirmButtonColor: '#FF6A00',
                confirmButtonText: 'Ok'
            });
            break;
        case 'backup_noneed':
            Swal.fire({
                title: getLocalizedErrorMessage("back_up"),
                text: getLocalizedErrorMessage("no_backup"),
                icon: 'info',
                background: '#FFFFFF',
                color: '#000000',
                confirmButtonColor: '#FF6A00',
                confirmButtonText: 'Ok'
            });
            break;
        case 'backup_ok':
            Swal.fire({
                title: getLocalizedErrorMessage("back_up"),
                text: getLocalizedErrorMessage("backedup"),
                icon: 'success',
                background: '#FFFFFF',
                color: '#000000',
                confirmButtonColor: '#FF6A00',
                confirmButtonText: 'Ok'
            });
            break;
    }
});

