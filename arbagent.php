<?php
session_start();
require_once 'config.php';
// Login Security Check
if (!isset($_SESSION['user_phone']) || $_SESSION['user_phone'] !== "1000000000") {
    header("Location: index.php");
    exit();
}
$v = time();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>ARB Admin Dashboard</title>
<style>
/* Global Styling */
*{margin:0;padding:0;box-sizing:border-box;font-family:sans-serif}
body, html { height: 100%; height: 100dvh; background:#f0f2f5; overflow:hidden; }
body { display:flex; width: 100vw; position: relative; }

/* Sidebar Adjustment */
.sidebar { width:320px; background:#fff; border-right:1px solid #ddd; display:flex; flex-direction:column; transition: 0.3s; z-index: 999; flex-shrink: 0; }
.sidebar-header { padding:15px; background:#f9c21b; font-weight:bold; display: flex; flex-direction: column; gap: 8px; }
.search-box { width:100%; padding:8px; border-radius:20px; border:none; outline:none; font-size:12px; background:rgba(255,255,255,0.5); }
.user-list { flex:1; overflow-y:auto; -webkit-overflow-scrolling: touch; }
.user { padding:15px; cursor:pointer; border-bottom:1px solid #eee; }
.user.active { background:#fff4d1; border-left:5px solid #f9c21b; }

/* Chat Area */
.chat { flex:1; display:flex; flex-direction:column; background:#e5ddd5; position: relative; }
.chat-header { height:60px; background:#fff; display:flex; align-items:center; justify-content: space-between; padding:0 10px; border-bottom:1px solid #ddd; }
.back-btn { display: none; background:#f9c21b; border:none; padding:8px; border-radius:5px; font-weight:bold; }
.id-badge { background:#f1f3f5; padding:6px 12px; border-radius:20px; font-weight:bold; font-size:13px; border:1px solid #ddd; }
.btn-del { background:#333; color:#fff; border:none; padding:8px 12px; border-radius:8px; font-size:11px; font-weight:bold; cursor:pointer; }
.btn-clr { background:#ff4d4d; color:#fff; border:none; padding:8px 12px; border-radius:8px; font-size:11px; font-weight:bold; cursor:pointer; }

/* Messages Scroll */
#messages { flex:1; padding:15px; overflow-y:auto; display:flex; flex-direction:column; gap: 10px; scroll-behavior: smooth; }
.row { display:flex; width:100%; }
.row.user { justify-content:flex-start; }
.row.agent { justify-content:flex-end; }
.bubble { max-width:80%; padding:10px; border-radius:12px; font-size:14px; word-wrap:break-word; box-shadow:0 1px 2px rgba(0,0,0,0.1); }
.bubble.user { background:#fff; border-top-left-radius:2px; }
.bubble.agent { background:#dcf8c6; border-top-right-radius:2px; }

/* Input Bar */
.input-bar { background:#fff; padding:10px; display:flex; align-items:center; gap:10px; border-top:1px solid #ddd; }
.attach-btn { font-size:24px; cursor:pointer; }
#mInp { flex:1; padding:12px; border-radius:20px; border:1px solid #ccc; outline:none; font-size:15px; }
.send-btn { background:#f9c21b; border:none; width:45px; height:45px; border-radius:50%; font-weight:bold; display:flex; align-items:center; justify-content:center; }

@media(max-width: 768px) { .sidebar { position:fixed; width:100%; height:100%; } .sidebar.hide { transform: translateX(-100%); } .back-btn { display:block; } }
</style>
</head>
<body>

<div class="sidebar" id="sidebar">
  <div class="sidebar-header">
    <div style="display:flex; justify-content:space-between;"><span>ARB Admin</span><button onclick="location.reload()" style="background:none; border:none; font-size:18px;">🔄</button></div>
    <input type="text" id="uSearch" class="search-box" placeholder="Search number..." onkeyup="filterUsers()">
  </div>
  <div class="user-list">
    <?php
    $files = glob(DATA_DIR."*.json");
    foreach($files as $file){
      $num = basename($file,".json");
      $last4 = substr($num, -4);
      echo "<div class='user' id='u-$num' data-phone='$num' onclick='openChat(\"$num\", \"$last4\")'><b>ID: ARB-*$last4</b><br><small>+91 $num</small></div>";
    }
    ?>
  </div>
</div>

<div class="chat">
  <div class="chat-header">
    <div style="display:flex; align-items:center; gap:8px;"><button class="back-btn" onclick="toggleSidebar()">⬅</button><div class="id-badge" id="chatUser">Select User</div></div>
    <div id="actions" style="display:none; gap:8px;"><button class="btn-del" onclick="deleteUser()">Delete</button><button class="btn-clr" onclick="clearChat()">Clear</button></div>
  </div>
  <div id="messages"></div>
  <div class="input-bar">
    <label class="attach-btn">📎<input type="file" onchange="uploadFile(this)" style="display:none" accept="image/*"></label>
    <input id="mInp" placeholder="Type response..." autocomplete="off"><button class="send-btn" onclick="send()">➤</button>
  </div>
</div>

<script>
let currentUser = "";
const msgBox = document.getElementById("messages");

function filterUsers() {
  let val = document.getElementById('uSearch').value;
  document.querySelectorAll('.user').forEach(u => u.style.display = u.dataset.phone.includes(val) ? "block" : "none");
}

function toggleSidebar() { document.getElementById("sidebar").classList.remove("hide"); }

function openChat(phone, last4) {
  currentUser = phone;
  document.getElementById("chatUser").innerText = "ID: *" + last4;
  document.getElementById("actions").style.display = "flex";
  document.querySelectorAll('.user').forEach(u => u.classList.remove('active'));
  document.getElementById("u-"+phone).classList.add("active");
  if(window.innerWidth < 768) document.getElementById("sidebar").classList.add("hide");
  load();
}

function load() {
  if(!currentUser) return;
  fetch(`admin_api.php?action=fetch&phone=${currentUser}&v=<?php echo $v; ?>`)
  .then(r => r.json()).then(d => {
    let html = "";
    d.messages.forEach(m => {
      let body = m.type === 'image' ? `<img src="${m.url}" style="max-width:100%; border-radius:10px;">` : m.text;
      html += `<div class="row ${m.sender}"><div class="bubble ${m.sender}">${body}<small style="display:block; font-size:9px; opacity:0.5; text-align:right;">${m.time}</small></div></div>`;
    });
    msgBox.innerHTML = html;
    msgBox.scrollTop = msgBox.scrollHeight;
  });
}

function send() {
  const inp = document.getElementById("mInp");
  if(!inp.value.trim() || !currentUser) return;
  const fd = new FormData(); fd.append("phone", currentUser); fd.append("msg", inp.value);
  fetch("admin_api.php?action=reply", {method:"POST", body:fd}).then(() => { inp.value = ""; load(); });
}

function uploadFile(input) {
  if(!input.files[0] || !currentUser) return;
  const fd = new FormData(); fd.append("phone", currentUser); fd.append("image", input.files[0]);
  fetch("admin_api.php?action=upload", {method:"POST", body:fd}).then(() => { input.value = ""; load(); });
}

function deleteUser() {
  if(!currentUser || !confirm("Delete user thread permanently?")) return;
  const fd = new FormData(); fd.append("phone", currentUser);
  fetch("admin_api.php?action=delete_user", {method:"POST", body:fd}).then(r => r.json()).then(res => {
    if(res.status === 'ok') location.reload(); else alert("Error!");
  });
}

function clearChat() {
  if(!currentUser || !confirm("Clear messages?")) return;
  const fd = new FormData(); fd.append("phone", currentUser);
  fetch("admin_api.php?action=clear", {method:"POST", body:fd}).then(() => load());
}

if(window.visualViewport) window.visualViewport.addEventListener('resize', () => { document.body.style.height = window.visualViewport.height + 'px'; msgBox.scrollTop = msgBox.scrollHeight; });
document.getElementById("mInp").addEventListener("keypress", (e) => { if(e.key === "Enter") send(); });
setInterval(load, 2500);
</script>
</body>
</html>
