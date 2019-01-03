<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Decorative WebGL Backgrounds | Demo 2 | Codrops</title>
		<meta name="description" content="A collection of decorative animated background shapes powered by WebGL and TweenMax." />
		<meta name="keywords" content="webgl, background, shape, web design, web development, tweenmax, gsap, animation" />
		<meta name="author" content="Louis Hoebregts for Codrops" />
		<!--<link rel="shortcut icon" href="favicon.ico">-->
		<link href="https://fonts.googleapis.com/css?family=Barlow:400,500,700" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="assets/css/base.css" />
		<link rel="stylesheet" type="text/css" href="assets/css/menu.css" />
		<link rel="stylesheet" type="text/css" href="assets/css/normalize.css" />
		<script>document.documentElement.className="js";var supportsCssVars=function(){var e,t=document.createElement("style");return t.innerHTML="root: { --tmp-var: bold; }",document.head.appendChild(t),e=!!(window.CSS&&window.CSS.supports&&window.CSS.supports("font-weight","var(--tmp-var)")),t.parentNode.removeChild(t),e};supportsCssVars()||alert("Please view this demo in a modern browser that supports CSS Variables.");</script>
	</head>
	<body class="menu">
    <main class="show-coming main main--demo-1">
        <div class="content content--fixed">
            <header class="codrops-header">
                <h1 class="codrops-header__title">Logo</h1>
            </header>
        </div>
                <div class="content content--demo-1">
                    <div class="hamburger js-hover">
                        <div class="hamburger__line hamburger__line--01">
                            <div class="hamburger__line-in hamburger__line-in--01"></div>
                        </div>
                        <div class="hamburger__line hamburger__line--02">
                            <div class="hamburger__line-in hamburger__line-in--02"></div>
                        </div>
                        <div class="hamburger__line hamburger__line--03">
                            <div class="hamburger__line-in hamburger__line-in--03"></div>
                        </div>
                        <div class="hamburger__line hamburger__line--cross01">
                            <div class="hamburger__line-in hamburger__line-in--cross01"></div>
                        </div>
                        <div class="hamburger__line hamburger__line--cross02">
                            <div class="hamburger__line-in hamburger__line-in--cross02"></div>
                        </div>
                    </div>
                    <div class="global-menu">
                        <div class="global-menu__wrap">
                            <a class="global-menu__item global-menu__item--demo-1" href="#">Home</a>
                            <a class="global-menu__item global-menu__item--demo-1" href="#">About</a>
                            <a class="global-menu__item global-menu__item--demo-1" href="#">Products</a>
                            <a class="global-menu__item global-menu__item--demo-1" href="#">Contact</a>
                        </div>
                    </div>
                    <!-- Canvas -->
                    <canvas class="scene scene--full" id="scene"></canvas>
                    <div class="content__inner">
                        <h3 class="content__subtitle">Abdullah Bozdağ</h3>
                        <h2 class="content__title">Developer</h2>
                    </div>
                    <!-- Canvas Finish -->
                    <svg class="shape-overlays" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <path class="shape-overlays__path"></path>
                        <path class="shape-overlays__path"></path>
                        <path class="shape-overlays__path"></path>
                    </svg>

                </div>


			<script type="x-shader/x-vertex" id="wrapVertexShader">
				#define PI 3.1415926535897932384626433832795
				attribute float size;
				void main() {
					vec4 mvPosition = modelViewMatrix * vec4( position, 1.0 );
					gl_PointSize = 3.0;
					gl_Position = projectionMatrix * mvPosition;
				}
			</script>
			<script type="x-shader/x-fragment" id="wrapFragmentShader">
				uniform sampler2D texture;
				void main(){
					vec4 textureColor = texture2D( texture, gl_PointCoord );
					if ( textureColor.a < 0.3 ) discard;
					vec4 dotColor = vec4(0.06, 0.18, 0.36, 0.4);
					vec4 color = dotColor * textureColor;
					gl_FragColor = color;
				}
			</script>
    </main>

    <script src="assets/js/particle.js"></script>
    <script src="assets/js/three.min.js"></script>
    <script src="assets/js/TweenMax.min.js"></script>
    <script src="assets/js/particle2.js"></script>
    <!--<script src="assets/js/particle3.js"></script>-->
    <script src="assets/js/menu.js"></script>
    <script src="assets/js/menu1.js"></script>
    <script src="assets/js/easings.js"></script>

    </body>
</html>

