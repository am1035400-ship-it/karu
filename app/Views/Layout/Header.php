<header>
  <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm align-middle" role="alert" style="background: linear-gradient(0.9turn, #FF90BB, #8ACCD5, #FF90BB);">
        <div class="container px-3">
            <a class="navbar-brand" href="<?= site_url() ?>"><i class="bi bi-box text-danger px-2"></i><?= BASE_NAME ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <?php if (session()->has('userid')) : ?>
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('keys') ?>" style="color: #FFFDF6; font-weight: 500;"><i class="bi bi-key me-1"></i>Keys</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('keys/generate') ?>" style="color: #FFFDF6; font-weight: 500;"><i class="bi bi-plus-circle me-1"></i>Generate</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('recharge') ?>" style="color: #FFFDF6; font-weight: 500;"><i class="bi bi-wallet2 me-1"></i>Add Balance</a>
                        </li>
                    </ul>
                    <div class="float-right">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person-circle pe-2"></i><?= getName($user) ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start fixed-dropdown" aria-labelledby="navbarDropdown">
                                    <li>
                                        <a class="dropdown-item text-danger" href="<?= site_url('settings') ?>">
                                            <i class="bi bi-gear"></i> Settings
                                        </a>
                                    </li>
                                   
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                        <?php if (($user->level == 1) || ($user->level ==2)) : ?>
                                     
                                        
                                          <li>
                                        <a class="dropdown-item text-danger" href="<?= site_url('Server') ?>">
                                            <i class="bi-controller"></i> Online System
                                        </a>
                                    </li>
                                        
                                        <li>
                                            <a class="dropdown-item text-danger" href="<?= site_url('admin/manage-users') ?>">
                                                <i class="bi bi-person-check"></i> manage Users
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="<?= site_url('admin/create-referral') ?>">
                                                <i class="bi bi-person-plus"></i> Create Referral
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                    <?php endif; ?>
                                    <li>
                                        <a class="dropdown-item text-danger" href="<?= site_url('logout') ?>">
                                            <i class="bi bi-box-arrow-in-left"></i> Logout
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
            </div>
        <?php endif; ?>

        </div>
    </nav>
</header>

<style>
    .fixed-dropdown {
        z-index: 9999 !important;
        position: absolute !important;
        background: linear-gradient(0.9turn, #FF90BB, #8ACCD5, #FF90BB) !important;
        border: 1px solid #FF90BB !important;
        box-shadow: 0 5px 15px rgba(0,0,0,0.5) !important;
        border-radius: 20px;
        
    }
   
    .dropdown-item:hover {
        background-color: #8ACCD5 !important;
    }
    .ul{
        color:white;
    }
    }
</style>