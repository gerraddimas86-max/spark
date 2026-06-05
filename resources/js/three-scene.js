import * as THREE from 'three';
import gsap from 'gsap';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';

let scene, camera, renderer, controls, ship, water, clouds, seagulls;
let time = 0;
let animationId = null;

export function initThreeScene(containerId) {
    const container = document.getElementById(containerId);
    if (!container) {
        console.error('Container not found:', containerId);
        return;
    }
    
    // ========== SCENE ==========
    scene = new THREE.Scene();
    scene.background = new THREE.Color(0x87CEEB);
    scene.fog = new THREE.FogExp2(0x87CEEB, 0.008);
    
    // ========== CAMERA (Isometric 45 degree) ==========
    camera = new THREE.PerspectiveCamera(35, container.clientWidth / container.clientHeight, 0.1, 1000);
    camera.position.set(8, 6, 10);
    camera.lookAt(0, 1, 0);
    
    // ========== RENDERER ==========
    renderer = new THREE.WebGLRenderer({ antialias: true, alpha: false });
    renderer.setSize(container.clientWidth, container.clientHeight);
    renderer.shadowMap.enabled = true;
    renderer.shadowMap.type = THREE.PCFSoftShadowMap;
    renderer.setPixelRatio(window.devicePixelRatio);
    container.appendChild(renderer.domElement);
    
    // ========== ORBIT CONTROLS ==========
    controls = new OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.05;
    controls.rotateSpeed = 1.0;
    controls.zoomSpeed = 1.2;
    controls.panSpeed = 0.8;
    controls.target.set(0, 1.5, 0);
    
    // ========== LIGHTING ==========
    // Ambient light
    const ambientLight = new THREE.AmbientLight(0x88aaff, 0.65);
    scene.add(ambientLight);
    
    // Main directional light (matahari)
    const mainLight = new THREE.DirectionalLight(0xfff5e6, 1.4);
    mainLight.position.set(5, 10, 4);
    mainLight.castShadow = true;
    mainLight.receiveShadow = true;
    mainLight.shadow.mapSize.width = 1024;
    mainLight.shadow.mapSize.height = 1024;
    scene.add(mainLight);
    
    // Fill light from below
    const fillLight = new THREE.PointLight(0x88ccff, 0.4);
    fillLight.position.set(0, -2, 0);
    scene.add(fillLight);
    
    // Warm back light
    const backLight = new THREE.PointLight(0xffaa66, 0.5);
    backLight.position.set(-3, 3, -5);
    scene.add(backLight);
    
    // Rim light
    const rimLight = new THREE.PointLight(0xffcc88, 0.4);
    rimLight.position.set(2, 3, -4);
    scene.add(rimLight);
    
    // ========== WATER / OCEAN ==========
    const waterGeometry = new THREE.CircleGeometry(18, 32);
    const waterMaterial = new THREE.MeshStandardMaterial({ 
        color: 0x2E86C1,
        roughness: 0.3,
        metalness: 0.7,
        emissive: 0x1B4F72,
        emissiveIntensity: 0.1
    });
    water = new THREE.Mesh(waterGeometry, waterMaterial);
    water.rotation.x = -Math.PI / 2;
    water.position.y = -0.6;
    water.receiveShadow = true;
    scene.add(water);
    
    // Water ripple effect
    const rippleGeometry = new THREE.CircleGeometry(19, 32);
    const rippleMaterial = new THREE.MeshStandardMaterial({ 
        color: 0x3A9BDC,
        roughness: 0.2,
        metalness: 0.8,
        transparent: true,
        opacity: 0.5
    });
    const rippleRing = new THREE.Mesh(rippleGeometry, rippleMaterial);
    rippleRing.rotation.x = -Math.PI / 2;
    rippleRing.position.y = -0.58;
    scene.add(rippleRing);
    
    // ========== STYLIZED CARTOON PIRATE SHIP ==========
    ship = createStylizedPirateShip();
    scene.add(ship);
    
    // ========== CLOUDS ==========
    clouds = createStylizedClouds();
    scene.add(clouds);
    
    // ========== SEAGULLS ==========
    seagulls = createCartoonSeagulls();
    scene.add(seagulls);
    
    // ========== SUN ==========
    const sunGeometry = new THREE.SphereGeometry(1.0, 32, 32);
    const sunMaterial = new THREE.MeshStandardMaterial({ 
        color: 0xFFDD88,
        emissive: 0xFF8844,
        emissiveIntensity: 0.4,
        roughness: 0.1
    });
    const sun = new THREE.Mesh(sunGeometry, sunMaterial);
    sun.position.set(-10, 8, -8);
    scene.add(sun);
    
    // Sun glow
    const glowGeometry = new THREE.SphereGeometry(1.3, 16, 16);
    const glowMaterial = new THREE.MeshBasicMaterial({ 
        color: 0xFFAA55,
        transparent: true,
        opacity: 0.2,
        side: THREE.BackSide
    });
    const sunGlow = new THREE.Mesh(glowGeometry, glowMaterial);
    sun.add(sunGlow);
    
    // ========== ANIMATION ==========
    let lastTimestamp = 0;
    
    function animate() {
        animationId = requestAnimationFrame(animate);
        const now = Date.now();
        const delta = Math.min(1/30, (now - lastTimestamp) / 1000);
        lastTimestamp = now;
        time += delta;
        
        controls.update();
        
        // Gentle ship bobbing
        if (ship) {
            ship.position.y = Math.sin(time * 1.2) * 0.04;
            ship.rotation.z = Math.sin(time * 1.0) * 0.015;
            ship.rotation.x = Math.sin(time * 0.8) * 0.01;
        }
        
        // Animate clouds
        if (clouds) {
            clouds.children.forEach((cloud, idx) => {
                cloud.position.x += 0.001 * (idx % 2 === 0 ? 1 : -1);
                if (cloud.position.x > 12) cloud.position.x = -12;
                if (cloud.position.x < -12) cloud.position.x = 12;
            });
        }
        
        // Animate seagulls
        if (seagulls) {
            seagulls.children.forEach((seagull, idx) => {
                seagull.position.x += Math.sin(time * 0.5 + idx) * 0.003;
                seagull.position.z += Math.cos(time * 0.5 + idx) * 0.002;
                seagull.rotation.y = Math.sin(time * 1.5 + idx) * 0.3;
            });
        }
        
        renderer.render(scene, camera);
    }
    
    animate();
    
    window.addEventListener('resize', () => onWindowResize(container));
    
    console.log('🏴‍☠️ Stylized Cartoon Pirate Ship Loaded!');
    return { scene, camera, renderer, controls };
}

