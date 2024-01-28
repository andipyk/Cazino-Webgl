<?php
add_action( 'wp_enqueue_scripts', 'my_plugin_assets' );
function my_plugin_assets() {
    wp_register_style( 'casino_webgl', plugins_url( 'style.css' , __FILE__ ) );
}

add_shortcode( 'casino', 'casino_webgl' );

function casino_webgl( $atts ) {
    wp_enqueue_style( 'casino_webgl' );
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/2.2.0/anime.min.js" integrity="sha512-X8lZKRjcVNiBSXn7PNdIncguzX4v8peKIuD9DpNQJqOmKrH4KFvYkDBYdJikYBmghypYSQ+nBlclJsACfDhaKw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.1/gsap.min.js" integrity="sha512-Mf/xUqfWvDIr+1B6zfnIDIiG7XHzyP/guXUWgV6PgaQoIFeXkJQR5XWh9fqAiCiRCpemabt3naV4jhDWVnuYDQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script type="module">

import * as THREE from 'https://cdn.skypack.dev/three@0.136';
import { GLTFLoader } from 'https://cdn.skypack.dev/three@0.136/examples/jsm/loaders/GLTFLoader.js';
import { DRACOLoader } from 'https://cdn.skypack.dev/three@0.136/examples/jsm/loaders/DRACOLoader.js';
import { GUI } from 'https://cdn.skypack.dev/three@0.136/examples/jsm/libs/lil-gui.module.min.js';

jQuery('.vertical-slide').parent().after(`
    <div class="casino scene-bg">
    <!-- Scene 2 overlay -->
    <div class="casino-overlay" id="scene-2">
        <div class="casino-bubble element element-resting">
            <a id="focus-regal-button" role="button" href="javascript:void(0)">
                <img src="https://vechiulcazinou.ro/wp-content/uploads/2023/02/Salon_Regal-removebg-preview.png">
            </a>
        </div>
        <div class="casino-bubble element element-resting">
            <a id="focus-baroc-button" role="button" href="javascript:void(0)">
                <img src="https://vechiulcazinou.ro/wp-content/uploads/2023/02/Salon-Baroc.png">
            </a>
        </div>
        <div class="casino-bubble element element-resting">
            <a id="focus-great-button" role="button" href="javascript:void(0)">
                <img src="https://vechiulcazinou.ro/wp-content/uploads/2023/02/Marele-salon.png">
            </a>
        </div>
        <div class="casino-text">
            <p class="body-1 element element-resting">
				Povestea evenimentului tău începe la Vechiul Cazinou. Descoperă saloanele ce-ți vor celebra momentele mult visate!
			</p>
        </div>
    </div>
    <!-- End scene 2 overlay -->

    <!-- Scene 3 overlay -->
    <div class="casino-overlay" id="scene-3">
        <div class="casino-text">
            <p class="heading-2 element element-resting">Salonul</p>
            <p class="heading-1 element element-resting">Baroc</p>
            <p class="body-2 element element-resting">
				Întruchipează istoria Vechiului Cazinou prin bogăția ornamentelor exuberante și delicatețea florilor sculptate ce te vor conduce într-o lume a noilor începuturi - cea a visurilor împlinite. Somptuos, dar și fin, luminos dar și intim, Salonul Baroc te va ului din momentul în care îi vei trece pragul.
			</p>
			<h6 style="font-size: 18px; margin-top: 15px;"><a href="/salonul-baroc/"><span style="color: #000000;"><strong><span style="text-decoration: underline;">VEZI DETALII</span></strong></span></a></h6>
        </div>
    </div>
    <!-- End scene 3 overlay -->

    <!-- Scene 4 overlay -->
    <div class="casino-overlay" id="scene-4">
        <div class="casino-text">
            <p class="heading-2 element element-resting">Salonul</p>
            <p class="heading-1 element element-resting">Regal</p>
            <p class="body-2 element element-resting">Trecutul, prezentul și viitorul se întâlnesc chiar aici, în inima Vechiului Cazinou. Celebrând bogăția decorațiunii originale, accentuată cu elemente delicate și detalii impecabile, Salonul Regal te va purta într-o călătorie desăvârșită a simțurilor și a emoțiilor, în care povestea evenimentul tău și istoria acestui loc special se vor împleti pentru totdeauna.</p>
			<h6 style="font-size: 18px; margin-top: 15px;"><a href="/salonul-regal/"><span style="color: #000000;"><strong><span style="text-decoration: underline;">VEZI DETALII</span></strong></span></a></h6>

        </div>
    </div>
    <!-- End scene 4 overlay -->

    <!-- Scene 5 overlay -->
    <div class="casino-overlay" id="scene-5">
        <div class="casino-text">
            <p class="heading-2 element element-resting">Marele</p>
            <p class="heading-1 element element-resting">Salon</p>
            <p class="body-2 element element-resting">Este clasic, grandios și romantic. Te va cuceri cu vitalitatea, dar și cu eleganța lui și îți vei imagina cu ușurință aici cel mai frumos moment al vieții tale, fie că este în acorduri de vals sau în ritm de dans.</p>
			<h6 style="font-size: 18px; margin-top: 15px;"><a href="/marele-salon/"><span style="color: #000000;"><strong><span style="text-decoration: underline;">VEZI DETALII</span></strong></span></a></h6>
        </div>
    </div>
    <!-- End scene 5 overlay -->

    <!-- Scene 6 overlay -->
   
    <!-- End scene 6 overlay -->

 

	<div class="scene-bg"></div>

    <!-- Blur -->
    <div class="blur blur-right"></div>
    <div class="blur blur-left"></div>
    <div class="blur blur-bottom"></div>
    <!-- End Blur -->

	<canvas id="webgl"></canvas>

    <!-- Scroll button -->
    <div class="scroll-down"></div>

    </div>  
`);

let additionalfov = 0;

// if (window.innerWidth <= 1024) {
//     additionalfov = 20;
// }

const isMobile = window.innerWidth <= 640;
if (isMobile) {
    additionalfov = 56;
}

// Canvas
const canvas = document.querySelector('#webgl');

const sizes = {
    width: canvas.offsetWidth,
    height: canvas.offsetHeight
};

// const sectiontext = document.querySelector('.section-info')

// Scene

const scene = new THREE.Scene();
scene.background = new THREE.Color('white');

// Camera

const camera = new THREE.PerspectiveCamera(44.2 + additionalfov, sizes.width / sizes.height, 0.001, 1000);

// Renderer

const renderer = new THREE.WebGLRenderer({
    antialias: true,
    logarithmicDepthBuffer: true,
    canvas: canvas
});
renderer.setSize(sizes.width, sizes.height);
renderer.setPixelRatio(window.devicePixelRatio);
renderer.outputEncoding = THREE.sRGBEncoding;

// Screen resize

window.addEventListener('resize', function () {
    renderer.setSize(sizes.width, sizes.height);
    renderer.setPixelRatio(window.devicePixelRatio);

    camera.aspect = sizes.width / sizes.height;
    camera.updateProjectionMatrix();
});

// Lights

const light = new THREE.AmbientLight('white', 0.85); // soft white light
scene.add(light);

const directionalLight = new THREE.DirectionalLight(0xffffff, 1.25);
scene.add(directionalLight);
directionalLight.position.x = -5;

const seconddirectionalLight = new THREE.DirectionalLight(0xffffff, 0.85);
scene.add(seconddirectionalLight);
seconddirectionalLight.position.z = 2;

// Tilt Effect
const pointer = new THREE.Vector2();

function onPointerMove(event) {
    // calculate pointer position in normalized device coordinates
    // (-1 to +1) for both components

    pointer.x = (event.clientX / window.innerWidth) * 2 - 1;
    pointer.y = -(event.clientY / window.innerHeight) * 2 + 1;
    scene.rotation.y = pointer.x * 0.05;
	scene.rotation.x = pointer.y * 0.012;
}

window.addEventListener('pointermove', onPointerMove);

// resize
window.addEventListener('resize', function () {
    renderer.setSize(sizes.width, sizes.height);
    camera.aspect = sizes.width / sizes.height;
    camera.updateProjectionMatrix();
});

// Model

function colorTo(target, value) {

var target = scene.getObjectByName(target);

var initial = new THREE.Color(target.material.color.getHex());
var value = new THREE.Color(value.color.getHex());
TweenLite.to(initial, 1, {
r: value.r,
g: value.g,
b: value.b,

onUpdate: function () {
  target.material.color = initial;
}
});
}

scene.position.x = 0.06;



const dracoLoader = new DRACOLoader();
dracoLoader.setDecoderPath('https://vechiulcazinou.ro/wp-content/plugins/cazino-webgl/draco/');
	
let model;
let building;
const loader = new GLTFLoader();
loader.setDRACOLoader(dracoLoader);

loader.load('https://vechiulcazinou.ro/wp-content/uploads/2022/11/cazinou-10.glb', glb => {
    model = glb.scene;
    scene.add(model);

 
    model.traverse(child => {
        if (child.isMesh && child.name == 'Garden003') {
            building = child;
            building.position.y = -1.3
            // console.log(building);
        }

        if (child.isMesh && child.userData.label) {
            child.visible = false;
        }
    });
});

// Box create function

function createpoint(visible = true) {
    const boxgeometry = new THREE.BoxGeometry(0.06, 0.06, 0.06);
    const material = new THREE.MeshBasicMaterial({ color: 'red' });
    const box = new THREE.Mesh(boxgeometry, material);
    scene.add(box);
    box.visible = visible;
    box.position.y = 0;
    return box;
}

let point1 = createpoint(false);
// point1.position.set(-0.21,0.13,0.45)



// camera.position.set(1.87,  0.48, 0);




function animate() {
    requestAnimationFrame(animate);

    camera.lookAt(point1.position);

    renderer.render(scene, camera);
}

animate();
const duration = 1.5;
const ease = 'power2.in';

gsap.to(camera.position, {
                z: 1.87,
                y: 0.48,
                x: 0.0,
                duration,
                ease
            });

            gsap.to(point1.position, {
                y: 0.13,
                x: -0.21,
                duration,
                ease
            });

    //  gsap.to(building.position, {
    //             y: -1.3,
    //             duration,
    //             ease
    //         });         
camera.updateProjectionMatrix();



const goToScene = index => {
    switch (index) {
        case 2:



            // gsap.to(building.position, {
            //     y: -1.3,
            //     duration,
            //     ease
            // });

            gsap.to(camera.position, {
                z: 1.87,
                y: 0.48,
                x: 0.0,
                duration,
                ease
            });

            gsap.to(point1.position, {
                y: 0.13,
                x: -0.21,
                duration,
                ease
            });

            gsap.to(camera, {
                duration,
                ease
            });

                

            camera.updateProjectionMatrix();

            colorTo("Garden002", new THREE.MeshBasicMaterial({color: '#FFFFFF'}));
            colorTo("Garden004", new THREE.MeshBasicMaterial({color: '#FFFFFF'}));
            colorTo("Garden005", new THREE.MeshBasicMaterial({color: '#FFFFFF'}));
            colorTo("Garden006", new THREE.MeshBasicMaterial({color: '#FFFFFF'}));
            colorTo("Salonul_Regal002", new THREE.MeshBasicMaterial({color: '#FFFFFF'}));
            colorTo("Salonul_Regal004", new THREE.MeshBasicMaterial({color: '#FFFFFF'}));
            colorTo("Salonul_Regal003", new THREE.MeshBasicMaterial({color: '#FFFFFF'}));

            gsap.to(scene.getObjectByName('Garden002').material, {
                emissiveIntensity: 1,
                duration,
                ease
            });

            gsap.to(scene.getObjectByName('Garden004').material, {
                emissiveIntensity: 1,
                metalness: 1,
                duration,
                ease
            });

            gsap.to(scene.getObjectByName('Garden005').material, {
                emissiveIntensity: 1,
                metalness: 1,
                duration,
                ease
            });
            
            gsap.to(scene.getObjectByName('Garden006').material, {
                emissiveIntensity: 1,
                metalness: 1,
                duration,
                ease
            });

            gsap.to(scene.getObjectByName('Salonul_Regal002').material, {
                emissiveIntensity: 1,
                metalness: 1,
                duration,
                ease
            });

            gsap.to(scene.getObjectByName('Salonul_Regal003').material, {
                emissiveIntensity: 1,
                metalness: 1,
                duration,
                ease
            });


            gsap.to(scene.getObjectByName('Salonul_Regal004').material, {
                emissiveIntensity: 1,
                metalness: 1,
                duration,
                ease
            });
            



            break;
        case 3:

   
            gsap.to(building.position, {
                y: -1.3,
                duration,
                ease
            });

            gsap.to(camera.position, {
                z: 0.55,
                y: 0.28,
                x: -0.53,
                duration,
                ease
            });

            gsap.to(point1.position, {
                y: 0.225,
                x: -0.13,
                z: 0.145,
                duration,
                ease
            });
            camera.updateProjectionMatrix();

            colorTo("Garden002", new THREE.MeshBasicMaterial({color: '#FFFFFF'}));
            colorTo("Garden004", new THREE.MeshBasicMaterial({color: '#FFFFFF'}));
            colorTo("Garden005", new THREE.MeshBasicMaterial({color: '#FFFFFF'}));
            colorTo("Garden006", new THREE.MeshBasicMaterial({color: '#FFFFFF'}));
            colorTo("Salonul_Regal001", new THREE.MeshBasicMaterial({color: '#FFFFFF'}));
            colorTo("Salonul_Regal002", new THREE.MeshBasicMaterial({color: '#c49d78'}));
            colorTo("Salonul_Regal004", new THREE.MeshBasicMaterial({color: '#FFFFFF'}));
            colorTo("Salonul_Regal003", new THREE.MeshBasicMaterial({color: '#BEC5CD'}));

           
            gsap.to(scene.getObjectByName('Garden002').material, {
                emissiveIntensity: 1,
                duration,
                ease
            });

            gsap.to(scene.getObjectByName('Garden004').material, {
                emissiveIntensity: 1,
                metalness: 1,
                duration,
                ease
            });

            gsap.to(scene.getObjectByName('Garden005').material, {
                emissiveIntensity: 1,
                metalness: 1,
                duration,
                ease
            });

            gsap.to(scene.getObjectByName('Garden006').material, {
                emissiveIntensity: 1,
                metalness: 1,
                duration,
                ease
            });

            gsap.to(scene.getObjectByName('Salonul_Regal001').material, {
                emissiveIntensity: 0.7,
                metalness: 0.8,
                duration,
                ease
            });

            gsap.to(scene.getObjectByName('Salonul_Regal002').material, {
                emissiveIntensity: 0.7,
                metalness: 0.8,
                duration,
                ease
            });

            gsap.to(scene.getObjectByName('Salonul_Regal003').material, {
                emissiveIntensity: 0.2,
                duration,
                ease
            });

            gsap.to(scene.getObjectByName('Salonul_Regal004').material, {
                emissiveIntensity: 1,
                metalness: 1,
                duration,
                ease
            });
           

            break;
        case 4:



            colorTo("Garden002", new THREE.MeshBasicMaterial({color: '#FFFFFF'}));
            colorTo("Garden004", new THREE.MeshBasicMaterial({color: '#FFFFFF'}));
            colorTo("Garden005", new THREE.MeshBasicMaterial({color: '#FFFFFF'}));
            colorTo("Garden006", new THREE.MeshBasicMaterial({color: '#c49d78'}));
            colorTo("Salonul_Regal002", new THREE.MeshBasicMaterial({color: '#FFFFFF'}));
            colorTo("Salonul_Regal004", new THREE.MeshBasicMaterial({color: '#c49d78'}));
            colorTo("Salonul_Regal001", new THREE.MeshBasicMaterial({color: '#FFFFFF'}));
            colorTo("Salonul_Regal002", new THREE.MeshBasicMaterial({color: '#FFFFFF'}));
            colorTo("Salonul_Regal003", new THREE.MeshBasicMaterial({color: '#FFFFFF'}));

            

            gsap.to(building.position, {
                y: -1.3,
                duration,
                ease
            });

            gsap.to(camera.position, {
                z: 1.2,
                y: 0.22,
                x: 0.25,
                duration,
                ease
            });

            gsap.to(point1.position, {
                y: 0.12,
                x: 0.01,
                z: 0.2,
                duration,
                ease
            });
            camera.updateProjectionMatrix();

            gsap.to(scene.getObjectByName('Garden002').material, {
                emissiveIntensity: 1,
                duration,
                ease
            });

            gsap.to(scene.getObjectByName('Garden004').material, {
                emissiveIntensity: 1,
                metalness: 1,
                duration,
                ease
            });

            gsap.to(scene.getObjectByName('Garden005').material, {
                emissiveIntensity: 1,
                metalness: 1,
                duration,
                ease
            });

            gsap.to(scene.getObjectByName('Salonul_Regal002').material, {
                emissiveIntensity: 1,
                metalness: 1,
                duration,
                ease
            });

            gsap.to(scene.getObjectByName('Salonul_Regal003').material, {
                emissiveIntensity: 1,
                metalness: 1,
                duration,
                ease
            });

            gsap.to(scene.getObjectByName('Salonul_Regal004').material, {
                emissiveIntensity: 0.7,
                metalness: 0.8,
                duration,
                ease
            });

            gsap.to(scene.getObjectByName('Salonul_Regal001').material, {
                emissiveIntensity: 0.7,
                metalness: 0.8,
                duration,
                ease
            });

            gsap.to(scene.getObjectByName('Salonul_Regal002').material, {
                emissiveIntensity: 0.7,
                metalness: 0.8,
                duration,
                ease
            });

            gsap.to(scene.getObjectByName('Salonul_Regal003').material, {
                emissiveIntensity: 0.7,
                metalness: 0.8,
                duration,
                ease
            });

            gsap.to(scene.getObjectByName('Garden006').material, {
                emissiveIntensity: 0.5,
                metalness: 0.93,
                duration,
                ease
            });



          

            break;
        case 5:

            gsap.to(building.position, {
                y: -1.3,
                duration,
                ease
            });

            gsap.to(camera.position, {
                z: 1.3,
                y: 0.28,
                x: -0.15,
                duration,
                ease
            });

            gsap.to(point1.position, {
                y: 0.11,
                x: -0.5,
                z: 0.2,
                duration,
                ease
            });
            camera.updateProjectionMatrix();

            colorTo("Garden002", new THREE.MeshBasicMaterial({color: '#A3E699'}));
            colorTo("Garden004", new THREE.MeshBasicMaterial({color: '#c49d78'}));
            colorTo("Garden005", new THREE.MeshBasicMaterial({color: '#c49d78'}));
            colorTo("Garden006", new THREE.MeshBasicMaterial({color: '#FFFFFF'}));
            colorTo("Salonul_Regal001", new THREE.MeshBasicMaterial({color: '#FFFFFF'}));
            colorTo("Salonul_Regal002", new THREE.MeshBasicMaterial({color: '#FFFFFF'}));
            colorTo("Salonul_Regal004", new THREE.MeshBasicMaterial({color: '#FFFFFF'}));
            colorTo("Salonul_Regal003", new THREE.MeshBasicMaterial({color: '#FFFFFF'}));

           

            gsap.to(scene.getObjectByName('Garden002').material, {
                emissiveIntensity: 0.11,
                duration,
                ease
            });

            gsap.to(scene.getObjectByName('Garden004').material, {
                emissiveIntensity: 0.7,
                metalness: 0.7,
                duration,
                ease
            });

            gsap.to(scene.getObjectByName('Garden005').material, {
                emissiveIntensity: 0.3,
                metalness: 0.78,
                duration,
                ease
            });

            gsap.to(scene.getObjectByName('Salonul_Regal001').material, {
                emissiveIntensity: 1,
                metalness: 1,
                duration,
                ease
            });

            gsap.to(scene.getObjectByName('Salonul_Regal002').material, {
                emissiveIntensity: 1,
                metalness: 1,
                duration,
                ease
            });

            gsap.to(scene.getObjectByName('Salonul_Regal003').material, {
                emissiveIntensity: 1,
                metalness: 1,
                duration,
                ease
            });


            gsap.to(scene.getObjectByName('Salonul_Regal004').material, {
                emissiveIntensity: 1,
                metalness: 1,
                duration,
                ease
            });

            gsap.to(scene.getObjectByName('Garden006').material, {
                emissiveIntensity: 1,
                metalness: 1,
                duration,
                ease
            });
      

            break;
        case 6:

        
            // scene.getObjectByName('Garden002').material.emissiveIntensity = 0.45
            // scene.getObjectByName('Garden004').material.emissiveIntensity = 0.7
            // scene.getObjectByName('Garden004').material.metalness = 0.55

            break;

        default:
    }
};

if (!jQuery('body').hasClass('elementor-editor-active')) {
    jQuery('body').css('overflow', 'hidden');

    // FSVS specific IDs
    jQuery('html').attr('id', 'fsvs');
    jQuery('.vertical-slide').parent().attr('id', 'fsvs-body');

    // Append the overlay elements that will show up on the WebGL
    jQuery('.vertical-slide').parent().attr('id', 'fsvs-body');
	
	jQuery('.scroll-down').appendTo('body');

    const lastVerticalSlide = jQuery('.vertical-slide').last();
    // Create a sections wrapper and include all the section that are not FSVS slides
    lastVerticalSlide.after(`
        <div class="vertical-slide last-slide" id="sections-wrapper" style="overflow-y: auto;"></div>
    `);
    jQuery('#sections-wrapper').nextAll().appendTo('#sections-wrapper');
    jQuery("[data-elementor-type='footer']").appendTo('#sections-wrapper');

    // Add empty slides that will controll the WebGL animations
    jQuery('#cazino-webgl').after(`
        <div class="vertical-slide"></div>
        <div class="vertical-slide"></div>
        <div class="vertical-slide"></div>
    `);

    const fsvsSpeed = 1000;
    const fsvsEvents =
        'wheel.fsvs mousewheel.fsvs DOMMouseScroll.fsvs MozMousePixelScroll.fsvs touchstart.fsvs touchmove.fsvs';
    const showScrollAfter = 8000;

    // Show the Scroll Down button if no action was taken
    const scrollButton = jQuery('.scroll-down');
    const showScroll = () => scrollButton.addClass('active');
    let scrolTimeout;

    // While the FSVS slides or the WebGL animates the next scene, prevent the scroll events
    const preventBodyScroll = e => {
        e.stopPropagation();
    };
	
    const fsvs = jQuery.fn.fsvs({
        speed: fsvsSpeed,
        bodyID: 'fsvs-body',
        selector: '.vertical-slide',
        mouseSwipeDisance: 40,
        beforeSlide: function (index) {
             const preventDuration = index > 2 && index < 6 ? duration * 1000 : fsvsSpeed;
            // Prevent FSVS events and re-enable them after the animation finishes
			jQuery('body').on(fsvsEvents, preventBodyScroll);
			setTimeout(() => {
				jQuery('body').off(fsvsEvents, preventBodyScroll);
			}, preventDuration);
						
            // Reset additional info state
            jQuery('.casino').attr('class', 'casino');
            jQuery('.element').removeClass('element-active');
            jQuery('.casino-overlay').removeClass('overlay-active');
			
            // Hide scroll then start the count down for the next inactivity
            clearInterval(scrolTimeout);
            scrollButton.removeClass('active');
			if(index >= 2 && index < 6){
                scrolTimeout = setTimeout(showScroll, showScrollAfter);				
			}

           
            
            if (index <= 1) {
                // Move casino down so it will animate bottom-up
                jQuery('.casino').css('transform', 'translateY(100%)');
                jQuery("[data-elementor-type='header']").removeClass('hidden');
            }

            if (index >= 1) {
                jQuery("[data-elementor-type='header']").addClass('hidden');
            }

            if (index > 1 && index < 6) {
                goToScene(index);
                // Move casino on the screen, the initial position
                jQuery('.casino').css('transform', 'translateY(0)');
				if(window.innerWidth <= 768) {
					jQuery('.casino #webgl').css('top', '10%');
				}

                // Animate current slide additional info
                // We add a small delay to wait for the FVSVS slide and WebGL animation
                setTimeout(
                    () => {
                        jQuery('.casino').addClass(`scene-${index}-active`);
                        jQuery(`#scene-${index} .element`).addClass('element-active');
                        jQuery(`#scene-${index}.casino-overlay`).addClass('overlay-active');
                    },
                    index === 2 ? fsvsSpeed / 2 : fsvsSpeed
                );
            }
            if (index >= 6) {
              
                // Move casino up so it will animate up-bottom
                jQuery('.casino').css('transform', 'translateY(-100%)');
				if(window.innerWidth <= 768) {
					jQuery('.casino #webgl').css('top', '0');
				}
            }
			
			if (index < 7) {
                jQuery('.blur-bottom').css('display', 'block');
            }
			
			  if (index >= 7) {
                jQuery('.blur-bottom').css('display', 'none');
            }
			
			if (index > 8) {
                // Prevent FSVS events from triggering after you reach final slide
                const sectionWrapper = jQuery('#sections-wrapper');

                // Slightly scroll to allow the trigger of the next event
                sectionWrapper.animate({ scrollTop: 150 });
				
				// Allow scroll in the final slide
				sectionWrapper.on('scroll', () => {
					if (sectionWrapper.scrollTop() <= 40) {
						jQuery('body').off(fsvsEvents, preventBodyScroll);
					}else {
						jQuery('body').on(fsvsEvents, preventBodyScroll);
					}
				});
			}

            
        },
        afterSlide: function (index) {},
        endSlide: function (e) {},
        mouseWheelEvents: true,
        mouseDragEvents: true,
        touchEvents: true,
        arrowKeyEvents: false,
        pagination: true,
        nthClasses: false,
        detectHash: false
    });
    fsvs.init();
    fsvs.addPagination();
    //slider.slideUp();
    //slider.slideDown();
    //slider.slideToIndex( index );
    //slider.unbind();
    //slider.rebind();

    // Additional events for the Slides Annotations
    scrollButton.on('click', () => fsvs.slideDown());
    jQuery('#focus-casino-button').on('click', () => fsvs.slideToIndex(3));
    jQuery('#focus-baroc-button').on('click', () => fsvs.slideToIndex(3));
    jQuery('#focus-great-button').on('click', () => fsvs.slideToIndex(5));
    jQuery('#focus-regal-button').on('click', () => fsvs.slideToIndex(4));
	
	const slideTitles = [
    'Acasă',
    'Despre noi',
    'Vechiul Cazinou',
    'Salonul Baroc',
    'Salonul Regal',
    'Marele Salon',
	'Casa Ceaiului',
    'Simțuri',
    'Cătălin Jernoiu',
    'Galerie',
    'Rezervări',
  	];

	
	slideTitles.forEach((title, index)=>{
		const elem = jQuery(`#fsvs-pagination li:nth-child(${index + 1})`);
		elem.attr('data-title', title);
	})
}

</script>

<?php
	

    // Gallery code here
}