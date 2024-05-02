document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const actionResult = urlParams.get('action');

    switch (actionResult) {
        case 'login-success':
            Swal.fire({
                title: 'Success!',
                text: 'You have logged in successfully!',
                icon: 'success',
                background: '#FFFFFF', // --accent-color
                color: '#000000', // --secondary-color
                confirmButtonColor: '#FF6A00', // --primary-color
                confirmButtonText: 'Ok'
            });
            break;
        case 'login-failed':
            Swal.fire({
                title: 'Failed!',
                text: 'Login failed. Please try again!',
                icon: 'error',
                background: '#FFFFFF',
                color: '#000000',
                confirmButtonColor: '#FF6A00',
                confirmButtonText: 'Ok'
            });
            break;
        case 'logout-success':
            Swal.fire({
                title: 'Logged Out!',
                text: 'You have successfully logged out.',
                icon: 'info',
                background: '#FFFFFF',
                color: '#000000',
                confirmButtonColor: '#FF6A00',
                confirmButtonText: 'Ok'
            });
            break;
        case 'register-success':
            Swal.fire({
                title: 'Registration Successful!',
                text: 'You are now registered and logged in.',
                icon: 'success',
                background: '#FFFFFF',
                color: '#000000',
                confirmButtonColor: '#FF6A00',
                confirmButtonText: 'Ok'
            });
            break;
        case 'register-failed':
            Swal.fire({
                title: 'Registration Failed!',
                text: 'Please try registering again.',
                icon: 'error',
                background: '#FFFFFF',
                color: '#000000',
                confirmButtonColor: '#FF6A00',
                confirmButtonText: 'Ok'
            });
            break;
        case 'code-not-found':
            Swal.fire({
                title: 'Question with this code doesnt exist!',
                text: 'Please enter a valid code.',
                icon: 'error',
                background: '#FFFFFF',
                color: '#000000',
                confirmButtonColor: '#FF6A00',
                confirmButtonText: 'Ok'
            });
            break;
        case 'username-taken':
            Swal.fire({
                title: 'Username already exist!',
                text: 'Please enter a valid code.',
                icon: 'error',
                background: '#FFFFFF',
                color: '#000000',
                confirmButtonColor: '#FF6A00',
                confirmButtonText: 'Ok'
            });
            break;
        case 'backup_noneed':
            Swal.fire({
                title: 'Backup',
                text: 'No backup is required.',
                icon: 'info',
                background: '#FFFFFF',
                color: '#000000',
                confirmButtonColor: '#FF6A00',
                confirmButtonText: 'Ok'
            });
            break;
        case 'backup_ok':
            Swal.fire({
                title: 'Backup',
                text: 'backup has been created.',
                icon: 'success',
                background: '#FFFFFF',
                color: '#000000',
                confirmButtonColor: '#FF6A00',
                confirmButtonText: 'Ok'
            });
            break;
    }
});

