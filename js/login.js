const formPopup = document.querySelector(".form-popup");
const showPopupBtn = document.querySelector(".login-btn");
const hidePopupBtn = formPopup.querySelector(".close-btn");
const signupLoginLink = formPopup.querySelectorAll(".bottom-link a");
const loginForm = document.getElementById('loginForm');
const signupForm = document.getElementById('signupForm');

if (showPopupBtn) {
    showPopupBtn.addEventListener("click", () => {
        document.body.classList.add("show-popup");
    });
}

hidePopupBtn.addEventListener("click", () => {
    document.body.classList.remove("show-popup");
});

signupLoginLink.forEach(link => {
    link.addEventListener("click", (e) => {
        e.preventDefault();
        
        if (link.textContent.includes("Sign Up")) {
            loginForm.style.display = 'none';
            signupForm.style.display = 'block';
        } else if (link.textContent.includes("Login")) {
            signupForm.style.display = 'none';
            loginForm.style.display = 'block';
        }
    });
});

document.getElementById('loginFormAjax').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    
    const formData = new FormData();
    formData.append('email', email);
    formData.append('password', password);
    formData.append('signIn', true);
    
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'register.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = xhr.responseText;
            if (response === 'Success') {
                window.location.href = 'index.php';
            } else {
                document.getElementById('loginError').textContent = response;
                document.getElementById('loginError').style.display = 'block';
            }
        }
    };
    xhr.send(formData);
});

document.getElementById('signupFormAjax').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const username = document.getElementById('signupUsername').value;
    const email = document.getElementById('signupEmail').value;
    const password = document.getElementById('signupPassword').value;
    
    const formData = new FormData();
    formData.append('username', username);
    formData.append('email', email);
    formData.append('password', password);
    formData.append('signUp', true);
    
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'register.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = xhr.responseText;
            if (response === 'Success') {
                window.location.href = 'index.php';
            } else {
                document.getElementById('signupError').textContent = response;
                document.getElementById('signupError').style.display = 'block';
            }
        }
    };
    xhr.send(formData);
});
