document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.button');

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            if (button.classList.contains('active')) {
                window.location.href = 'dashbord.php';
            } else {
                buttons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                if (button.id === 'button') {
                    window.location.href = 'dashbord.php';
                } else if (button.id === 'button1') {
                    window.location.href = 'dashbord2.php';
                } else if (button.id === 'button2') {
                    window.location.href = 'dashbord3.php';
                } else if (button.id === 'button3') {
                    window.location.href = 'dashbord4.php';
                }
            }
        });
    });
});
