<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ALLAN GUEST HOUSE</title>
               <style>
            body {
                background-color: #f5f5f5;
            }
            nav {
                background: #222;
                color: #fff;
                padding: 16px;
                border-radius: 8px;
                text-align: center;
                margin-bottom: 24px;
            }
            nav a {
                color: #fff;
                text-decoration: none;
                margin: 0 10px;
                font-weight: bold;
            }
            nav a:hover {
                text-decoration: underline;
            }
            #rotating-image {
                display: block;
                margin: 20px auto;
                max-width: 80vw;
                max-height: 400px;
                border-radius: 10px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            }
  
}
        </style>
    </head>
    <body>
        <nav>
    <a href="#rooms">Rooms</a> |
    <a href="#hotel">hotel</a> |
    <a href="book.php">Booking</a> |
    <a href="admin.php">admin</a> |
    <a href="#contact">Contact</a>
</nav>
        <img id="rotating-image" src="r1.jpg.png" alt="Rotating Image">
        <div id="contact">
            <h1>Welcome to Allan Guest House</h1>
            <p>Experience comfort and hospitality like never before.</p>
            <p>
                Call us: +254 703630174<br>
                Email: emoruallan@gmail.com
            </p>
        </div>
        <div id="rooms">
    <h2>Rooms</h2>
    <p>Details about our rooms...</p>
</div>
<div id="hotel">
    <h2>hotel</h2>
    <p>Our dining options...</p>
</div>
<div id="Admim">
    <h2>Admin</h2>
    <p>Admin section...</p>
</div>
        <script>
            const images = [
                'r1.jpg.png',
                'r2.png',
                'r3.png',
                "r4.png",
                'd1.png',
                'd2.png',
                'd3.png',
                'd4.png',
                'p1.png',
                'p2.png',
                'p3.png',
            ];
            let index = 0;
            function rotateBackground() {
                document.body.style.backgroundImage = `url('${images[index]}')`;
                index = (index + 1) % images.length;
            }
            rotateBackground(); // Show first image immediately
            setInterval(rotateBackground, 3000); // Change every 3 seconds
        </script>
                <footer style="text-align:center; padding:16px; background:#222; color:#fff; margin-top:40px; border-radius:8px;">
            &copy; 2025 Allan Guest House Malaba. All rights reserved.<br>
            <div style="margin-top:12px;">
                <span style="font-weight:bold;">Follow us:</span>
                <ul style="list-style:none; padding:0; margin:12px auto 0 auto; display:inline-block; text-align:left;">
                    <li style="margin-bottom:10px;">
                        <a href="https://www.instagram.com/unkno_wnallan?igsh=dTFtY3FlYTlhMXpn" target="_blank" aria-label="Instagram" style="color:#E1306C; text-decoration:none; display:flex; align-items:center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#E1306C" viewBox="0 0 24 24" style="vertical-align:middle; margin-right:8px;">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.334 3.608 1.308.974.974 1.246 2.241 1.308 3.608.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.062 1.366-.334 2.633-1.308 3.608-.974.974-2.241 1.246-3.608 1.308-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.062-2.633-.334-3.608-1.308-.974-.974-1.246-2.241-1.308-3.608C2.175 15.647 2.163 15.267 2.163 12s.012-3.584.07-4.85c.062-1.366.334-2.633 1.308-3.608.974-.974 2.241-1.246 3.608-1.308C8.416 2.175 8.796 2.163 12 2.163zm0-2.163C8.741 0 8.332.013 7.052.072 5.773.131 4.602.396 3.635 1.363c-.967.967-1.232 2.138-1.291 3.417C2.013 8.332 2 8.741 2 12c0 3.259.013 3.668.072 4.948.059 1.279.324 2.45 1.291 3.417.967.967 2.138 1.232 3.417 1.291C8.332 23.987 8.741 24 12 24s3.668-.013 4.948-.072c1.279-.059 2.45-.324 3.417-1.291.967-.967 1.232-2.138 1.291-3.417.059-1.28.072-1.689.072-4.948 0-3.259-.013-3.668-.072-4.948-.059-1.279-.324-2.45-1.291-3.417-.967-.967-2.138-1.232-3.417-1.291C15.668.013 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zm0 10.162a3.999 3.999 0 1 1 0-7.998 3.999 3.999 0 0 1 0 7.998zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/>
                            </svg>
                            Instagram
                        </a>
                    </li>
                    <li>
                        <a href="https://www.tiktok.com/@alllan1303?is_from_webapp=1&sender_device=pc" target="_blank" aria-label="TikTok" style="color:#69C9D0; text-decoration:none; display:flex; align-items:center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#69C9D0" viewBox="0 0 24 24" style="vertical-align:middle; margin-right:8px;">
                                <path d="M12.004 2c2.209 0 4.004 1.795 4.004 4.004v11.992c0 2.209-1.795 4.004-4.004 4.004s-4.004-1.795-4.004-4.004v-3.996h2v3.996c0 1.104.896 2 2.004 2s2.004-.896 2.004-2v-11.992c0-1.104-.896-2-2.004-2s-2.004.896-2.004 2v3.996h-2v-3.996c0-2.209 1.795-4.004 4.004-4.004z"/>
                            </svg>
                            TikTok
                        </a>
                    </li>
                </ul>
            </div>
        </footer>
    </body>
</html>