function createStylizedPirateShip() {
    const shipGroup = new THREE.Group();
    
    // ========== COLORS ==========
    const hullColor = 0xC17A3A;
    const hullDarkColor = 0xA0622A;
    const deckColor = 0xE8C89A;
    const trimColor = 0xD4A76A;
    const sailColor = 0xFEF8E8;
    const sailShadowColor = 0xE8E0C8;
    const redAccent = 0xCC3333;
    const goldAccent = 0xFFD700;
    const cannonColor = 0x4A4A4A;
    
    // ========== HULL (Curved and proportioned) ==========
    // Main hull body - curved shape
    const hullGeo = new THREE.BoxGeometry(3.2, 0.9, 5.8);
    const hullMat = new THREE.MeshStandardMaterial({ color: hullColor, roughness: 0.4, metalness: 0.1 });
    const hull = new THREE.Mesh(hullGeo, hullMat);
    hull.position.y = 0;
    hull.castShadow = true;
    hull.receiveShadow = true;
    shipGroup.add(hull);
    
    // Hull bottom - rounded
    const bottomGeo = new THREE.CylinderGeometry(1.6, 1.4, 0.7, 16);
    const bottomMat = new THREE.MeshStandardMaterial({ color: hullDarkColor, roughness: 0.5 });
    const bottom = new THREE.Mesh(bottomGeo, bottomMat);
    bottom.position.y = -0.55;
    bottom.castShadow = true;
    shipGroup.add(bottom);
    
    // Hull front (bow) - curved cone
    const bowGeo = new THREE.ConeGeometry(0.9, 1.3, 12);
    const bowMat = new THREE.MeshStandardMaterial({ color: hullColor, roughness: 0.4 });
    const bow = new THREE.Mesh(bowGeo, bowMat);
    bow.position.set(0, 0.15, 3.1);
    bow.rotation.x = 0.15;
    bow.castShadow = true;
    shipGroup.add(bow);
    
    // Hull back (stern) - raised
    const sternGeo = new THREE.BoxGeometry(2.8, 1.1, 0.9);
    const sternMat = new THREE.MeshStandardMaterial({ color: hullColor });
    const stern = new THREE.Mesh(sternGeo, sternMat);
    stern.position.set(0, 0.4, -2.6);
    stern.castShadow = true;
    shipGroup.add(stern);
    
    // Decorative stripes on hull
    const stripeMat = new THREE.MeshStandardMaterial({ color: goldAccent });
    const stripeGeo = new THREE.BoxGeometry(3.0, 0.08, 0.15);
    
    const stripe1 = new THREE.Mesh(stripeGeo, stripeMat);
    stripe1.position.set(0, -0.1, 1.5);
    shipGroup.add(stripe1);
    
    const stripe2 = new THREE.Mesh(stripeGeo, stripeMat);
    stripe2.position.set(0, -0.1, -0.5);
    shipGroup.add(stripe2);
    
    const stripe3 = new THREE.Mesh(stripeGeo, stripeMat);
    stripe3.position.set(0, -0.1, -2.2);
    shipGroup.add(stripe3);
    
    // Red accent stripe
    const redMat = new THREE.MeshStandardMaterial({ color: redAccent });
    const redStripe = new THREE.Mesh(new THREE.BoxGeometry(3.1, 0.1, 0.12), redMat);
    redStripe.position.set(0, 0.05, 1.8);
    shipGroup.add(redStripe);
    
    const redStripe2 = new THREE.Mesh(new THREE.BoxGeometry(3.1, 0.1, 0.12), redMat);
    redStripe2.position.set(0, 0.05, -1.2);
    shipGroup.add(redStripe2);
    
    // ========== DECK ==========
    const deckMat = new THREE.MeshStandardMaterial({ color: deckColor, roughness: 0.5 });
    const deckGeo = new THREE.BoxGeometry(2.9, 0.12, 5.4);
    const deck = new THREE.Mesh(deckGeo, deckMat);
    deck.position.y = 0.55;
    deck.castShadow = true;
    shipGroup.add(deck);
    
    // Deck planks detail
    const plankMat = new THREE.MeshStandardMaterial({ color: 0xD4A76A });
    for (let i = -2; i <= 2; i++) {
        const plank = new THREE.Mesh(new THREE.BoxGeometry(0.1, 0.05, 4.8), plankMat);
        plank.position.set(i * 0.65, 0.62, 0);
        shipGroup.add(plank);
    }
    
    // ========== RAILINGS ==========
    const railMat = new THREE.MeshStandardMaterial({ color: trimColor });
    const railGeo = new THREE.BoxGeometry(0.12, 0.35, 5.5);
    
    // Left railing
    const leftRail = new THREE.Mesh(railGeo, railMat);
    leftRail.position.set(-1.55, 0.78, 0);
    leftRail.castShadow = true;
    shipGroup.add(leftRail);
    
    // Right railing
    const rightRail = new THREE.Mesh(railGeo, railMat);
    rightRail.position.set(1.55, 0.78, 0);
    rightRail.castShadow = true;
    shipGroup.add(rightRail);
    
    // Front railing
    const frontRail = new THREE.Mesh(new THREE.BoxGeometry(3.0, 0.35, 0.12), railMat);
    frontRail.position.set(0, 0.78, 2.85);
    frontRail.castShadow = true;
    shipGroup.add(frontRail);
    
    // Back railing
    const backRail = new THREE.Mesh(new THREE.BoxGeometry(3.0, 0.35, 0.12), railMat);
    backRail.position.set(0, 0.78, -2.8);
    backRail.castShadow = true;
    shipGroup.add(backRail);
    
    // Railing posts
    const postMat = new THREE.MeshStandardMaterial({ color: trimColor });
    for (let i = -2; i <= 2; i++) {
        const leftPost = new THREE.Mesh(new THREE.BoxGeometry(0.1, 0.5, 0.1), postMat);
        leftPost.position.set(-1.55, 0.6, i * 1.1);
        shipGroup.add(leftPost);
        
        const rightPost = new THREE.Mesh(new THREE.BoxGeometry(0.1, 0.5, 0.1), postMat);
        rightPost.position.set(1.55, 0.6, i * 1.1);
        shipGroup.add(rightPost);
    }
    
    // ========== MASTS ==========
    const mastMat = new THREE.MeshStandardMaterial({ color: 0x8B6914, roughness: 0.3 });
    
    // Main mast (tallest, center)
    const mainMast = new THREE.Mesh(new THREE.CylinderGeometry(0.22, 0.32, 3.8, 10), mastMat);
    mainMast.position.set(0, 2.1, 0.2);
    mainMast.castShadow = true;
    shipGroup.add(mainMast);
    
    // Front mast
    const frontMast = new THREE.Mesh(new THREE.CylinderGeometry(0.16, 0.24, 2.8, 10), mastMat);
    frontMast.position.set(0, 1.6, 2.4);
    frontMast.castShadow = true;
    shipGroup.add(frontMast);
    
    // Back mast
    const backMast = new THREE.Mesh(new THREE.CylinderGeometry(0.18, 0.26, 3.0, 10), mastMat);
    backMast.position.set(0, 1.8, -1.8);
    backMast.castShadow = true;
    shipGroup.add(backMast);
    
    // Crow's nest on main mast
    const nestMat = new THREE.MeshStandardMaterial({ color: 0x8B6914 });
    const nestBase = new THREE.Mesh(new THREE.CylinderGeometry(0.45, 0.5, 0.2, 8), nestMat);
    nestBase.position.set(0, 3.3, 0.2);
    shipGroup.add(nestBase);
    
    const nestRail = new THREE.Mesh(new THREE.TorusGeometry(0.45, 0.05, 8, 24), nestMat);
    nestRail.position.set(0, 3.45, 0.2);
    shipGroup.add(nestRail);
    
    // ========== SAILS (Large, cartoon style) ==========
    const sailMat = new THREE.MeshStandardMaterial({ color: sailColor, side: THREE.DoubleSide, roughness: 0.2 });
    const sailShadowMat = new THREE.MeshStandardMaterial({ color: sailShadowColor, side: THREE.DoubleSide, roughness: 0.3 });
    
    // Main sail - large
    const mainSailGeo = new THREE.PlaneGeometry(2.8, 3.2);
    const mainSail = new THREE.Mesh(mainSailGeo, sailMat);
    mainSail.position.set(0, 2.5, 0.2);
    mainSail.castShadow = true;
    shipGroup.add(mainSail);
    
    // Main sail lower
    const mainSailLowerGeo = new THREE.PlaneGeometry(2.4, 2.4);
    const mainSailLower = new THREE.Mesh(mainSailLowerGeo, sailShadowMat);
    mainSailLower.position.set(0, 1.4, 0.5);
    mainSailLower.castShadow = true;
    shipGroup.add(mainSailLower);
    
    // Front sail
    const frontSailGeo = new THREE.PlaneGeometry(2.0, 2.2);
    const frontSail = new THREE.Mesh(frontSailGeo, sailMat);
    frontSail.position.set(0, 1.9, 2.6);
    frontSail.castShadow = true;
    shipGroup.add(frontSail);
    
    // Front sail lower
    const frontSailLower = new THREE.Mesh(new THREE.PlaneGeometry(1.6, 1.6), sailShadowMat);
    frontSailLower.position.set(0, 1.0, 3.0);
    frontSailLower.castShadow = true;
    shipGroup.add(frontSailLower);
    
    // Back sail
    const backSailGeo = new THREE.PlaneGeometry(2.2, 2.5);
    const backSail = new THREE.Mesh(backSailGeo, sailMat);
    backSail.position.set(0, 2.0, -1.6);
    backSail.castShadow = true;
    shipGroup.add(backSail);
    
    // Sail details (stitching lines)
    const stitchMat = new THREE.MeshStandardMaterial({ color: 0xD4C4A8 });
    
    function addStitching(sailMesh, offsetX, offsetY) {
        const stitchGeo = new THREE.BoxGeometry(0.03, 0.03, 0.02);
        for (let y = -1.2; y <= 1.2; y += 0.4) {
            const stitch = new THREE.Mesh(stitchGeo, stitchMat);
            stitch.position.set(offsetX, y + offsetY, 0.05);
            sailMesh.add(stitch);
        }
    }
    
    // ========== FLAGS (Jolly Roger!) ==========
    const flagMat = new THREE.MeshStandardMaterial({ color: 0x222222 });
    const flagGeo = new THREE.BoxGeometry(0.9, 0.1, 0.6);
    const mainFlag = new THREE.Mesh(flagGeo, flagMat);
    mainFlag.position.set(0.45, 3.6, 0.2);
    mainFlag.castShadow = true;
    shipGroup.add(mainFlag);
    
    // Skull on main flag
    const whiteMat = new THREE.MeshStandardMaterial({ color: 0xFFFFFF });
    const skull = new THREE.Mesh(new THREE.SphereGeometry(0.1, 8, 8), whiteMat);
    skull.position.set(0.45, 3.62, 0.55);
    shipGroup.add(skull);
    
    const crossMat = new THREE.MeshStandardMaterial({ color: 0xFFFFFF });
    const cross1 = new THREE.Mesh(new THREE.BoxGeometry(0.05, 0.2, 0.05), crossMat);
    cross1.position.set(0.45, 3.58, 0.53);
    shipGroup.add(cross1);
    
    const cross2 = new THREE.Mesh(new THREE.BoxGeometry(0.2, 0.05, 0.05), crossMat);
    cross2.position.set(0.45, 3.56, 0.53);
    shipGroup.add(cross2);
    
    // Red flag at back
    const redFlagMat = new THREE.MeshStandardMaterial({ color: redAccent });
    const backFlag = new THREE.Mesh(new THREE.BoxGeometry(0.7, 0.08, 0.5), redFlagMat);
    backFlag.position.set(-0.5, 3.0, -1.8);
    shipGroup.add(backFlag);
    
    // ========== CANNONS (Cartoon style) ==========
    const cannonMat = new THREE.MeshStandardMaterial({ color: cannonColor, metalness: 0.6, roughness: 0.3 });
    const cannonPositions = [
        { x: -1.4, z: 2.0, rot: 0.2 },
        { x: 1.4, z: 2.0, rot: -0.2 },
        { x: -1.4, z: 0.6, rot: 0.15 },
        { x: 1.4, z: 0.6, rot: -0.15 },
        { x: -1.4, z: -0.8, rot: 0.1 },
        { x: 1.4, z: -0.8, rot: -0.1 }
    ];
    
    cannonPositions.forEach(pos => {
        const cannonGroup = new THREE.Group();
        
        const cannonBody = new THREE.Mesh(new THREE.CylinderGeometry(0.14, 0.16, 0.5, 8), cannonMat);
        cannonBody.rotation.z = Math.PI / 2;
        cannonBody.rotation.x = pos.rot;
        cannonGroup.add(cannonBody);
        
        const cannonWheel = new THREE.Mesh(new THREE.CylinderGeometry(0.1, 0.1, 0.06, 6), cannonMat);
        cannonWheel.rotation.x = Math.PI / 2;
        cannonWheel.position.set(0, -0.12, 0);
        cannonGroup.add(cannonWheel);
        
        cannonGroup.position.set(pos.x, 0.25, pos.z);
        shipGroup.add(cannonGroup);
    });
    
    // ========== WHEEL (Helm) ==========
    const wheelGroup = new THREE.Group();
    const wheelWoodMat = new THREE.MeshStandardMaterial({ color: 0x8B5A2B });
    const wheelGoldMat = new THREE.MeshStandardMaterial({ color: goldAccent, metalness: 0.7 });
    
    const wheelCenter = new THREE.Mesh(new THREE.CylinderGeometry(0.15, 0.15, 0.1, 8), wheelGoldMat);
    wheelCenter.rotation.x = Math.PI / 2;
    wheelGroup.add(wheelCenter);
    
    for (let i = 0; i < 8; i++) {
        const angle = (i / 8) * Math.PI * 2;
        const spoke = new THREE.Mesh(new THREE.BoxGeometry(0.08, 0.55, 0.08), wheelWoodMat);
        spoke.position.set(Math.cos(angle) * 0.35, Math.sin(angle) * 0.35, 0);
        wheelGroup.add(spoke);
        
        const handle = new THREE.Mesh(new THREE.SphereGeometry(0.06, 6, 6), wheelGoldMat);
        handle.position.set(Math.cos(angle) * 0.55, Math.sin(angle) * 0.55, 0);
        wheelGroup.add(handle);
    }
    
    const wheelRing = new THREE.Mesh(new THREE.TorusGeometry(0.4, 0.07, 8, 32), wheelWoodMat);
    wheelRing.rotation.x = Math.PI / 2;
    wheelGroup.add(wheelRing);
    
    wheelGroup.position.set(0.9, 0.95, -2.5);
    wheelGroup.rotation.z = 0.15;
    shipGroup.add(wheelGroup);
    
    // ========== ANCHOR ==========
    const anchorMat = new THREE.MeshStandardMaterial({ color: 0x666666, metalness: 0.5 });
    
    const anchorArm = new THREE.Mesh(new THREE.BoxGeometry(0.1, 0.7, 0.1), anchorMat);
    anchorArm.position.set(0, -0.2, 3.2);
    shipGroup.add(anchorArm);
    
    const anchorHook = new THREE.Mesh(new THREE.TorusGeometry(0.18, 0.06, 8, 16, Math.PI), anchorMat);
    anchorHook.rotation.x = Math.PI / 2;
    anchorHook.position.set(0, -0.55, 3.2);
    shipGroup.add(anchorHook);
    
    const anchorRing = new THREE.Mesh(new THREE.TorusGeometry(0.12, 0.05, 6, 12), anchorMat);
    anchorRing.position.set(0, 0.1, 3.2);
    shipGroup.add(anchorRing);
    
    // ========== LANTERNS ==========
    const lanternMat = new THREE.MeshStandardMaterial({ color: 0xFFAA55, emissive: 0xFF6622, emissiveIntensity: 0.3 });
    const lanternPositions = [
        { x: -1.5, z: 2.7, y: 0.9 },
        { x: 1.5, z: 2.7, y: 0.9 },
        { x: -1.5, z: -2.3, y: 0.9 },
        { x: 1.5, z: -2.3, y: 0.9 }
    ];
    
    lanternPositions.forEach(pos => {
        const lanternBase = new THREE.Mesh(new THREE.CylinderGeometry(0.12, 0.14, 0.18, 6), lanternMat);
        lanternBase.position.set(pos.x, pos.y, pos.z);
        shipGroup.add(lanternBase);
        
        const lanternGlass = new THREE.Mesh(new THREE.SphereGeometry(0.11, 8, 8), lanternMat);
        lanternGlass.position.set(pos.x, pos.y + 0.12, pos.z);
        shipGroup.add(lanternGlass);
        
        const lanternTop = new THREE.Mesh(new THREE.ConeGeometry(0.1, 0.1, 4), lanternMat);
        lanternTop.position.set(pos.x, pos.y + 0.24, pos.z);
        shipGroup.add(lanternTop);
    });
    
    // ========== FIGUREHEAD (Cute pirate caricature) ==========
    const figureGroup = new THREE.Group();
    
    const figureBody = new THREE.Mesh(new THREE.CylinderGeometry(0.25, 0.3, 0.7, 8), new THREE.MeshStandardMaterial({ color: 0xDEB887 }));
    figureBody.position.set(0, 0.2, 3.45);
    shipGroup.add(figureBody);
    
    const figureHead = new THREE.Mesh(new THREE.SphereGeometry(0.22, 16, 16), new THREE.MeshStandardMaterial({ color: 0xDEB887 }));
    figureHead.position.set(0, 0.6, 3.5);
    shipGroup.add(figureHead);
    
    // Hat
    const hatGeo = new THREE.ConeGeometry(0.28, 0.4, 6);
    const hatMat = new THREE.MeshStandardMaterial({ color: 0x8B0000 });
    const hat = new THREE.Mesh(hatGeo, hatMat);
    hat.position.set(0, 0.85, 3.5);
    shipGroup.add(hat);
    
    // Bandana
    const bandanaMat = new THREE.MeshStandardMaterial({ color: 0xFF4444 });
    const bandana = new THREE.Mesh(new THREE.BoxGeometry(0.45, 0.08, 0.1), bandanaMat);
    bandana.position.set(0, 0.72, 3.65);
    shipGroup.add(bandana);
    
    // Eyepatch
    const eyepatchMat = new THREE.MeshStandardMaterial({ color: 0x222222 });
    const eyepatch = new THREE.Mesh(new THREE.SphereGeometry(0.08, 8, 8), eyepatchMat);
    eyepatch.position.set(-0.12, 0.62, 3.72);
    shipGroup.add(eyepatch);
    
    const eyepatchStrap = new THREE.Mesh(new THREE.BoxGeometry(0.35, 0.04, 0.05), eyepatchMat);
    eyepatchStrap.position.set(-0.05, 0.62, 3.68);
    shipGroup.add(eyepatchStrap);
    
    // ========== ROPES (Decorative) ==========
    const ropeMat = new THREE.MeshStandardMaterial({ color: 0xC4A67A });
    
    // Rope between main mast and front mast
    const ropePoints = [
        [0, 2.8, 0.2], [0, 2.2, 2.4]
    ];
    
    // Simple rope representation using cylinders
    const ropeLength = Math.sqrt(Math.pow(2.8 - 2.2, 2) + Math.pow(0.2 - 2.4, 2));
    const rope = new THREE.Mesh(new THREE.BoxGeometry(0.05, 0.05, ropeLength * 2), ropeMat);
    rope.position.set(0, 2.5, 1.3);
    rope.rotation.z = 0.2;
    shipGroup.add(rope);
    
    // ========== BARREL on deck ==========
    const barrelMat = new THREE.MeshStandardMaterial({ color: 0xA0724A });
    const barrel = new THREE.Mesh(new THREE.CylinderGeometry(0.35, 0.35, 0.5, 8), barrelMat);
    barrel.position.set(1.2, 0.4, -1.5);
    barrel.castShadow = true;
    shipGroup.add(barrel);
    
    const barrelStrap = new THREE.Mesh(new THREE.TorusGeometry(0.35, 0.05, 6, 16), new THREE.MeshStandardMaterial({ color: 0x8B5A2B }));
    barrelStrap.rotation.x = Math.PI / 2;
    barrelStrap.position.set(1.2, 0.65, -1.5);
    shipGroup.add(barrelStrap);
    
    return shipGroup;
}

