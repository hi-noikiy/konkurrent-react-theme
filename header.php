<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<!-- Latest compiled and minified CSS -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans&family=Raleway" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link href="https://file.myfontastic.com/n6vo44Re5QaWo8oCKShBs7/icons.css" rel="stylesheet">

<?php wp_head(); ?>
<script type="text/javascript" src=" http://codex-themes.com/thegem/wp-content/plugins/thegem-custom-options/js/TweenLite.min.js"></script>
<!--<script type="text/javascript" src="http://thegem2.codexthemes.netdna-cdn.com/thegem/wp-content/cache/minify/000000/M9QvyUhNT83VTS4tLsnP1c0vKMnMzyvWzyrWd00sTg1ITM7Wy83MAwA.js"></script>-->
</head>

<body <?php body_class(); ?> id="app-container">

<!--    <header id="masthead" class="site-header" role="banner">
            <div class="site-branding col-xs-9 col-sm-4">
                <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
            </div>

            <div class="primary-navigation pull-right">
                <nav class="nav-primary" role="navigation">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-target="#header-menu" data-toggle="collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div id="header-menu" class="navbar-collapse">
                        <?php
                        if (has_nav_menu('primary')) :
                          wp_nav_menu(['theme_location' => 'primary', 'menu_class' => 'nav nav-stack']);
                        endif;
                        ?>
                    </div>
                </nav>
            </div>
    </header>-->

