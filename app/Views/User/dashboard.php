<?php

include('conn.php');
include('mail.php');
include('UserMail.php');

// For Credits
$sql = "SELECT * FROM credit where id=1";
$result = mysqli_query($conn, $sql);
$credit = mysqli_fetch_assoc($result);

// For Keys count
$sql = "SELECT COUNT(*) as id_keys FROM keys_code";
$result = mysqli_query($conn, $sql);
$keycount = mysqli_fetch_assoc($result);

// For Active Keys count
$sql = "SELECT COUNT(devices) as devices FROM keys_code";
$result = mysqli_query($conn, $sql);
$active = mysqli_fetch_assoc($result);

// For In-Active Keys Count
$sql = "SELECT COUNT(*) as devices FROM keys_code where devices IS NULL";
$result = mysqli_query($conn, $sql);
$inactive = mysqli_fetch_assoc($result);

// For Users Count
$sql = "SELECT COUNT(*) as id_users FROM users";
$result = mysqli_query($conn, $sql);
$users = mysqli_fetch_assoc($result);

$userid = session()->userid;
$sql = "SELECT `expiration_date` FROM `users` WHERE `id_users` = '".$userid."'";
$query = mysqli_query($conn, $sql);
$period = mysqli_fetch_assoc($query);

function HoursToDays($value)
{
    if($value == 1) {
       return "$value Hour";
    } else if($value >= 2 && $value < 24) {
       return "$value Hours";
    } else if($value == 24) {
       $darkespyt = $value/24;
       return "$darkespyt Day";
    } else if($value > 24) {
       $darkespyt = $value/24;
       return "$darkespyt Days";
    }
}

$dateTime = strtotime($period['expiration_date']);
$getDateTime = date("F d, Y H:i:s", $dateTime);
?>

