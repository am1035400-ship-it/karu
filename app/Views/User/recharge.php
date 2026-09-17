<?= $this->extend('Layout/Starter') ?>
<?= $this->section('content') ?>

<style>
    @import 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap';
    
    body {
        font-family: 'Poppins', sans-serif;
    }
    
    .recharge-card {
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(255, 144, 187, 0.15);
        background: white;
        border: none;
        overflow: hidden;
        margin-bottom: 24px;
        transition: transform 0.3s ease;
    }
    
    .card-header-pink {
        background: linear-gradient(0.9turn, #FF90BB, #8ACCD5, #FF90BB);
        color: white;
        font-weight: 600;
        padding: 16px 20px;
        border: none;
        font-size: 17px;
    }

    .balance-badge {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 8px 18px;
        border-radius: 30px;
        font-size: 22px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .amount-chip {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 16px;
        font-weight: 600;
        color: #475569;
        cursor: pointer;
        background: #f8fafc;
        transition: all 0.2s ease;
        text-align: center;
        user-select: none;
    }

    .amount-chip:hover {
        border-color: #FF90BB;
        background: #fff0f5;
        color: #FF69B4;
        transform: translateY(-2px);
    }

    .amount-chip.active {
        border-color: #FF90BB;
        background: linear-gradient(135deg, #FF90BB, #8ACCD5);
        color: white;
        box-shadow: 0 4px 12px rgba(255, 144, 187, 0.35);
    }

    .btn-pay {
        background: linear-gradient(0.9turn, #FF90BB, #8ACCD5, #FF90BB);
        color: white;
        border: none;
        padding: 14px 28px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 16px;
        width: 100%;
        box-shadow: 0 4px 15px rgba(255, 144, 187, 0.4);
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-pay:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 144, 187, 0.5);
    }

    .upi-badge-row {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 15px;
    }

    .upi-pill {
        font-size: 11.5px;
        font-weight: 600;
        background: #f8fafc;
        color: #475569;
        padding: 4px 10px;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
    }

    .badge-paid {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
    }

    .badge-pending {
        background: #fef9c3;
        color: #854d0e;
        border: 1px solid #fef08a;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
    }

    .badge-expired {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #cbd5e1;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
    }
</style>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-12">
            <?= $this->include('Layout/msgStatus') ?>
        </div>

        <!-- Left Column: Add Balance Card -->
        <div class="col-lg-5 col-md-12">
            <div class="recharge-card">
                <div class="card-header-pink d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-wallet2 me-2"></i> Add Wallet Balance</span>
                    <span class="badge bg-white text-dark" style="font-size: 12px; border-radius: 20px;">Instant UPI</span>
                </div>
                <div class="card-body p-4">
                    
                    <!-- Balance Banner -->
                    <div class="text-center mb-4 p-3" style="background: #faf5ff; border-radius: 14px; border: 1px dashed #d8b4fe;">
                        <small class="text-muted d-block mb-1">Current Wallet Balance</small>
                        <div class="balance-badge">
                            <i class="bi bi-cash-stack"></i> ₹<?= number_format((float)($user->saldo ?? 0), 2) ?>
                        </div>
                    </div>

                    <!-- Quick Amount Selector -->
                    <label class="form-label fw-bold text-secondary mb-2" style="font-size: 13px;">Select Quick Amount (Min: ₹1 | Max: ₹1,000)</label>
                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <div class="amount-chip" onclick="selectAmount(1)">₹1</div>
                        </div>
                        <div class="col-4">
                            <div class="amount-chip" onclick="selectAmount(10)">₹10</div>
                        </div>
                        <div class="col-4">
                            <div class="amount-chip" onclick="selectAmount(50)">₹50</div>
                        </div>
                        <div class="col-4">
                            <div class="amount-chip active" onclick="selectAmount(100)">₹100</div>
                        </div>
                        <div class="col-4">
                            <div class="amount-chip" onclick="selectAmount(500)">₹500</div>
                        </div>
                        <div class="col-4">
                            <div class="amount-chip" onclick="selectAmount(1000)">₹1,000</div>
                        </div>
                    </div>

                    <!-- Amount Input Form with Direct Redirect to FamGateway -->
                    <form id="rechargeForm" action="<?= site_url('payment/create') ?>" method="POST" onsubmit="return handlePaySubmit()">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="amountInput" class="form-label fw-bold text-secondary" style="font-size: 13px;">Enter Custom Amount (INR)</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light text-muted fw-bold">₹</span>
                                <input type="number" name="amount" id="amountInput" class="form-control fw-bold text-dark" value="100" min="1" max="1000" step="1" required placeholder="100">
                            </div>
                            <small class="text-muted mt-1 d-block"><i class="bi bi-shield-check text-success"></i> Instant auto-credit | Min: ₹1 &nbsp;•&nbsp; Max: ₹1,000</small>
                        </div>

                        <button type="submit" id="payBtn" class="btn-pay">
                            <i class="bi bi-arrow-right-circle"></i> Proceed to Pay via UPI
                        </button>
                    </form>

                    <!-- Supported UPI Apps -->
                    <div class="upi-badge-row">
                        <span class="upi-pill"><i class="bi bi-check-circle-fill text-success"></i> Google Pay</span>
                        <span class="upi-pill"><i class="bi bi-check-circle-fill text-success"></i> PhonePe</span>
                        <span class="upi-pill"><i class="bi bi-check-circle-fill text-success"></i> Paytm</span>
                        <span class="upi-pill"><i class="bi bi-check-circle-fill text-success"></i> FamPay</span>
                        <span class="upi-pill"><i class="bi bi-check-circle-fill text-success"></i> BHIM UPI</span>
                    </div>

                </div>
            </div>
        </div>

        <!-- Right Column: Recent Transactions Table -->
        <div class="col-lg-7 col-md-12">
            <div class="recharge-card">
                <div class="card-header-pink d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-clock-history me-2"></i> Recent Recharge History</span>
                    <a href="<?= site_url('dashboard') ?>" class="btn btn-sm btn-light text-dark py-0 px-2" style="font-size: 12px; border-radius: 20px;">Back to Dashboard</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-center" style="font-size: 13.5px;">
                            <thead class="table-light">
                                <tr>
                                    <th>Order ID</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>UTR / Ref</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recentPayments)) : ?>
                                    <?php foreach ($recentPayments as $p) : ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-light text-dark border font-monospace"><?= esc($p['order_id']) ?></span>
                                            </td>
                                            <td class="fw-bold text-success">
                                                ₹<?= number_format((float)$p['amount'], 2) ?>
                                            </td>
                                            <td>
                                                <?php if ($p['status'] === 'PAID') : ?>
                                                    <span class="badge-paid"><i class="bi bi-check2-circle"></i> PAID</span>
                                                <?php elseif ($p['status'] === 'EXPIRED') : ?>
                                                    <span class="badge-expired"><i class="bi bi-x-circle"></i> EXPIRED</span>
                                                <?php else : ?>
                                                    <span class="badge-pending"><i class="bi bi-hourglass-split"></i> PENDING</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-muted font-monospace" style="font-size: 12px;">
                                                <?= $p['utr'] ? esc($p['utr']) : '<span class="text-muted">-</span>' ?>
                                            </td>
                                            <td class="text-muted" style="font-size: 12px;">
                                                <?= date('d M, h:i A', strtotime($p['created_at'])) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="5" class="py-4 text-muted">
                                            <i class="bi bi-inbox fs-2 d-block text-secondary mb-2"></i>
                                            No recharge transactions found yet.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function selectAmount(val) {
    document.getElementById('amountInput').value = val;
    document.querySelectorAll('.amount-chip').forEach(chip => {
        chip.classList.remove('active');
        if (chip.innerText.replace(/[^0-9]/g, '') == val) {
            chip.classList.add('active');
        }
    });
}

document.getElementById('amountInput').addEventListener('input', function() {
    let val = this.value;
    document.querySelectorAll('.amount-chip').forEach(chip => {
        chip.classList.remove('active');
        if (chip.innerText.replace(/[^0-9]/g, '') == val) {
            chip.classList.add('active');
        }
    });
});

function handlePaySubmit() {
    let btn = document.getElementById('payBtn');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Redirecting to FamGateway...';
    btn.disabled = true;
    return true;
}
</script>

<?= $this->endSection() ?>
