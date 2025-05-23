<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Basic Example — Networked-Aframe</title>
    <meta name="description" content="Basic Example — Networked-Aframe" />

    <script src="https://aframe.io/releases/1.7.0/aframe.min.js"></script>

    <!--   NAF basic requirements   -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.8.1/socket.io.min.js"></script>
    <script src="https://naf-examples.glitch.me/easyrtc/easyrtc.js"></script>
    <script src="https://naf-examples.glitch.me/dist/networked-aframe.js"></script>

    <!--    used for flying in this demo  -->
    <script src="https://cdn.jsdelivr.net/gh/c-frame/aframe-extras@7.5.4/dist/aframe-extras.controls.min.js"></script>

    <!--   used for the pretty environment   -->
    <script src="https://cdn.jsdelivr.net/npm/aframe-environment-component@1.5.0/dist/aframe-environment-component.min.js"></script>

    <!--   used to prevent players from spawning on top of each other so much  -->
    <script src="https://naf-examples.glitch.me/js/spawn-in-circle.component.js"></script>

    <script>
        // Called by Networked-Aframe when connected to server (optional)
        // (this api will change in future versions)
        function onConnect() {
            console.log('onConnect', new Date());
        }

        // Note the way we're establishing the NAF schema here; this is a bit awkward
        // because of a recent bug found in the original handling. This mitigates that bug for now,
        // until a refactor in the future that should fix the issue more cleanly.
        // see issue https://github.com/networked-aframe/networked-aframe/issues/267

        // This one is necessary, because tracking the .head child component's material's color
        // won't happen unless we tell NAF to keep it in sync, like here.
        NAF.schemas.getComponentsOriginal = NAF.schemas.getComponents;
        NAF.schemas.getComponents = (template) => {
            if (!NAF.schemas.hasTemplate('#avatar-template')) {
                NAF.schemas.add({
                    template: '#avatar-template',
                    components: [
                        // position and rotation are added by default if we don't include a template, but since
                        // we also want to sync the color, we need to specify a custom template; if we didn't
                        // include position and rotation in this custom template, they'd not be synced.
                        {
                            component: 'position',
                            requiresNetworkUpdate: NAF.utils.vectorRequiresUpdate(0.001)
                        },
                        {
                            component: 'rotation',
                            requiresNetworkUpdate: NAF.utils.vectorRequiresUpdate(0.5)
                        },

                        // this is how we sync a particular property of a particular component for a particular
                        // child element of template instances.
                        {
                            selector: '.head',
                            component: 'material',
                            property: 'color' // property is optional; if excluded, syncs everything in the component schema
                        }
                    ]
                });
            }

            if (!NAF.schemas.hasTemplate('#rig-template')) {
                NAF.schemas.add({
                    template: '#rig-template',
                    components: [{
                            component: 'position',
                            requiresNetworkUpdate: NAF.utils.vectorRequiresUpdate(0.001)
                        },
                        {
                            component: 'rotation',
                            requiresNetworkUpdate: NAF.utils.vectorRequiresUpdate(0.5)
                        }
                    ]
                });
            }

            const components = NAF.schemas.getComponentsOriginal(template);
            return components;
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/aframe-randomizer-components@3.0.2/dist/aframe-randomizer-components.min.js"></script>
</head>

<body>
    <a-scene
        networked-scene="
      room: basic;
      debug: true;
      adapter: wseasyrtc;
    ">
        <a-assets>
            <!-- Templates -->
            <!-- Camera Rig / Player -->
            <template id="rig-template">
                <a-entity></a-entity>
            </template>

            <!-- Head / Avatar -->
            <!--      a few spheres make a head + eyes + pupils    -->
            <template id="avatar-template">
                <a-entity class="avatar">
                    <!-- notice this child sphere, with class .head, has the random-color component; this modifies the material component's color property -->
                    <a-sphere class="head" scale="0.2 0.22 0.2" random-color></a-sphere>
                    <a-entity class="face" position="0 0.05 0">
                        <a-sphere class="eye" color="white" position="0.06 0.05 -0.16" scale="0.04 0.04 0.04">
                            <a-sphere class="pupil" color="black" position="0 0 -1" scale="0.2 0.2 0.2"></a-sphere>
                        </a-sphere>
                        <a-sphere class="eye" color="white" position="-0.06 0.05 -0.16" scale="0.04 0.04 0.04">
                            <a-sphere class="pupil" color="black" position="0 0 -1" scale="0.2 0.2 0.2"></a-sphere>
                        </a-sphere>
                    </a-entity>
                </a-entity>
            </template>
            <!-- /Templates -->
        </a-assets>

        <a-entity environment="preset:starry;groundColor:#000000;"></a-entity>

        <!--   Here we declare only the local user's avatar, which we then broadcast to other users     -->
        <!--   The 'spawn-in-circle' component will set the position and rotation of #rig;
             because this entity also has the networked component, and position and rotation are tracked by default,
             the changes made by spawn-in-circle will be kept in sync with other networked users.
             Also note that by adding the networked component with a template reference, we generate that full template,
             including all applicable child elements. However, because we don't need to see our own avatar, we use the
             `attachTemplateToLocal:false` option. This makes our local copies invisible on our machine, but visible on everyone else's.
      -->
        <a-entity id="rig" movement-controls="fly:true;" spawn-in-circle="radius:3" networked="template:#rig-template;">
            <!-- Here we add the camera. Adding the camera within a 'rig' is standard practice.
         We set the camera to head height for e.g. computer users, but otherwise never touch it again; if the user enters VR,
         its rotation and position will be updated by the headset in VR. If we need to touch the user's position
         or rotation, we always do that by adjusting the rig parent of the active camera. By making that rig--and the
         active camera appended to it--both networked, we ensure all player movement is kept in sync.
        -->
            <a-entity
                id="player"
                camera
                position="0 1.6 0"
                look-controls
                networked="template:#avatar-template;"
                visible="false">
            </a-entity>
        </a-entity>
    </a-scene>

    <script>
        // Called by Networked-Aframe when connected to server
        // Optional to use; this API will change in the future
        function onConnect() {
            console.log('onConnect', new Date());
        }
    </script>
</body>

</html>