<?= $this->extend('Layout/Starter') ?>
<?= $this->section('content') ?>
<style>
    @import 'https://fonts.googleapis.com/css?family=Nova+Mono|Eczar';
    @import 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap';
    
    body {
        font-family: 'Poppins', sans-serif;
    }
    
    #exp {
      font-family: 'Nova Mono', monospace;
      text-align: center;
      font-size: 3vw;
      text-shadow: 0px 0px 20px blue;
    }
    
    .dashboard-card {
        border-radius: 15px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
        overflow: hidden;
        margin-bottom: 20px;
        background: white;
    }
    
    .dashboard-card:hover {
        transform: translateY(-5px);
    }
    
    .card-icon {
        font-size: 24px;
        margin-right: 10px;
    }
    
    .stat-card {
        padding: 20px;
        border-radius: 15px;
        color: #333;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card.purple {
        background-color: #f0e6ff;
    }
    
    .stat-card.pink {
        background-color: #ffe6f2;
    }
    
    .stat-card.blue {
        background-color: #e6f2ff;
    }
    
    .stat-card.yellow {
        background-color: #fff8e6;
    }
    
    .stat-value {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .stat-label {
        font-size: 14px;
        opacity: 0.8;
    }
    
    .stat-trend {
        position: absolute;
        right: 20px;
        bottom: 20px;
        font-size: 12px;
        padding: 3px 8px;
        border-radius: 10px;
    }
    
    .trend-up {
        background-color: #e6fff0;
        color: #00b341;
    }
    
    .trend-down {
        background-color: #ffe6e6;
        color: #ff3333;
    }
    
    .chart-container {
        height: 200px;
        margin-top: 20px;
    }
    
    .bar-chart {
        display: flex;
        align-items: flex-end;
        height: 150px;
        gap: 15px;
        padding: 0 10px;
    }
    
    .bar {
        flex: 1;
        background: linear-gradient(to top, #6c5ce7, #a29bfe);
        border-radius: 8px 8px 0 0;
        position: relative;
        min-height: 20px;
        transition: height 0.5s ease;
    }
    
    .bar:nth-child(2n) {
        background: linear-gradient(to top, #fd79a8, #fab1c9);
    }
    
    .bar:nth-child(3n) {
        background: linear-gradient(to top, #0984e3, #74b9ff);
    }
    
    .bar-label {
        position: absolute;
        bottom: -25px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 12px;
        white-space: nowrap;
    }
    
    @media only screen and (max-width: 768px){
      #exp {font-size: 5vw;}
      .stat-value {font-size: 22px;}
    }
</style>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-12">
            <?= $this->include('Layout/msgStatus') ?>
        </div>
        
        <!-- Stats Cards Row -->
        <div class="col-md-3 col-sm-6">
            <div class="stat-card purple">
                <div class="d-flex align-items-center">
                    <i class="fas fa-key card-icon"></i>
                    <div>
                        <div class="stat-value"><?php echo $keycount['id_keys']; ?></div>
                        <div class="stat-label">Total Keys</div>
                    </div>
                </div>
                <div class="stat-trend trend-up">+5.2%</div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6">
            <div class="stat-card pink">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle card-icon"></i>
                    <div>
                        <div class="stat-value"><?php echo $active['devices']; ?></div>
                        <div class="stat-label">Used Keys</div>
                    </div>
                </div>
                <div class="stat-trend trend-up">+3.1%</div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6">
            <div class="stat-card blue">
                <div class="d-flex align-items-center">
                    <i class="fas fa-times-circle card-icon"></i>
                    <div>
                        <div class="stat-value"><?php echo $inactive['devices']; ?></div>
                        <div class="stat-label">Unused Keys</div>
                    </div>
                </div>
                <div class="stat-trend trend-down">-2.4%</div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6">
            <div class="stat-card yellow">
                <div class="d-flex align-items-center">
                    <i class="fas fa-users card-icon"></i>
                    <div>
                        <div class="stat-value"><?php echo $users['id_users']; ?></div>
                        <div class="stat-label">Total Users</div>
                    </div>
                </div>
                <div class="stat-trend trend-up">+7.8%</div>
            </div>
        </div>
        
        <!-- Expiration Card -->
        <div class="col-lg-8 col-md-12 mt-4">
            <div class="dashboard-card">
                <div class="card-header text-center text-white" style="background: linear-gradient(0.9turn, #FF90BB, #8ACCD5, #FF90BB);">
                    <i class="fas fa-clock mr-2"></i> Subscription Expiration
                </div>
                <div class="card-body">
                    <p id="exp"></p>
                    
                    <!-- Activity Chart -->
                    <div class="chart-container mt-4">
                        <h5 class="text-center mb-3">Activity Overview</h5>
                        <div class="bar-chart">
                            <div class="bar" style="height: 40%;">
                                <span class="bar-label">Mon</span>
                            </div>
                            <div class="bar" style="height: 65%;">
                                <span class="bar-label">Tue</span>
                            </div>
                            <div class="bar" style="height: 85%;">
                                <span class="bar-label">Wed</span>
                            </div>
                            <div class="bar" style="height: 55%;">
                                <span class="bar-label">Thu</span>
                            </div>
                            <div class="bar" style="height: 70%;">
                                <span class="bar-label">Fri</span>
                            </div>
                            <div class="bar" style="height: 30%;">
                                <span class="bar-label">Sat</span>
                            </div>
                            <div class="bar" style="height: 20%;">
                                <span class="bar-label">Sun</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- User Information Card -->
        <div class="col-lg-4 col-md-12 mt-4">
            <div class="dashboard-card">
                <div class="card-header text-center text-white" style="background: linear-gradient(0.9turn, #FF90BB, #8ACCD5, #FF90BB);">
                    <i class="fas fa-user-circle mr-2"></i> User Information
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div style="width: 80px; height: 80px; background-color: #e6e6ff; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                            <i class="fas fa-user" style="font-size: 40px; color: #6c5ce7;"></i>
                        </div>
                        <h5 class="mb-0"><?= $user->username ?? 'User' ?></h5>
                        <small class="text-muted"><?= getLevel($user->level) ?></small>
                    </div>
                    
                    <ul class="list-group list-hover mb-3">
                        <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-user-tag mr-2"></i> Role</span>
                            <span class="badge bg-light text-dark">
                                <?= getLevel($user->level) ?>
                            </span>
                        </li>
                        <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-wallet mr-2"></i> Balance</span>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success text-white fw-bold">
                                    ₹<?= number_format((float)($user->saldo ?? 0), 2) ?>
                                </span>
                                <a href="<?= site_url('recharge') ?>" class="btn btn-sm btn-primary py-0 px-2" style="border-radius: 20px; font-size: 11px; font-weight: 600;">+ Add</a>
                            </div>
                        </li>
                        <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-sign-in-alt mr-2"></i> Login Time</span>
                            <span class="badge bg-light text-dark">
                                <?= $time::parse(session()->time_since)->humanize() ?>
                            </span>
                        </li>
                        <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-sign-out-alt mr-2"></i> Auto Log-out</span>
                            <span class="badge bg-light text-dark">
                                <?= $time::now()->difference($time::parse(session()->time_login))->humanize() ?>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- History Card -->
        <div class="col-lg-12 mt-4">
            <div class="dashboard-card">
                <div class="card-header text-center text-white" style="background: linear-gradient(0.9turn, #FF90BB, #8ACCD5, #FF90BB);">
                    <i class="fas fa-history mr-2"></i> Transaction History
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover text-center">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Type</th>
                                    <th>Key</th>
                                    <th>Duration</th>
                                    <th>Devices</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($history as $h) : ?>
                                    <?php if ($h->keys_id === 'RECHARGE' || strpos($h->info, '|') === false) : ?>
                                        <tr>
                                            <td><span class="badge bg-light text-dark">#3812<?= $h->id_history ?></span></td>
                                            <td><span class="badge bg-success text-white"><i class="fas fa-wallet me-1"></i> Recharge</span></td>
                                            <td colspan="3" class="text-center font-monospace" style="font-size: 12.5px;"><?= esc($h->info) ?></td>
                                            <td><i class="badge bg-light text-muted"><?= $time::parse($h->created_at)->humanize() ?></i></td>
                                        </tr>
                                    <?php else : ?>
                                        <?php $in = explode("|", $h->info); ?>
                                        <tr>
                                            <td><span class="badge bg-light text-dark">#3812<?= $h->id_history ?></span></td>
                                            <td><?= esc($in[0] ?? '-') ?></td>
                                            <td><span class="badge bg-light text-dark"><?= esc($in[1] ?? '-') ?>**</span></td>
                                            <td><span class="badge bg-light text-dark"><?= isset($in[2]) ? HoursToDays($in[2]) : '-' ?></span></td>
                                            <td><span class="badge bg-primary"><?= esc($in[3] ?? '1') ?> Devices</span></td>
                                            <td><i class="badge bg-light text-muted"><?= $time::parse($h->created_at)->humanize() ?></i></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Required scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
<script>
    var countDownTimer = new Date("<?php echo "$getDateTime"; ?>").getTime();
    // Update the count down every 1 second
    var interval = setInterval(function() {
        var current = new Date().getTime();
        // Find the difference between current and the count down date
        var diff = countDownTimer - current;
        // Countdown Time calculation for days, hours, minutes and seconds
        var days = Math.floor(diff / (1000 * 60 * 60 * 24));
        var hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((diff % (1000 * 60)) / 1000);

        document.getElementById("exp").innerHTML = days + "Day : " + hours + "h " +
        minutes + "m " + seconds + "s ";
        // Display Expired, if the count down is over
        if (diff < 0) {
            clearInterval(interval);
            document.getElementById("exp").innerHTML = "EXPIRED";
        }
    }, 1000);
    
    // Animate bars on load
    document.addEventListener('DOMContentLoaded', function() {
        const bars = document.querySelectorAll('.bar');
        bars.forEach(bar => {
            const originalHeight = bar.style.height;
            bar.style.height = '0%';
            setTimeout(() => {
                bar.style.height = originalHeight;
            }, 300);
        });
    });
</script>
<?= $this->endSection() ?>