<script>
    jQuery('.navbar-header button').on('click', function() {
        jQuery('#header-menu').toggle();
        jQuery('body').css('overflow', 'hidden');
    });

    (function() {
        var lastTime = 0;
        var vendors = ['ms', 'moz', 'webkit', 'o'];
        for (var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
            window.requestAnimationFrame = window[vendors[x]+'RequestAnimationFrame'];
            window.cancelAnimationFrame = window[vendors[x]+'CancelAnimationFrame'] || window[vendors[x]+'CancelRequestAnimationFrame'];
        }

        if (!window.requestAnimationFrame)
            window.requestAnimationFrame = function(callback, element) {
                var currTime = new Date().getTime();
                var timeToCall = Math.max(0, 16 - (currTime - lastTime));
                var id = window.setTimeout(function() { callback(currTime + timeToCall); },
                    timeToCall);
                    lastTime = currTime + timeToCall;
                return id;
            };

            if (!window.cancelAnimationFrame)
                window.cancelAnimationFrame = function(id) {
                    clearTimeout(id);
                };

        var canvas,
            contentWidth,
            contentHeight,
            ctx,
            points = [],
            target;

        initVisualAnimation = function() {
            canvas = document.getElementById("animation-visual-canvas");

            resize();

            ctx = canvas.getContext('2d');

            target = {
                x: contentWidth / 2,
                y: contentHeight / 2
            };

            // create points
            for (var x = 0; x < contentWidth; x = x + contentWidth / 20) {
                for (var y = 0; y < contentHeight; y = y + contentHeight / 20) {
                    var px = x + Math.random() * contentWidth / 20;
                    var py = y + Math.random() * contentHeight / 20;
                    points.push({
                        x: px,
                        originX: px,
                        y: py,
                        originY: py
                    });
                }
            }

            // for each point find the 5 closest points
            for (var i = 0; i < points.length; i++) {
                var closest = [];
                var p1 = points[i];
                for (var j = 0; j < points.length; j++) {
                    var p2 = points[j];
                    if (p1 != p2) {
                        var placed = false;
                        for (var k = 0; k < 5; k++) {
                            if (!placed) {
                                if (closest[k] == undefined) {
                                    closest[k] = p2;
                                    placed = true;
                                }
                            }
                        }

                        for (var k = 0; k < 5; k++) {
                            if (!placed) {
                                if (getDistance(p1, p2) < getDistance(p1, closest[k])) {
                                    closest[k] = p2;
                                    placed = true;
                                }
                            }
                        }
                    }
                }
                p1.closest = closest;
            }

            // assign a circle to each point
            for (var i in points) {
                points[i].circle = new Circle(points[i], 2 + Math.random() * 2, 'rgba(255, 255, 255, 0.3)');
            }

            addListeners();

            animate();
            for (var i in points) {
                shiftPoint(points[i]);
            }
        }

        function addListeners() {
            if( !('ontouchstart' in window)) {
                window.addEventListener('mousemove', mouseMove);
            }

            window.addEventListener('resize', resize);
        }

        function mouseMove(e) {
            var posx = posy = 0;
            var offset_top = getElementPosition(canvas).top;

            if (e.pageX || e.pageY) {
                posx = e.pageX;
                posy = e.pageY;
            } else if (e.clientX || e.clientY)    {
                posx = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
                posy = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
            }

            target.x = posx;
            target.y = posy - offset_top;
        }

        function getElementPosition(elem) {
            var w = elem.offsetWidth,
                h = elem.offsetHeight,
                l = 0,
                t = 0;

            while (elem) {
                l += elem.offsetLeft;
                t += elem.offsetTop;
                elem = elem.offsetParent;
            }

            return {
                left: l,
                top: t,
                width: w,
                height: h
            };
        }

        function resize() {
            // parent node size
            console.log('resized');
            console.log(canvas);
            contentWidth = canvas.parentNode.offsetWidth;
            contentHeight = canvas.parentNode.offsetHeight;

            // set canvas size equal size of parent node
            canvas.width = contentWidth;
            canvas.height = contentHeight;
        }

        function getDistance(p1, p2) {
            return Math.pow(p1.x - p2.x, 2) + Math.pow(p1.y - p2.y, 2);
        }

        function Circle(pos, rad, color) {
            var _this = this;

            (function() {
                _this.pos = pos || null;
                _this.radius = rad || null;
                _this.color = color || null;
            })();

            this.draw = function() {
                if (!_this.active) return;
                ctx.beginPath();
                ctx.arc(_this.pos.x, _this.pos.y, _this.radius, 0, 2 * Math.PI, false);
                ctx.fillStyle = 'rgba(255, 255, 255, ' + _this.active + ')';
                ctx.fill();
            };
        }

        function animate() {
            ctx.clearRect(0, 0, contentWidth, contentHeight);

            for (var i in points) {
                // detect points in range
                if (Math.abs(getDistance(target, points[i])) < 4000) {
                    points[i].active = 0.3;
                    points[i].circle.active = 0.6;
                } else if (Math.abs(getDistance(target, points[i])) < 20000) {
                    points[i].active = 0.1;
                    points[i].circle.active = 0.3;
                } else if (Math.abs(getDistance(target, points[i])) < 40000) {
                    points[i].active = 0.02;
                    points[i].circle.active = 0.1;
                } else {
                    points[i].active = 0;
                    points[i].circle.active = 0;
                }

                drawLines(points[i]);
                points[i].circle.draw();
            }

            requestAnimationFrame(animate);
        }

        function drawLines(p) {
            if (!p.active) {
                return;
            }

            for (var i in p.closest) {
                ctx.beginPath();
                ctx.moveTo(p.x, p.y);
                ctx.lineTo(p.closest[i].x, p.closest[i].y);
                ctx.strokeStyle = 'rgba(255, 255, 255, ' + p.active + ')';
                ctx.stroke();
            }
        }

        function shiftPoint(p) {
            TweenLite.to(
                p,
                1 + 1 * Math.random(),
                {
                    x: p.originX - 50 + Math.random() * 100,
                    y: p.originY - 50 + Math.random() * 100,
                    ease:Circle.easeInOut,
                    onComplete: function() {
                        shiftPoint(p);
                    }
                }
            );
        }

        window.onload = initVisualAnimation;
    })();

</script>
</body>
</html>
