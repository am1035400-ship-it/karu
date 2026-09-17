<?php

namespace App\Controllers;

use App\Models\KeysModel;

class Connect extends BaseController
{
    protected $model, $maintenance, $maintenance_msg, $staticWords;

    public function __construct()
    {
        include('conn.php');
        $sql1 = "SELECT * FROM onoff WHERE id=1";
        $result1 = mysqli_query($conn, $sql1);
        $userDetails1 = mysqli_fetch_assoc($result1);

        $this->model = new KeysModel();
        $this->maintenance = ($userDetails1['status'] === 'on');
        $this->maintenance_msg = $userDetails1['myinput'] ?? 'Under Maintenance';
        $this->staticWords = "Vm8Lk7Uj2JmsjCPVPVjrLa7zgfx3uz9E";
    }

    public function index()
    {
        if ($this->request->getPost()) {
            return $this->index_post();
        } else {
            // Display the moving text banner when accessed via GET
            return $this->showMovingBanner();
        }
    }

    public function showMovingBanner()
    {
        // HTML content with moving text banner - Funny Style
        $html = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>🤪 BUY PANEL - @ARYANISPE 🤪</title>
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                
                body {
                    font-family: "Comic Neue", "Comic Sans MS", "Chalkboard SE", cursive, sans-serif;
                    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
                    min-height: 100vh;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    overflow-x: hidden;
                    position: relative;
                }
                
                /* Funny random dots background */
                body::before {
                    content: "😂 🤣 🥲 🥹 😜 🤪 😝 😛 🫠";
                    position: absolute;
                    top: 0;
                    left: 0;
                    font-size: 30px;
                    opacity: 0.1;
                    white-space: nowrap;
                    animation: float 20s linear infinite;
                    pointer-events: none;
                }
                
                @keyframes float {
                    0% {
                        transform: translateX(0%) translateY(0%);
                    }
                    100% {
                        transform: translateX(-50%) translateY(50%);
                    }
                }
                
                .banner-container {
                    width: 100%;
                    background: #ff6b6b;
                    padding: 20px 0;
                    position: fixed;
                    top: 0;
                    left: 0;
                    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
                    overflow: hidden;
                    white-space: nowrap;
                    border-bottom: 5px dashed #ffd93d;
                    z-index: 1000;
                }
                
                .moving-text {
                    display: inline-block;
                    animation: wiggleMove 15s linear infinite;
                    font-size: 32px;
                    font-weight: bold;
                    color: #ffd93d;
                    text-shadow: 3px 3px 0 #ff3b3b;
                    letter-spacing: 3px;
                }
                
                @keyframes wiggleMove {
                    0% {
                        transform: translateX(0%) rotate(0deg);
                    }
                    25% {
                        transform: translateX(25%) rotate(2deg);
                    }
                    50% {
                        transform: translateX(50%) rotate(-2deg);
                    }
                    75% {
                        transform: translateX(75%) rotate(1deg);
                    }
                    100% {
                        transform: translateX(100%) rotate(0deg);
                    }
                }
                
                .content {
                    text-align: center;
                    padding: 20px;
                    position: relative;
                    z-index: 1;
                    margin-top: 100px;
                }
                
                .gif-container {
                    margin-bottom: 30px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    animation: bounce 2s ease-in-out infinite;
                }
                
                @keyframes bounce {
                    0%, 100% {
                        transform: translateY(0);
                    }
                    50% {
                        transform: translateY(-10px);
                    }
                }
                
                .tenor-gif-embed {
                    max-width: 300px;
                    margin: 0 auto;
                    border-radius: 20px;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
                }
                
                .api-info {
                    background: rgba(255, 255, 255, 0.95);
                    border-radius: 50px;
                    padding: 40px;
                    max-width: 600px;
                    margin: 0 auto;
                    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
                    border: 5px solid #ffd93d;
                    transform: rotate(-1deg);
                    transition: transform 0.3s ease;
                }
                
                .api-info:hover {
                    transform: rotate(0deg) scale(1.02);
                }
                
                .api-info h1 {
                    margin-bottom: 20px;
                    font-size: 36px;
                    color: #ff6b6b;
                    font-weight: bold;
                    text-shadow: 2px 2px 0 #ffd93d;
                }
                
                .api-info p {
                    font-size: 18px;
                    line-height: 1.6;
                    margin-bottom: 15px;
                    color: #555;
                }
                
                .method {
                    background: linear-gradient(135deg, #ffd93d, #ffb347);
                    padding: 15px;
                    border-radius: 30px;
                    font-family: monospace;
                    margin-top: 20px;
                    color: #fff;
                    font-weight: bold;
                    font-size: 18px;
                }
                
                .funny-emoji {
                    font-size: 40px;
                    margin: 10px;
                    display: inline-block;
                    animation: spin 3s ease-in-out infinite;
                }
                
                @keyframes spin {
                    0%, 100% {
                        transform: rotate(0deg);
                    }
                    50% {
                        transform: rotate(10deg);
                    }
                }
                
                hr {
                    border: none;
                    height: 3px;
                    background: linear-gradient(to right, transparent, #ff6b6b, #ffd93d, #ff6b6b, transparent);
                    margin: 20px 0;
                }
                
                @media (max-width: 768px) {
                    .moving-text {
                        font-size: 20px;
                        animation-duration: 10s;
                    }
                    
                    .api-info {
                        margin: 20px;
                        padding: 25px;
                    }
                    
                    .api-info h1 {
                        font-size: 24px;
                    }
                    
                    .funny-emoji {
                        font-size: 30px;
                    }
                }
            </style>
        </head>
        <body>
            <div class="banner-container">
                <div class="moving-text">
                    🤪 BUY PANEL - @ARYANISPE 🤪 &nbsp;&nbsp;&nbsp;😂 BUY PANEL - @ARYANISPE 😂 &nbsp;&nbsp;&nbsp;🥴 BUY PANEL - @ARYANISPE 🥴 &nbsp;&nbsp;&nbsp;😜 BUY PANEL - @ARYANISPE 😜 &nbsp;&nbsp;&nbsp;
                </div>
            </div>
            
            <div class="content">
                <div class="gif-container">
                    <div class="tenor-gif-embed" data-postid="3201141539685971562" data-share-method="host" data-aspect-ratio="1" data-width="100%"><a href="https://tenor.com/view/wobbly-life-macarena-dance-meme-song-gif-3201141539685971562">Wobbly Life Macarena Sticker</a>from <a href="https://tenor.com/search/wobbly+life-stickers">Wobbly Life Stickers</a></div>
                    <script type="text/javascript" async src="https://tenor.com/embed.js"></script>
                </div>
                
                <div class="api-info">
                    <div class="funny-emoji">🤪 🤣 🥴</div>
                    <h1>✨ HEY YOU! WELCOME TO THE FUN ZONE! ✨</h1>
                    <div class="funny-emoji">😜 😝 🫠</div>
                    <hr>
                    <p><strong>🤡 Yo! This is where the magic happens! 🤡</strong></p>
                    <p>Send me a POST request with these things:<br>
                    <span style="font-size: 24px;">👇👇👇</span></p>
                    <div class="method">
                        🎮 <strong>game</strong> | 🔑 <strong>user_key</strong> | 📱 <strong>serial</strong>
                    </div>
                    <p style="margin-top: 20px;">
                        <span style="font-size: 20px;">⚠️ NO POST = NO FUN ⚠️</span><br>
                        <small>Use POST method or I\'ll be sad 😢</small>
                    </p>
                    <hr>
                    <p><small>© ' . date('Y') . ' | Made with 🤪 by @ARYANISPE | BUY NOW OR I\'LL DANCE MACARENA ALL DAY! 💃</small></p>
                    <div class="funny-emoji">🕺 💃 🪩</div>
                </div>
            </div>
        </body>
        </html>
        ';
        
        return $this->response->setBody($html);
    }

    public function index_post()
    {
        $game = $this->request->getPost('game');
        $uKey = $this->request->getPost('user_key');
        $sDev = $this->request->getPost('serial');

        $form_rules = [
            'game' => 'required|alpha_dash',
            'user_key' => 'required|min_length[1]|max_length[36]',
            'serial' => 'required|alpha_dash'
        ];

        if (!$this->validate($form_rules)) {
            return $this->response->setJSON([
                'status' => false,
                'reason' => "Bad Parameter"
            ]);
        }

        if ($this->maintenance) {
            return $this->response->setJSON([
                'status' => false,
                'reason' => $this->maintenance_msg
            ]);
        }

        $model = $this->model;
        $time = new \CodeIgniter\I18n\Time;
        $now = $time::now('Asia/Kolkata');

        $findKey = $model->getKeysGame([
            'user_key' => $uKey,
            'game' => $game
        ]);

        if (!$findKey) {
            return $this->response->setJSON([
                'status' => false,
                'reason' => 'USER OR GAME NOT REGISTERED'
            ]);
        }

        if ($findKey->status != 1) {
            return $this->response->setJSON([
                'status' => false,
                'reason' => 'USER BLOCKED'
            ]);
        }

        $id_keys = $findKey->id_keys;
        $duration = $findKey->duration;
        $expired = $findKey->expired_date;
        $max_dev = $findKey->max_devices;
        $devices = $findKey->devices;

        // Device check
        $lsDevice = array_filter(explode(",", $devices));
        $serialOn = in_array($sDev, $lsDevice);

        if (!$serialOn) {
            if (count($lsDevice) >= $max_dev) {
                // Remove oldest and add current device
                array_shift($lsDevice); // remove first (oldest)
            }
            $lsDevice[] = $sDev;
            $model->update($id_keys, [
                'devices' => implode(",", array_unique($lsDevice))
            ]);
        }

        // Expiry Check
        if (!$expired) {
            $expired = $now->addHours($duration);
            $model->update($id_keys, ['expired_date' => $expired->toDateTimeString()]);
        } elseif (!$now->isBefore($expired)) {
            return $this->response->setJSON([
                'status' => false,
                'reason' => 'EXPIRED KEY'
            ]);
        }

        $expiredFormatted = \CodeIgniter\I18n\Time::parse($expired, 'Asia/Kolkata')->toDateTimeString();

        // Extra fetch
        include('conn.php');
        $modData = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM modname WHERE id=1"));
        $footerData = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM _ftext WHERE id=1"));
        $featureData = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM Feature WHERE id=1"));

        $real = "$game-$uKey-$sDev-" . $this->staticWords;
        $rngcnt = $now->getTimestamp();

        return $this->response->setJSON([
            'status' => true,
            'data' => [
                'real' => $real,
                'token' => md5($real),
                'modname' => $modData['modname'] ?? "ARYANISPE PREMIUM",
                'mod_status' => $footerData['_status'] ?? "ON",
                'credit' => $footerData['_ftext'] ?? "Designed by Aryan",
                'ESP' => (int)($featureData['ESP'] ?? 1),
                'Item' => (int)($featureData['Item'] ?? 1),
                'AIM' => (int)($featureData['AIM'] ?? 1),
                'SilentAim' => (int)($featureData['SilentAim'] ?? 0),
                'BulletTrack' => (int)($featureData['BulletTrack'] ?? 0),
                'Floating' => (int)($featureData['Floating'] ?? 0),
                'Memory' => (int)($featureData['Memory'] ?? 0),
                'Setting' => (int)($featureData['Setting'] ?? 0),
                'floating_text' => $featureData['FloatingText'] ?? '',
                'expired_date' => $expiredFormatted,
                'device' => $max_dev,
                'rng' => $rngcnt
            ]
        ]);
    }
}