function createStylizedClouds() {
    const cloudGroup = new THREE.Group();
    const cloudMat = new THREE.MeshStandardMaterial({ color: 0xF5F5F0, roughness: 0.9, emissive: 0xEEEEEE, emissiveIntensity: 0.05 });
    
    const cloudConfigs = [
        { x: -5, y: 5.5, z: -3, scale: 1.0, parts: 5 },
        { x: 2, y: 6, z: -4, scale: 1.2, parts: 6 },
        { x: 6, y: 5, z: -2, scale: 0.9, parts: 4 },
        { x: -3, y: 7, z: -6, scale: 0.8, parts: 4 },
        { x: 4, y: 5.8, z: -5, scale: 1.0, parts: 5 }
    ];
    
    cloudConfigs.forEach(config => {
        const cloud = new THREE.Group();
        const partsCount = config.parts;
        
        for (let i = 0; i < partsCount; i++) {
            const radius = 0.35 + Math.random() * 0.15;
            const sphere = new THREE.Mesh(new THREE.SphereGeometry(radius, 16, 16), cloudMat);
            sphere.position.set(
                (Math.random() - 0.5) * 0.6,
                (Math.random() - 0.5) * 0.3,
                (Math.random() - 0.5) * 0.4
            );
            cloud.add(sphere);
        }
        
        cloud.position.set(config.x, config.y, config.z);
        cloud.scale.set(config.scale, config.scale, config.scale);
        cloudGroup.add(cloud);
    });
    
    return cloudGroup;
}

