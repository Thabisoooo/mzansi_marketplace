?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mzansi Marketplace</title>
    <link rel="stylesheet" href="assets/css/style.css">
	<header class="sa-header">
    <h1>Mzansi Marketplace</h1>
    
    <div class="language-selector">
        <label for="language">Choose Language: </label>
        <select id="language" onchange="setLanguage()">
            <option value="en">English</option>
            <option value="zu">isiZulu</option>
            <option value="se">Setswana</option>
            <option value="af">Afrikaans</option>
            <option value="ts">Xitsonga</option>
        </select>
    </div>
</header>

</head>
<body>



<nav class="main-nav">
    <ul>
        <li><a href="index.php">Home</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="dashboard_buyer.php">Buyer Dashboard</a></li>
            <li><a href="dashboard_seller.php">Seller Dashboard</a></li>
            <li><a href="cart.php">Cart</a></li>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>

<main>
    <h2 id="welcome">Welcome to the Mzansi Marketplace!</h2>
    <p id="description">South Africa's hybrid barter e-commerce platform. Trade goods, sell your items, or find the best deals, all in your language.</p>

    <section class="features">
        <div class="feature-card">
			
			<img src="assets/uploads/2013_sony_camera.jpeg" alt="Used Camera">
            <h3>2013 Sony Alpha 7R</h3>
            <p> 36.4MP Full-Frame Mirrorless Camera
				<br>Legendary resolution in a compact body. Full-frame 36.4MP sensor, E-mount, fast autofocus, and lightweight for pros and enthusiasts. Great condition. Ready to shoot.</p>
            <p><strong>R600</strong> or trade</p>
        </div>
        <div class="feature-card">
			<img src="assets/uploads/mountain_bike.jpg" alt="Used mountain bike">
            <h3>Used Mountain Bicycle</h3>
            <p>Mountain bike with gears, great for trail rides and local commutes.</p>
            <p><strong>R1050</strong>
        </div>
        <div class="feature-card">
			<img src="assets/uploads/zulu_beads.jpg" alt="Traditional Beads">
            <h3>Traditional Zulu Beads</h3>
			<p>Beautiful handmade beadwork. Cultural and stylish.</p>
            <p><strong>R200</strong> or trade</p>
        </div>
    </section>
</main>


<script>
const translations = {
    en: {
        welcome: "Welcome to Mzansi Marketplace!",
        description: "A unified South African hybrid barter platform."
    },
    zu: {
        welcome: "Siyakwamukela eMzansi Marketplace!",
        description: "Ipulatifomu ye-bhatha ehlanganisayo yeNingizimu Afrika."
    },
    xh: {
        welcome: "Wamkelekile kwiMzansi Marketplace!",
        description: "Iqonga lokutshintshiselana elidibeneyo laseMzantsi Afrika."
    },
    af: {
        welcome: "Welkom by Mzansi Marketplace!",
        description: "‘n Verenigde Suid-Afrikaanse ruilplatform."
    },
    se: {
        welcome: "Rea u amogela go Mzansi Marketplace!",
        description: "Setšhaba sa borai sa mebaro sa Afrika Borwa."
    },
    ts: {
        welcome: "U amukeriwile eMzansi Marketplace!",
        description: "Xipulatifomo xa barter xa Afrika Dzonga lexi hlanganisiweke."
    }
    
};

function setLanguage() {
    const lang = document.getElementById("language").value;
    localStorage.setItem("lang", lang);
    updateText(lang);
}

function updateText(lang) {
    const text = translations[lang] || translations["en"];
    document.getElementById("welcome").textContent = text.welcome;
    document.getElementById("description").textContent = text.description;
}

document.addEventListener("DOMContentLoaded", () => {
    const lang = localStorage.getItem("lang") || "en";
    document.getElementById("language").value = lang;
    updateText(lang);
});

</script>


</script>

<footer class="sa-footer">
    <p>&copy; <?php echo date('Y'); ?> Mzansi Marketplace. <br>	Umuntu Ngumuntu Ngabantu</p>
</footer>
</body>
</html>