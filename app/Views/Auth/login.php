<?= $this->extend('Layout/Starter') ?>

<?= $this->section('content') ?>

<div id="particles-js" class="animated-bg"></div>

<div class="container min-vh-100 d-flex align-items-center justify-content-center position-relative">
    <div class="col-lg-4 col-md-6 col-sm-8">
        <?= $this->include('Layout/msgStatus') ?>
        
        <div class="card shadow-lg border-0 rounded-lg backdrop-blur">
            <div class="card-header bg-gradient-primary text-center py-4">
                <h3 class="text-white mb-0">Welcome Back</h3>
                <p class="text-light mb-0">Please login to continue</p>
            </div>
            
            <div class="card-body p-4">
                <?= form_open() ?>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="username" id="username" placeholder="Username" required minlength="4">
                    <label for="username">Username</label>
                    <?php if ($validation->hasError('username')) : ?>
                        <small class="text-danger"><?= $validation->getError('username') ?></small>
                    <?php endif; ?>
                </div>

                <div class="form-floating mb-3 position-relative">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" required minlength="6">
                    <label for="password">Password</label>
                    <i class="bi bi-eye-slash position-absolute top-50 end-0 translate-middle-y me-3 text-white toggle-password" style="cursor: pointer;" onclick="togglePassword(this)"></i>
                    <?php if ($validation->hasError('password')) : ?>
                        <small class="text-danger"><?= $validation->getError('password') ?></small>
                    <?php endif; ?>
                </div>

                <input type="hidden" name="ip" value="<?= $_SERVER['HTTP_USER_AGENT']; ?>">

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" name="stay_log" id="stay_log" value="yes">
                    <label class="form-check-label text-white" for="stay_log">Keep me logged in</label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-glow btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login
                    </button>
                </div>
                <?= form_close() ?>
            </div>
        </div>

        <div class="text-center mt-4">
            <p class="text-white mb-2">
                Need an account? 
                <a href="<?= site_url('register') ?>" class="text-white text-decoration-none hover-glow">Register here</a>
            </p>
            <p class="text-white">
                Contact Support: 
                <a href="https://telegram.me/aryanispe" class="text-white text-decoration-none hover-glow">@aryanispe</a>
            </p>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<!-- Particles.js -->
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>

<!-- Show/Hide Password -->
<script>
function togglePassword(el) {
    const input = document.getElementById("password");
    const icon = el;
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");
    } else {
        input.type = "password";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");
    }
}
</script>

<!-- Your CSS and Particles Background (no changes) -->
<style>
.animated-bg {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
    background-size: 400% 400%;
    animation: gradient 15s ease infinite;
}
@keyframes gradient {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
#particles-js {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
}
.backdrop-blur {
    background: rgba(255, 255, 255, 0.1) !important;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}
.card {
    transition: all 0.3s ease;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.3);
}
.btn-glow {
    background: linear-gradient(45deg, #ff6b6b, #ff8e53);
    border: none; color: white;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}
.btn-glow:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
}
.btn-glow::after {
    content: '';
    position: absolute;
    top: -50%; left: -50%;
    width: 200%; height: 200%;
    background: linear-gradient(transparent, rgba(255,255,255,0.3), transparent);
    transform: rotate(45deg);
    animation: shine 3s infinite;
}
@keyframes shine {
    0% { transform: translateX(-100%) rotate(45deg); }
    100% { transform: translateX(100%) rotate(45deg); }
}
.hover-glow:hover {
    text-shadow: 0 0 10px rgba(255,255,255,0.8);
}
.form-control {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: white;
}
.form-control:focus {
    background: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.5);
    color: white;
    box-shadow: 0 0 15px rgba(255,255,255,0.1);
}
.form-floating label {
    color: rgba(255, 255, 255, 0.8);
}
</style>

<script>
particlesJS('particles-js', {
  "particles": {
    "number": { "value": 80, "density": { "enable": true, "value_area": 800 }},
    "color": { "value": "#ffffff" },
    "shape": { "type": "circle" },
    "opacity": { "value": 0.5 },
    "size": { "value": 3, "random": true },
    "line_linked": { "enable": true, "distance": 150, "color": "#ffffff", "opacity": 0.4, "width": 1 },
    "move": { "enable": true, "speed": 6, "direction": "none", "random": false, "straight": false, "out_mode": "out", "bounce": false }
  },
  "interactivity": {
    "detect_on": "canvas",
    "events": {
      "onhover": { "enable": true, "mode": "repulse" },
      "onclick": { "enable": true, "mode": "push" },
      "resize": true
    }
  },
  "retina_detect": true
});
</script>

<?= $this->endSection() ?>