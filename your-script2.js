import * as THREE from 'https://cdn.jsdelivr.net/npm/three@0.118/build/three.module.js';
import { OBJLoader } from 'https://cdn.jsdelivr.net/npm/three@0.118/examples/jsm/loaders/OBJLoader.js';
import { OrbitControls } from 'https://cdn.jsdelivr.net/npm/three@0.118/examples/jsm/controls/OrbitControls.js';
import { GLTFLoader } from 'https://cdn.jsdelivr.net/npm/three@0.118.1/examples/jsm/loaders/GLTFLoader.js';

function init() {
    var scene = new THREE.Scene();
    var leftLight = generateSpotlight('rgb(255,200,200)', 0.5);
    var rightLight = generateSpotlight('rgb(255,200,200)', 1.2);
    leftLight.position.set(6, 8, 10);
    rightLight.position.set(30, 20, -10)

    var filenames = ['px', 'nx', 'py', 'ny', 'pz', 'nz'];
    var reflectionCube = new THREE.CubeTextureLoader().load(filenames.map(
        function(filename) {
            return '/img/three-background/' + filename + ".jpg";
        }
    ));
    scene.background = reflectionCube;
    scene.add(leftLight);
    scene.add(rightLight);
    var camera = new THREE.PerspectiveCamera(45, window.innerWidth/window.innerHeight, 1, 1000);
camera.position.x = 1;
camera.position.y = 5;
camera.position.z = 5;
camera.lookAt(new THREE.Vector3(0,0,-5));

const objloader = new OBJLoader();
objloader.load('img/obj/Bike.obj', function(object){
    object.traverse(function(child) {
        if (child instanceof THREE.Mesh) {
            // Prüfen Sie die Geometrie oder das Material des Kindes, um das unerwünschte Objekt zu identifizieren
            if (isUnwantedObject(child)) {
                object.remove(child);
            }
        }
    });

    object.scale.set(4, 4, 4);
    // Position des Fahrrads auf den Boden setzen (Y-Achse anpassen)
    scene.add(object);
});

function isUnwantedObject(child) {
    // Identifizieren Sie das unerwünschte Objekt anhand seines Namens
    if (child.name === 'Plane') {
        return true;
    }
    return false;
}

var renderer = new THREE.WebGLRenderer();
renderer.shadowMap.enabled = true;
renderer.setSize(window.innerWidth, window.innerHeight);
renderer.setClearColor('rgb(60,60,60)');
document.getElementById('webgl').appendChild(renderer.domElement);

var controls = new OrbitControls(camera, renderer.domElement);

update(renderer, scene, camera, controls);
return scene;
}


function generateSpotlight(color, intensity) {
    var light = new THREE.SpotLight(color, intensity);
    light.castShadow = true;
    light.penumbra = 0.5;
    light.shadow.mapSize.width = 2048;
    light.shadow.mapSize.height = 2048;
    light.shadow.bias = 0.001;
    return light;
}

function update(renderer, scene, camera, controls){
    renderer.render(scene, camera);
    controls.update();
    requestAnimationFrame(function() {
        update(renderer, scene, camera, controls);
    });
}

var scene = init()