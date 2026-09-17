<?php
include('conn.php');
include('mail.php');

// For Credits
$sql = "SELECT * FROM credit where id=1";
$result = mysqli_query($conn, $sql);
$credit = mysqli_fetch_assoc($result);
?>

<?= $this->extend('Layout/Starter') ?>
<?= $this->section('content') ?>

<!-- Animated Background with Particles -->
<div id="particles-js" class="animated-bg"></div>

<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-lg-5">
            <?= $this->include('Layout/msgStatus') ?>
            <div class="card shadow-lg border-0 rounded-lg backdrop-blur">
                <div class="card-header bg-gradient-primary text-center py-4">
                    <h3 class="text-white mb-0">𝐑𝐄𝐆𝐈𝐒𝐓𝐄𝐑</h3>
                </div>
                
                <div class="card-body p-4">
                    <?= form_open() ?>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" name="email" id="email" 
                               placeholder="Enter Your Current Mail" minlength="13" maxlength="40" 
                               value="<?= old('email') ?>" required>
                        <label for="email">ᴇ-ᴍᴀɪʟ</label>
                        <?php if ($validation->hasError('email')) : ?>
                            <small class="text-danger"><?= $validation->getError('email') ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="username" id="username" 
                               placeholder="Your username" minlength="4" maxlength="24" 
                               value="<?= old('username') ?>" required>
                        <label for="username">ᴜsᴇʀɴᴀᴍᴇ</label>
                        <?php if ($validation->hasError('username')) : ?>
                            <small class="text-danger"><?= $validation->getError('username') ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="fullname" id="fullname" 
                               placeholder="Your fullname" minlength="4" maxlength="24" 
                               value="<?= old('fullname') ?>" required>
                        <label for="fullname">ғᴜʟʟɴᴀᴍᴇ</label>
                        <?php if ($validation->hasError('fullname')) : ?>
                            <small class="text-danger"><?= $validation->getError('fullname') ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="form-floating mb-3 position-relative">
                        <input type="password" class="form-control" name="password" id="password" 
                               placeholder="𝐸𝑛𝑡𝑒𝑟 𝑃𝑎𝑠𝑠𝑤𝑜𝑟𝑑" minlength="6" maxlength="24" required>
                        <label for="password">ᴘᴀssᴡᴏʀᴅ</label>
                        <button type="button" class="btn password-toggle" onclick="togglePassword('password', this)">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                        <?php if ($validation->hasError('password')) : ?>
                            <small class="text-danger"><?= $validation->getError('password') ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="form-floating mb-3 position-relative">
                        <input type="password" class="form-control" name="password2" id="password2" 
                               placeholder="Confirm password" minlength="6" maxlength="24" required>
                        <label for="password2">ᴄᴏɴғɪʀᴍ ᴘᴀssᴡᴏʀᴅ</label>
                        <button type="button" class="btn password-toggle" onclick="togglePassword('password2', this)">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                        <?php if ($validation->hasError('password2')) : ?>
                            <small class="text-danger"><?= $validation->getError('password2') ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="referral" id="referral" 
                               placeholder="Your Referral Code" value="<?= old('referral') ?>" maxlength="25" required>
                        <label for="referral">ʀᴇғᴇʀʀᴀʟ ᴄᴏᴅᴇ</label>
                        <?php if ($validation->hasError('referral')) : ?>
                            <small class="text-danger"><?= $validation->getError('referral') ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-glow btn-lg" onclick="popup()">
                            𝐑𝐄𝐆𝐈𝐒𝐓𝐄𝐑
                        </button>
                    </div>
                    <?= form_close() ?>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-white mb-2">
                    Already have an account? 
                    <a href="<?= site_url('login') ?>" class="text-white text-decoration-none hover-glow">Login here</a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- CSS -->
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
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
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
    border: none;
    color: white;
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
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(transparent, rgba(255,255,255,0.3), transparent);
    transform: rotate(45deg);
    animation: shine 3s infinite;
}

@keyframes shine {
    0% { transform: translateX(-100%) rotate(45deg); }
    100% { transform: translateX(100%) rotate(45deg); }
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

.password-toggle {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: transparent;
    border: none;
    color: white;
    z-index: 10;
}

.hover-glow:hover {
    text-shadow: 0 0 10px rgba(255,255,255,0.8);
}

.bg-gradient-primary {
    background: linear-gradient(45deg, #ff6b6b, #ff8e53);
}
</style>

<!-- Particles.js Script -->
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script>
particlesJS('particles-js', {
    "particles": {
        "number": { "value": 80, "density": { "enable": true, "value_area": 800 }},
        "color": { "value": "#ffffff" },
        "shape": { "type": "circle" },
        "opacity": { "value": 0.5 },
        "size": { "value": 3, "random": true },
        "line_linked": {
            "enable": true, "distance": 150, "color": "#ffffff",
            "opacity": 0.4, "width": 1
        },
        "move": {
            "enable": true, "speed": 6, "direction": "none",
            "random": false, "straight": false, "out_mode": "out", "bounce": false
        }
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

function togglePassword(inputId, btn) {
    const passwordInput = document.getElementById(inputId);
    const icon = btn.querySelector('i');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
    } else {
        passwordInput.type = 'password';
        icon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
    }
}
</script>

<?= $this->endSection() ?>