function createCartoonSeagulls() {
    const seagullGroup = new THREE.Group();
    
    const bodyMat = new THREE.MeshStandardMaterial({ color: 0xFFFFFF });
    const wingMat = new THREE.MeshStandardMaterial({ color: 0xE8E8E8 });
    
    for (let i = 0; i < 6; i++) {
        const seagull = new THREE.Group();
        
        // Body
        const body = new THREE.Mesh(new THREE.SphereGeometry(0.14, 8, 8), bodyMat);
        body.scale.set(0.9, 0.6, 1.3);
        seagull.add(body);
        
        // Head
        const head = new THREE.Mesh(new THREE.SphereGeometry(0.1, 8, 8), bodyMat);
        head.position.set(0, 0.08, 0.22);
        seagull.add(head);
        
        // Beak
        const beak = new THREE.Mesh(new THREE.ConeGeometry(0.05, 0.12, 4), new THREE.MeshStandardMaterial({ color: 0xFFA500 }));
        beak.position.set(0, 0.05, 0.32);
        seagull.add(beak);
        
        // Wings
        const leftWing = new THREE.Mesh(new THREE.BoxGeometry(0.35, 0.05, 0.12), wingMat);
        leftWing.position.set(-0.22, 0, 0);
        seagull.add(leftWing);
        
        const rightWing = new THREE.Mesh(new THREE.BoxGeometry(0.35, 0.05, 0.12), wingMat);
        rightWing.position.set(0.22, 0, 0);
        seagull.add(rightWing);
        
        // Tail
        const tail = new THREE.Mesh(new THREE.ConeGeometry(0.08, 0.12, 4), wingMat);
        tail.position.set(0, 0, -0.28);
        tail.rotation.x = 0.3;
        seagull.add(tail);
        
        // Random position
        seagull.position.set(
            (Math.random() - 0.5) * 18,
            3.5 + Math.random() * 4,
            (Math.random() - 0.5) * 12 - 3        );
        
        seagullGroup.add(seagull);
    }
    
    return seagullGroup;
}

export function resetCamera() {
    if (camera && controls) {
        gsap.to(camera.position, {
            x: 8,
            y: 6,
            z: 10,
            duration: 1,
            ease: "power2.out",
            onUpdate: () => {
                camera.lookAt(0, 1.5, 0);
            }
        });
        gsap.to(controls.target, {
            x: 0,
            y: 1.5,
            z: 0,
            duration: 1,
            ease: "power2.out"
        });
    }
}

function onWindowResize(container) {
    if (!container || !camera || !renderer) return;
    const width = container.clientWidth;
    const height = container.clientHeight;
    camera.aspect = width / height;
    camera.updateProjectionMatrix();
    renderer.setSize(width, height);
}